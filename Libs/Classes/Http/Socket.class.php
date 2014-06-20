<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Http
 */
require_once(dirname(__FILE__) . '/Headers.class.php');
/**
 * A sockets based (stream_socket_client) adapter class for Zend_Http_Client. Can be used
 * on almost every PHP environment, and does not require any special extensions.
 *
 * @category   Zend
 * @package    Zend_Http
 * @subpackage Client_Adapter
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Socket
{
    /**
     * The socket for server connection
     *
     * @var resource|null
     */
    protected $socket = null;

    /**
     * What host/port are we connected to?
     *
     * @var array
     */
    protected $connected_to = array(null, null);

    /**
     * Stream for storing output
     *
     * @var resource
     */
    protected $out_stream = null;

    /**
     * Parameters array
     *
     * @var array
     */
    protected $config = array(
        'persistent'    => false,
        'ssltransport'  => 'ssl',
        'sslcert'       => null,
        'sslpassphrase' => null,
        'sslusecontext' => false,
        'timeout'       => 30
    );

    /**
     * Request method - will be set by write() and might be used by read()
     *
     * @var string
     */
    protected $method = null;

    /**
     * Stream context
     *
     * @var resource
     */
    protected $_context = null;



    protected $responseHeader = null;

    protected $responseBody = '';

    protected $requestHeaders = array('Host' =>'',
                                      'Connection'=>'close',
                                      'Accept-encoding'=>'gzip, deflate',
                                      'User-Agent'=>'Zend_Socket');

    protected $requestParameters = array();

    protected $httpVersion = '1.1';

    protected $dataToSend = '';

    /**
     * Adapter constructor, currently empty. Config is set using setConfig()
     *
     */
    public function __construct($url, $method='get',$dataToSend='',  $headers=array(), $options=array())
    {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setConfig($options);
        $this->dataToSend = $dataToSend;
        $this->setHeaders($headers);

        if(!isset($this->config['timeout'])) {
            $this->config['timeout'] = 30;
        }
    }

    public function setUrl($url)
    {
        $urlInfo = parse_url($url);
        if(! isset($urlInfo['scheme'], $urlInfo['host'])) {
            throw Exception('wrong URL set');
            exit;
        }
        $urlInfo['scheme'] = strtolower($urlInfo['scheme']);
        $urlInfo['path'] = isset($urlInfo['path']) ? $urlInfo['path'] : '/';
        $urlInfo['port'] = isset($urlInfo['port']) ? $urlInfo['port'] : ($urlInfo['scheme']=='https'? '443':'80');
        if($urlInfo['scheme']=='https') {
            $this->config['secure'] = true;
        }

        if(! is_resource($this->socket)) {
            $this->urlInfo = $urlInfo;
            $this->dataToSend = '';
        } else if ($this->urlInfo['host'] != $urlInfo['host'] || $this->urlInfo['scheme']!=$urlInfo['scheme']
                || $this->urlInfo['port'] != $urlInfo['port']) {
            $this->close();
            $this->urlInfo = $urlInfo;
            $this->dataToSend = '';
        }

        $this->setHeaders(array('Host'=>$this->urlInfo['host']));

        return $this;
    }

    public function setMethod($method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    public function setRequestParameters($parameters)
    {
        $this->requestParameters = $parameters;

        return $this;
    }

    public function setHeaders($headers)
    {
        if(is_array($headers)) {
            foreach($headers as $key=>$value) {
                $this->requestHeaders[$key] = $value;
            }
        }

        return $this;
    }

    public function setHttpVersion ($httpVersion)
    {
        if(in_array($httpVersion, array('1.0', '1.1'))) {
            $this->httpVersion = $httpVersion;
        }

        return $this;
    }

    public function setDataToSend ($dataToSend)
    {
        $this->dataToSend = $dataToSend;

        return $this;
    }



    public function execute()
    {
        $this->write();
        $response = $this->read();
        $response = explode("\r\n\r\n", $response);
        $this->responseBody = $response[1];

        if(strpos(strtolower($this->responseHeader->getHeader('content-type')), 'application/json')!==false) {
            if(preg_match('/\{.*\}/s', $response[1], $match)) {
                $this->responseBody = $match[0];
            }
        }


        return $this->responseBody;
    }


    /**
     * Set the configuration array for the adapter
     *
     * @param Zend_Config | array $config
     */
    public function setConfig($config = array())
    {
        if(is_array($config)) {
            foreach ($config as $k => $v) {
                $this->config[strtolower($k)] = $v;
            }
        }

        return $this;
    }

    /**
      * Retrieve the array of all configuration options
      *
      * @return array
      */

    /**
     * Retrieve the array of all configuration options
     *
     * @return array
     */
    public function getConfig($configKey='', $defaultValue=null)
    {
        if(is_string($configKey)) {
            if(isset($this->config[$configKey])) {
                return $this->config[$configKey];
            } else {
                return $defaultValue;
            }
        }
        return $this->config;
    }

     /**
     * Set the stream context for the TCP connection to the server
     *
     * Can accept either a pre-existing stream context resource, or an array
     * of stream options, similar to the options array passed to the
     * stream_context_create() PHP function. In such case a new stream context
     * will be created using the passed options.
     *
     * @since  Zend Framework 1.9
     *
     * @param  mixed $context Stream context or array of context options
     * @return Zend_Http_Client_Adapter_Socket
     */
    public function setStreamContext($context)
    {
        if (is_resource($context) && get_resource_type($context) == 'stream-context') {
            $this->_context = $context;

        } elseif (is_array($context)) {
            $this->_context = stream_context_create($context);

        } else {
            // Invalid parameter
            throw new Exception(
                "Expecting either a stream context resource or array, got " . gettype($context)
            );
        }

        return $this;
    }

    /**
     * Get the stream context for the TCP connection to the server.
     *
     * If no stream context is set, will create a default one.
     *
     * @return resource
     */
    public function getStreamContext()
    {
        if (! $this->_context) {
            $this->_context = stream_context_create();
        }

        return $this->_context;
    }

    /**
     * Connect to the remote server
     *
     * @param string  $host
     * @param int     $port
     * @param boolean $secure
     */
    public function connect()
    {
        // If the URI should be accessed via SSL, prepend the Hostname with ssl://
        $host = ($this->config['secure'] ? $this->config['ssltransport'] : 'tcp') . '://' . $this->urlInfo['host'];

        // If we are connected to the wrong host, disconnect first
        if (($this->connected_to[0] != $host || $this->connected_to[1] != $this->urlInfo['port'])) {
            if (is_resource($this->socket)) $this->close();
        }

        // Now, if we are not connected, connect
        if (! is_resource($this->socket) || ! $this->config['keepalive']) {
            $context = $this->getStreamContext();
            if ($this->config['secure'] || $this->config['sslusecontext']) {
                if ($this->config['sslcert'] !== null) {
                    if (! stream_context_set_option($context, 'ssl', 'local_cert',
                                                    $this->config['sslcert'])) {
                        throw new Exception('Unable to set sslcert option');
                    }
                }
                if ($this->config['sslpassphrase'] !== null) {
                    if (! stream_context_set_option($context, 'ssl', 'passphrase',
                                                    $this->config['sslpassphrase'])) {
                        throw new Exception('Unable to set sslpassphrase option');
                    }
                }
            }

            $flags = STREAM_CLIENT_CONNECT;
            if ($this->config['persistent']) $flags |= STREAM_CLIENT_PERSISTENT;

            $this->socket = @stream_socket_client($host . ':' . $this->urlInfo['port'],
                                                  $errno,
                                                  $errstr,
                                                  (int) $this->config['timeout'],
                                                  $flags,
                                                  $context);

            if (! $this->socket) {
                $this->close();
                throw new Exception(
                    'Unable to Connect to ' . $host . ':' . $this->urlInfo['port'] . '. Error #' . $errno . ': ' . $errstr);
            }

            // Set the stream timeout
            if (! stream_set_timeout($this->socket, (int) $this->config['timeout'])) {
                throw new Exception('Unable to set the connection timeout');
            }

            // Update connected_to
            $this->connected_to = array($host, $this->urlInfo['port']);
        }
    }

    /**
     * Send request to the remote server
     *
     * @param string        $method
     * @param Zend_Uri_Http $uri
     * @param string        $http_ver
     * @param array         $headers
     * @param string        $body
     * @return string Request as string
     */
    public function write()
    {
        $this->connect();
        // Make sure we're properly connected
        if (! $this->socket) {
            throw new Exception('Trying to write but we are not connected');
        }

        $host = $this->urlInfo['host'];
        $host = (strtolower($this->urlInfo['scheme']) == 'https' ? $this->config['ssltransport'] : 'tcp') . '://' . $host;
        if (is_resource($this->socket) && ($this->connected_to[0] != $host || $this->connected_to[1] != $this->urlInfo['port'])) {
            throw new Exception('Trying to write but we are connected to the wrong host');
        }

        if (is_string($this->dataToSend) && strlen($this->dataToSend)>0) {
            $this->setMethod('post');
            $this->requestHeaders['Content-Length'] = strlen($this->dataToSend);
        } else if(is_array($this->dataToSend) && count($this->dataToSend)) {

            $queryString = http_build_query($this->dataToSend);
            if(strtolower($this->method)=='get') {
                if ($this->urlInfo['query']) {
                    $path .= '&' . $queryString;
                } else {
                    $path .= '?' . $queryString;
                }
            } else {
                $this->requestHeaders['Content-type'] = 'application/x-www-form-urlencoded';
                $this->requestHeaders['Content-Length'] = strlen($queryString);
                $this->dataToSend = $queryString;
            }
        }


        // Build request headers
        $path = $this->urlInfo['path'];
        if ($this->urlInfo['query']) $path .= '?' . $this->urlInfo['query'];
        $request = "{$this->method} {$path} HTTP/{$this->httpVersion}\r\n";
        foreach ($this->requestHeaders as $k => $v) {
            if (is_string($k)) $v = ucfirst($k) . ": $v";
            $request .= "$v\r\n";
        }

        // Add the request body
        $request .= "\r\n" . $this->dataToSend;

        // Send the request
        if (! @fwrite($this->socket, $request)) {
            throw new Exception('Error writing request to server');
        }

        return $request;
    }

    /**
     * Read response from server
     *
     * @return string
     */
    public function read()
    {
        // First, read headers only
        $response = '';
        $gotStatus = false;

        while (($line = @fgets($this->socket)) !== false) {
            $gotStatus = $gotStatus || (strpos($line, 'HTTP') !== false);
            if ($gotStatus) {
                $response .= $line;
                if (rtrim($line) === '') break;
            }
        }

        $this->_checkSocketReadTimeout();



        $responseInfo = explode("\r\n\r\n", $response);
        $this->responseHeader = new Headers();
        $this->responseHeader->loadHeadersFromString($responseInfo[0]);

        $statusCode = $this->responseHeader->getStatusCode();

        // Handle 100 and 101 responses internally by restarting the read again
        if ($statusCode == 100 || $statusCode == 101) return $this->read();

        /**
         * Responses to HEAD requests and 204 or 304 responses are not expected
         * to have a body - stop reading here
         */
        if ($statusCode == 304 || $statusCode == 204 ||
            $this->method == 'head') {

            // Close the connection if requested to do so by the server
            if ($this->responseHeader->getHeader('connection') == 'close') {
                $this->close();
            }
            return $response;
        }

        // If we got a 'transfer-encoding: chunked' header
        if ($this->responseHeader->getHeader('transfer-encoding')) {

            if (strtolower($this->responseHeader->getHeader('transfer-encoding')) == 'chunked') {

                do {
                    $line  = @fgets($this->socket);
                    $this->_checkSocketReadTimeout();

                    $chunk = $line;

                    // Figure out the next chunk size
                    $chunksize = trim($line);
                    if (! ctype_xdigit($chunksize)) {
                        $this->close();
                        throw new Exception('Invalid chunk size "' .
                            $chunksize . '" unable to read chunked body');
                    }

                    // Convert the hexadecimal value to plain integer
                    $chunksize = hexdec($chunksize);

                    // Read next chunk
                    $read_to = ftell($this->socket) + $chunksize;

                    do {
                        $current_pos = ftell($this->socket);
                        if ($current_pos >= $read_to) break;

                        if($this->out_stream) {
                            if(stream_copy_to_stream($this->socket, $this->out_stream, $read_to - $current_pos) == 0) {
                              $this->_checkSocketReadTimeout();
                              break;
                             }
                        } else {
                            $line = @fread($this->socket, $read_to - $current_pos);
                            if ($line === false || strlen($line) === 0) {
                                $this->_checkSocketReadTimeout();
                                break;
                            }
                                    $chunk .= $line;
                        }
                    } while (! feof($this->socket));

                    $chunk .= @fgets($this->socket);
                    $this->_checkSocketReadTimeout();

                    if(!$this->out_stream) {
                        $response .= $chunk;
                    }
                } while ($chunksize > 0);
            } else {
                $this->close();
                throw new Exception('Cannot handle "' .
                    $this->responseHeader->getHeader('transfer-encoding') . '" transfer encoding');
            }

            // We automatically decode chunked-messages when writing to a stream
            // this means we have to disallow the Zend_Http_Response to do it again
            if ($this->out_stream) {
                $response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $response);
            }
        // Else, if we got the content-length header, read this number of bytes
        } elseif ($this->responseHeader->getHeader('content-length')) {
            $contentLength = $this->responseHeader->getHeader('content-length');
            // If we got more than one Content-Length header (see ZF-9404) use
            // the last value sent
            if (is_array($contentLength)) {
                $contentLength = $contentLength[count($contentLength) - 1];
            }

            $current_pos = ftell($this->socket);
            $chunk = '';

            for ($read_to = $current_pos + $contentLength;
                 $read_to > $current_pos;
                 $current_pos = ftell($this->socket)) {

                 if($this->out_stream) {
                     if(@stream_copy_to_stream($this->socket, $this->out_stream, $read_to - $current_pos) == 0) {
                          $this->_checkSocketReadTimeout();
                          break;
                     }
                 } else {
                    $chunk = @fread($this->socket, $read_to - $current_pos);
                    if ($chunk === false || strlen($chunk) === 0) {
                        $this->_checkSocketReadTimeout();
                        break;
                    }

                    $response .= $chunk;
                }

                // Break if the connection ended prematurely
                if (feof($this->socket)) break;
            }

        // Fallback: just read the response until EOF
        } else {

            do {
                if($this->out_stream) {
                    if(@stream_copy_to_stream($this->socket, $this->out_stream) == 0) {
                          $this->_checkSocketReadTimeout();
                          break;
                     }
                }  else {
                    $buff = @fread($this->socket, 8192);
                    if ($buff === false || strlen($buff) === 0) {
                        $this->_checkSocketReadTimeout();
                        break;
                    } else {
                        $response .= $buff;
                    }
                }

            } while (feof($this->socket) === false);

            $this->close();
        }

        // Close the connection if requested to do so by the server
        if ($this->responseHeader->getHeader('connection') == 'close') {
            $this->close();
        }

        return $response;
    }

    /**
     * Close the connection to the server
     *
     */
    public function close()
    {
        if (is_resource($this->socket)) @fclose($this->socket);
        $this->socket = null;
        $this->connected_to = array(null, null);
    }

    /**
     * Check if the socket has timed out - if so close connection and throw
     * an exception
     *
     * @throws Zend_Http_Client_Adapter_Exception with READ_TIMEOUT code
     */
    protected function _checkSocketReadTimeout()
    {
        if ($this->socket) {
            $info = stream_get_meta_data($this->socket);
            $timedout = $info['timed_out'];
            if ($timedout) {
                $this->close();
                throw new Exception(
                    "Read timed out after {$this->config['timeout']} seconds"
                );
            }
        }
    }

    /**
     * Set output stream for the response
     *
     * @param resource $stream
     * @return Zend_Http_Client_Adapter_Socket
     */
    public function setOutputStream($stream)
    {
        $this->out_stream = $stream;
        return $this;
    }

    /**
     * Destructor: make sure the socket is disconnected
     *
     * If we are in persistent TCP mode, will not close the connection
     *
     */
    public function __destruct()
    {
        if (! $this->config['persistent']) {
            if ($this->socket) $this->close();
        }
    }
}


define('YIXIN_APP_ID', 'd54e53621b514858b4ebbc149fcd95e8');
define('YIXIN_APP_SECRET', '48ed9e6a9fc544479e608f578d25b1be');
$getTokenUrl = 'https://api.yixin.im/cgi-bin/token?grant_type=client_credential&appid='.YIXIN_APP_ID.'&secret='.YIXIN_APP_SECRET;

$url = $getTokenUrl;

define('WECHAT_APP_ID', 'wxd25e67dfa7b2dd59');
define('WECHAT_APP_SECRET', 'd91611978da6c8b67f41a9945c0599ae');
$getTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APP_ID.'&secret='. WECHAT_APP_SECRET;

//echo file_get_contents($getTokenUrl);exit;
$method='get';
$dataToSend='';
$headers=array();
$options=array();

$socketClass = new Socket($url, $method, $dataToSend, $headers, $options);

$json = $socketClass->execute();

    $result = json_decode($json,true);
    $token = $result['access_token'];

$menuJsonData = '{
        "button": [
            {
                "name": "案例展示",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "微信",
                        "key": "weixin"
                    },
                    {
                        "type": "click",
                        "name": "易信",
                        "key": "yixin"
                    },
                    {
                        "type": "click",
                        "name": "飞信",
                        "key": "feixin"
                    },
                    {
                        "type": "click",
                        "name": "来往",
                        "key": "laiwang"
                    },
                    {
                        "type": "click",
                        "name": "旺信",
                        "key": "wangxin"
                    }
                ]
            },
            {
                "name": "产品介绍",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "微信",
                        "key": "aboutweixin"
                    },
                    {
                        "type": "click",
                        "name": "飞信",
                        "key": "aboutfeixin"
                    },
                    {
                        "type": "click",
                        "name": "易信",
                        "key": "aboutyixin"
                    }
                ]
            },
            {
                "name": "帮助",
                "sub_button": [
                    {
                        "type": "click",
                        "name": "关于我们",
                        "key": "aboutus"
                    },
                    {
                        "type": "click",
                        "name": "公司介绍",
                        "key": "cominfo"
                    }
                ]
            }
        ]
    }';

    $result = json_decode($json,true);
    $token = $result['access_token'];
    $setMenuUrl = 'https://api.yixin.im/cgi-bin/menu/create?access_token='.$token;
echo $setMenuUrl;
echo $socketClass->setUrl($setMenuUrl)->setDataToSend($menuJsonData)->execute();