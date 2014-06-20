<?php
/**
 * Basic HTTP headers collection functionality
 * Handles aggregation of headers
 *
 * @category   Zend
 * @package    Zend_Http
 * @see        http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2
 */
class Headers
{

    /**
     * @var array Array of header array information or Header instances
     */
    protected $headers = array();

    /**
     * Populates headers from string representation
     *
     * Parses a string for headers, and aggregates them, in order, in the
     * current instance, primarily as strings until they are needed (they
     * will be lazy loaded)
     *
     * @param  string $string
     * @return Headers
     * @throws Exception\RuntimeException
     */
    public function loadHeadersFromString($string)
    {
        $current = array();
        $lines = explode("\r\n", $string);
        if (!is_array($lines) || count($lines) == 1) {
            $lines = explode("\n", $string);
        }

        $firstLine = array_shift($lines);

        $regex   = '/^HTTP\/(?P<version>1\.[01]) (?P<status>\d{3})(?:[ ]+(?P<reason>.*))?$/';
        $matches = array();
        if (!preg_match($regex, $firstLine, $matches)) {
            throw new Exception(
                'A valid response status line was not found in the provided string'
            );
        }

        $this->headers['version'] = $matches['version'];
        $this->headers['StatusCode'] = $matches['status'];
        $this->headers['ReasonPhrase'] = isset($matches['reason']) ? $matches['reason'] : '';

        // iterate the header lines, some might be continuations
        foreach ($lines as $line) {

            // check if a header name is present
            if (preg_match('/^(?P<name>[^()><@,;:\"\\/\[\]?=}{ \t]+):.*$/', $line, $matches)) {
                if ($current) {
                    $_line = explode(':', $current['line'], 2);
                    $this->headers[$current['name']]     = trim($_line[1]);
                }
                $current = array(
                    'name' => $matches['name'],
                    'line' => trim($line)
                );
            } elseif (preg_match('/^\s+.*$/', $line, $matches)) {
                // continuation: append to current line
                $current['line'] .= trim($line);
            } elseif (preg_match('/^\s*$/', $line)) {
                // empty line indicates end of headers
                break;
            } else {
                // Line does not match header format!
                throw new Exception(sprintf(
                    'Line "%s"does not match header format!',
                    $line
                ));
            }
        }
        if ($current) {
            $_line = explode(':', $current['line'], 2);
            $this->headers[$current['name']]     = trim($_line[1]);
        }

        return $this;
    }

    public function getHeader ($headerKey=null, $default=null)
    {
        if(is_string($headerKey)) {

        foreach($this->headers as $_key=>$_value) {
            if(strtolower($_key)==strtolower($headerKey)) {
                return $_value;
            }
        }
        return $default;
        }

        return $this->headers;
    }


    public function getStatusCode ()
    {
        return $this->getHeader('statusCode');
    }


}

/* EOF */