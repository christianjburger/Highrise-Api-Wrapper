<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Entity_ContactData_InstantMessenger implements Highrise_EntityDataObject
{
    public $id;
    public $address;
    public $protocol;
    public $location;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('instant-messenger'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('address', $this->address));
        $node->appendChild(new DOMElement('protocol', $this->protocol));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>