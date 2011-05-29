<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Response
{
    public $code;
    
    public $header;
    
    public $data;
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function getHeader()
    {
        return $this->header;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
?>