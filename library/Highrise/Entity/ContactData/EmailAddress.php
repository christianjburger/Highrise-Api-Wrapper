<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/EntityDataObject.php';

class Highrise_Entity_ContactData_EmailAddress implements Highrise_EntityDataObject
{
    public $location;
    public $address;
    public $id;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $address = $xml->appendChild(new DOMElement('email-address'));
        $address->appendChild(new DOMElement('id', $this->id));
        $address->appendChild(new DOMElement('address', $this->address));
        $address->appendChild(new DOMElement('location', $this->location));
        return $address;
    }
}
?>