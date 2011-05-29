<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/EntityDataObject.php';

class Highrise_Entity_ContactData_WebAddress implements Highrise_EntityDataObject
{
    public $id;
    public $url;
    public $location;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('web-address'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('url', $this->url));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>