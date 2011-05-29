<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Response.php';

class Highrise_Api
{
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    
    const API_URL = '.highrisehq.com';
    
    protected $_token;
    
    protected $_account;
    
    protected $_debug;
    
    public function __construct($account, $token, $debug = false)
    {
        $this->_account = $account;
        $this->_token   = $token;
        $this->_debug   = $debug;
    }
    
    public function request(Highrise_Request $request)
    {
        $response = $this->_sendRequest(
            $request->getEndpoint(), 
            $request->getMethod(), 
            $request->getData()
        );
        
        if ($request->getExpectedResponse() != $response->getCode()) 
        {
            throw new Exception('Expected response ' . $request->getExpectedResponse() . ' but API returned ' . $response->getCode());
        }
        
        if ($response->getData() === false)
        {
            throw new Exception('HTTP request failed');
        }
        
        return $response;
    }
    
    /**
     * @todo HTTPS
     * Enter description here ...
     * @param unknown_type $endpoint
     * @param unknown_type $method
     * @param unknown_type $data
     */
    protected function _sendRequest($endpoint, $method, $data = null)
    {
        //$encoded           = ($data) ? http_build_query($data) : null;
        $additionalHeaders = null;
        $request           = curl_init();
    
        $options = array(
            CURLOPT_URL            => 'https://' . $this->_account . self::API_URL . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_HTTPHEADER     => array('Content-Length: ' . strlen($encoded)),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST           => ($method == self::METHOD_POST) ? true : false,
            CURLOPT_POSTFIELDS     => ($data && $method == self::METHOD_POST) ? $data : null,
            CURLOPT_HTTPHEADER     => array('Content-Type: application/xml', $additionalHeaders),
            CURLOPT_USERPWD        => $this->_token . ':X',
            CURLOPT_VERBOSE        => ($this->_debug) ? true : false   
        );
        
        if ($method != self::METHOD_POST)
        {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt_array($request, $options);
        $responseData = curl_exec($request);
        $header   = curl_getinfo($request); 
        curl_close($request);
        
        $response           = new Highrise_Response();
        $response->code     = $header['http_code'];
        $response->header   = $header;
        $response->data     = $responseData;
        
        if ($this->_debug) print_r($response);
        return $response;
    }
}
?>