<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/EntityDataObject.php';

class Highrise_Entity_ContactData_PhoneNumber implements Highrise_EntityDataObject
{
    public $location;
    public $number;
    public $id;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('phone-number'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('number', $this->number));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>