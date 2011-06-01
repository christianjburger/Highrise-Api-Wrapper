<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Client_ProxyAbstract
{
    protected $_client;
    
    public function __construct($account,$token, $debug = false)
    {
        $this->_client = new Highrise_Client($account, $token, $debug);
    }
    
    /**
     * @return Highrise_Client $client
     */
    public function getClient()
    {
        return $this->_client;
    }
}
?>