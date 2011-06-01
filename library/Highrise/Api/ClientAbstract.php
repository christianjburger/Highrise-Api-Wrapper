<?php
/** 
 * @author cjb
 * 
 * 
 */
abstract class Highrise_Api_ClientAbstract
{
    protected $_client;
    
    public function __construct(Highrise_Api_Client $client = null)
    {
        if ($client !== null) $this->setClient($client);
    }
    
    public function setAccount($account,$token, $debug = false)
    {
        $this->setClient(new Highrise_Api_Client($account, $token, $debug));
    }
    
    public function setClient(Highrise_Api_Client $client)
    {
        $this->_client = $client;
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