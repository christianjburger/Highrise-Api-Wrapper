<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Client_Request
{
    public $endpoint;
    
    public $data;
    
    public $method;
    
    public $expected;
    
    public function getEndpoint()
    {
        return $this->endpoint;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getExpectedResponse()
    {
        return $this->expected;
    }
}
?>