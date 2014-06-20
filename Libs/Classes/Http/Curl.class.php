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
 * An adapter class for Zend\Http\Client based on the curl extension.
 * Curl requires libcurl. See for full requirements the PHP manual: http://php.net/curl
 *
 * @category   Zend
 * @package    Zend_Http
 * @subpackage Client_Adapter
 */
class Curl
{
    /**
     * Parameters array
     *
     * @var array
     */
    protected $config = array();

    /**
     * What host/port are we connected to?
     *
     * @var array
     */
    protected $connectedTo = array(null, null);

    /**
     * The curl session handle
     *
     * @var resource|null
     */
    protected $curl = null;

    /**
     * List of cURL options that should never be overwritten
     *
     * @var array
     */
    protected $invalidOverwritableCurlOptions;

    /**
     * Response gotten from server
     *
     * @var string
     */
    protected $response = null;

    /**
     * Stream for storing output
     *
     * @var resource
     */
    protected $outputStream;



    protected $responseHeader = null;

    protected $responseBody = '';

    protected $requestHeaders = array('Connection'=>'close',
                                      'Accept-encoding'=>'gzip, deflate',
                                      'User-Agent'=>'Zend_Socket');

    protected $requestParameters = array();

    protected $httpVersion = '1.1';

    protected $dataToSend = '';

    protected $urlInfo = null;

    /**
     * Adapter constructor
     *
     * Config is set using setOptions()
     *
     * @throws AdapterException\InitializationException
     */
    public function __construct($url, $method='get',$dataToSend='', $headers=array(), $options=array())
    {
        if (!extension_loaded('curl')) {
            throw new Exception('cURL extension has to be loaded to use Curl library');
            exit;
        }


        $this->setUrl($url);
        $this->setMethod($method);
        $this->setConfig($options);
        $this->dataToSend = $dataToSend;
        $this->setHeaders($headers);

        if(!isset($this->config['timeout'])) {
            $this->config['timeout'] = 30;
        }

        $this->invalidOverwritableCurlOptions = array(
            CURLOPT_HTTPGET,
            CURLOPT_POST,
            CURLOPT_UPLOAD,
            CURLOPT_CUSTOMREQUEST,
            CURLOPT_HEADER,
            CURLOPT_RETURNTRANSFER,
            CURLOPT_HTTPHEADER,
            CURLOPT_POSTFIELDS,
            CURLOPT_INFILE,
            CURLOPT_INFILESIZE,
            CURLOPT_PORT,
            CURLOPT_MAXREDIRS,
            CURLOPT_CONNECTTIMEOUT,
            CURL_HTTP_VERSION_1_1,
            CURL_HTTP_VERSION_1_0,
        );
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

        if(! is_resource($this->curl)) {
            $this->urlInfo = $urlInfo;
            $this->dataToSend = '';
        } else {
            if ($this->urlInfo['host'] != $urlInfo['host'] || $this->urlInfo['scheme']!=$urlInfo['scheme']
                    || $this->urlInfo['port'] != $urlInfo['port']) {
                $this->close();
            }
            $this->urlInfo = $urlInfo;
            $this->dataToSend = '';
        }

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
        if (is_string($dataToSend) && strlen($dataToSend)>0) {
            $this->setMethod('post');
            $this->dataToSend = $dataToSend;
        } else if(is_array($dataToSend) && count($dataToSend)) {

            $queryString = http_build_query($dataToSend);
            if(strtolower($this->method)=='get') {
                if (isset($this->urlInfo['query'])) {
                    $this->urlInfo['query'] .= '&' . $queryString;
                } else {
                    $this->urlInfo['query'] .= '?' . $queryString;
                }
            } else {
                $this->dataToSend = $queryString;
            }
        }

        return $this;
    }

    public function execute()
    {
        $this->write();
        $response = $this->read();
        $response = explode("\r\n\r\n", $response);


        $this->responseHeader = new Headers();
        $this->responseHeader->loadHeadersFromString($response[0]);

        $this->responseBody = $response[1];

        if(strpos(strtolower($this->responseHeader->getHeader('content-type')), 'application/json')!==false) {
            if(preg_match('/\{.*\}/s', $response[1], $match)) {
                $this->responseBody = $match[0];
            }
        }


        return $this->responseBody;
    }

    public function setConfig($config = array())
    {
        return $this->setOptions($config);
    }

    /**
     * Set the configuration array for the adapter
     *
     * @param  array|Traversable $options
     * @return Curl
     * @throws AdapterException\InvalidArgumentException
     */
    public function setOptions($options = array())
    {
        if (!is_array($options)) {
            throw new Exception('Array or Traversable object expected, got ' . gettype($options));
            exit;
        }

        /** Config Key Normalization */
        foreach ($options as $k => $v) {
            unset($options[$k]); // unset original value
            $options[str_replace(array('-', '_', ' ', '.'), '', strtolower($k))] = $v; // replace w/ normalized
        }

        if (isset($options['proxyuser']) && isset($options['proxypass'])) {
            $this->setCurlOption(CURLOPT_PROXYUSERPWD, $options['proxyuser'] . ":" . $options['proxypass']);
            unset($options['proxyuser'], $options['proxypass']);
        }

        foreach ($options as $k => $v) {
            $option = strtolower($k);
            switch ($option) {
                case 'proxyhost':
                    $this->setCurlOption(CURLOPT_PROXY, $v);
                    break;
                case 'proxyport':
                    $this->setCurlOption(CURLOPT_PROXYPORT, $v);
                    break;
                default:
                    $this->config[$option] = $v;
                    break;
            }
        }

        return $this;
    }

    /**
      * Retrieve the array of all configuration options
      *
      * @return array
      */
     public function getConfig()
     {
         return $this->config;
     }

    /**
     * Direct setter for cURL adapter related options.
     *
     * @param  string|int $option
     * @param  mixed $value
     * @return Curl
     */
    public function setCurlOption($option, $value)
    {
        if (!isset($this->config['curloptions'])) {
            $this->config['curloptions'] = array();
        }
        $this->config['curloptions'][$option] = $value;
        return $this;
    }

    /**
     * Initialize curl
     *
     * @param  string  $host
     * @param  int     $port
     * @param  boolean $secure
     * @return void
     * @throws AdapterException\RuntimeException if unable to connect
     */
    public function connect()
    {
        $host = $this->urlInfo['host'];
        $port = $this->urlInfo['port'];
        $secure = isset($this->config['secure']) ? $this->config['secure'] : false;
        // If we're already connected, disconnect first
        if ($this->curl) {
            $this->close();
        }

        // If we are connected to a different server or port, disconnect first
        if ($this->curl
            && is_array($this->connectedTo)
            && ($this->connectedTo[0] != $host
            || $this->connectedTo[1] != $port)
        ) {
            $this->close();
        }

        // Do the actual connection
        $this->curl = curl_init();
        $password = isset($this->urlInfo['password']) ? (":".$this->urlInfo['password']) : '';
        $auth     = isset($this->urlInfo['username']) > 0 ? ($this->urlInfo['username']."$password@") : '';
        $port     = isset($this->urlInfo['port']) > 0 ? (":".$this->urlInfo['port']) : '';
        $query    = isset($this->urlInfo['query']) > 0 ? ("?".$this->urlInfo['query']) : '';
        $fragment = isset($this->urlInfo['fragment']) > 0 ? ("#".$this->urlInfo['fragment']) : '';
        $_url = $this->urlInfo['scheme']
             . '://'
             . $auth
             . $this->urlInfo['host']
             . $port
             . $this->urlInfo['path']
             . $query
             . $fragment;
        // set URL
        curl_setopt($this->curl, CURLOPT_URL, $_url);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        if ($port != 80) {
            curl_setopt($this->curl, CURLOPT_PORT, intval($port));
        }

        if (isset($this->config['timeout'])) {
            // Set timeout
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->config['timeout']);
        }

        if (isset($this->config['maxredirects'])) {
            // Set Max redirects
            curl_setopt($this->curl, CURLOPT_MAXREDIRS, $this->config['maxredirects']);
        }

        if (!$this->curl) {
            $this->close();

            throw new Exception('Unable to Connect to ' . $host . ':' . $port);
        }

        if ($secure !== false) {
            // Behave the same like Zend\Http\Adapter\Socket on SSL options.
            if (isset($this->config['sslcert'])) {
                curl_setopt($this->curl, CURLOPT_SSLCERT, $this->config['sslcert']);
            }
            if (isset($this->config['sslpassphrase'])) {
                curl_setopt($this->curl, CURLOPT_SSLCERTPASSWD, $this->config['sslpassphrase']);
            }
        }

        // Update connected_to
        $this->connectedTo = array($this->urlInfo['host'], $this->urlInfo['port']);
    }

    /**
     * Send request to the remote server
     *
     * @param  string        $method
     * @param  \Zend\Uri\Uri $uri
     * @param  float         $httpVersion
     * @param  array         $headers
     * @param  string        $body
     * @return string        $request
     * @throws AdapterException\RuntimeException If connection fails, connected to wrong host, no PUT file defined, unsupported method, or unsupported cURL option
     * @throws AdapterException\InvalidArgumentException if $method is currently not supported
     */
    public function write()
    {
        $this->connect();
        // Make sure we're properly connected
        if (!$this->curl) {
            throw new Exception("Trying to write but we are not connected");
        }

        if ($this->connectedTo[0] != $this->urlInfo['host'] || $this->connectedTo[1] != $this->urlInfo['port']) {
            throw new Exception("Trying to write but we are connected to the wrong host");
        }

        // ensure correct curl call
        $curlValue = true;
        switch ($this->method) {
            case 'GET' :
                $curlMethod = CURLOPT_HTTPGET;
                break;

            case 'POST' :
                $curlMethod = CURLOPT_POST;
                break;

            case 'PUT' :
            case 'PATCH' :
            case 'DELETE' :
            case 'OPTIONS' :
            case 'TRACE' :
            case 'HEAD' :
                $curlMethod = CURLOPT_CUSTOMREQUEST;
                $curlValue = $this->method;
                break;

            default:
                // For now, through an exception for unsupported request methods
                throw new Exception("Method '{$this->method}' currently not supported");
        }

        // get http version to use
        $curlHttp = ($this->httpVersion == '1.1') ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0;

        // mark as HTTP request and set HTTP method
        curl_setopt($this->curl, $curlHttp, true);
        curl_setopt($this->curl, $curlMethod, $curlValue);

        if ($this->outputStream) {
            // headers will be read into the response
            curl_setopt($this->curl, CURLOPT_HEADER, false);
            curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array($this, "readHeader"));
            // and data will be written into the file
            curl_setopt($this->curl, CURLOPT_FILE, $this->outputStream);
        } else {
            // ensure headers are also returned
            curl_setopt($this->curl, CURLOPT_HEADER, true);

            // ensure actual response is returned
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        }

        // Treating basic auth headers in a special way
        if (array_key_exists('Authorization', $this->requestHeaders) && 'Basic' == substr($this->requestHeaders['Authorization'], 0, 5)) {
            curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($this->curl, CURLOPT_USERPWD, base64_decode(substr($this->requestHeaders['Authorization'], 6)));
            unset($this->requestHeaders['Authorization']);
        }

        // set additional headers
        if (!isset($this->requestHeaders['Accept'])) {
            $this->requestHeaders['Accept'] = '';
        }
        $curlHeaders = array();
        foreach ($this->requestHeaders as $key => $value) {
            $curlHeaders[] = $key . ': ' . $value;
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $curlHeaders);

        /**
         * Make sure POSTFIELDS is set after $curlMethod is set:
         * @link http://de2.php.net/manual/en/function.curl-setopt.php#81161
         */
        if ($this->method == 'POST') {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->dataToSend);
        } elseif ($curlMethod == CURLOPT_UPLOAD) {
            // this covers a PUT by file-handle:
            // Make the setting of this options explicit (rather than setting it through the loop following a bit lower)
            // to group common functionality together.
            curl_setopt($this->curl, CURLOPT_INFILE, $this->config['curloptions'][CURLOPT_INFILE]);
            curl_setopt($this->curl, CURLOPT_INFILESIZE, $this->config['curloptions'][CURLOPT_INFILESIZE]);
            unset($this->config['curloptions'][CURLOPT_INFILE]);
            unset($this->config['curloptions'][CURLOPT_INFILESIZE]);
        } elseif ($this->method == 'PUT') {
            // This is a PUT by a setRawData string, not by file-handle
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->dataToSend);
        } elseif ($this->method == 'PATCH') {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->dataToSend);
        }

        // set additional curl options
        if (isset($this->config['curloptions'])) {
            foreach ((array) $this->config['curloptions'] as $k => $v) {
                if (!in_array($k, $this->invalidOverwritableCurlOptions)) {
                    if (curl_setopt($this->curl, $k, $v) == false) {
                        throw new Exception(sprintf("Unknown or erroreous cURL option '%s' set", $k));
                    }
                }
            }
        }

        // send the request
        $response = curl_exec($this->curl);

        // if we used streaming, headers are already there
        if (!is_resource($this->outputStream)) {
            $this->response = $response;
        }

        $request  = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $request .= $this->dataToSend;

        if (empty($this->response)) {
            throw new Exception("Error in cURL request: " . curl_error($this->curl));
        }

        // cURL automatically decodes chunked-messages, this means we have to disallow the Zend\Http\Response to do it again
        if (stripos($this->response, "Transfer-Encoding: chunked\r\n")) {
            $this->response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $this->response);
        }

        // Eliminate multiple HTTP responses.
        do {
            $parts  = preg_split('|(?:\r?\n){2}|m', $this->response, 2);
            $again  = false;

            if (isset($parts[1]) && preg_match("|^HTTP/1\.[01](.*?)\r\n|mi", $parts[1])) {
                $this->response    = $parts[1];
                $again              = true;
            }
        } while ($again);

        // cURL automatically handles Proxy rewrites, remove the "HTTP/1.0 200 Connection established" string:
        if (stripos($this->response, "HTTP/1.0 200 Connection established\r\n\r\n") !== false) {
            $this->response = str_ireplace("HTTP/1.0 200 Connection established\r\n\r\n", '', $this->response);
        }

        return $request;
    }

    /**
     * Return read response from server
     *
     * @return string
     */
    public function read()
    {
        return $this->response;
    }

    /**
     * Close the connection to the server
     *
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
        $this->curl         = null;
        $this->connectedTo = array(null, null);
    }

    /**
     * Get cUrl Handle
     *
     * @return resource
     */
    public function getHandle()
    {
        return $this->curl;
    }

    /**
     * Set output stream for the response
     *
     * @param resource $stream
     * @return Curl
     */
    public function setOutputStream($stream)
    {
        $this->outputStream = $stream;
        return $this;
    }

    /**
     * Header reader function for CURL
     *
     * @param resource $curl
     * @param string $header
     * @return int
     */
    public function readHeader($curl, $header)
    {
        $this->response .= $header;
        return strlen($header);
    }
}


/* EOF */