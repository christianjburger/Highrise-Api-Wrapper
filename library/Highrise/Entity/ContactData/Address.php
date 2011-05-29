<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Entity_ContactData_Address implements Highrise_EntityDataObject
{
    public $id;
    public $city;
    public $country;
    public $state;
    public $street;
    public $zip;
    public $location;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('address'));
        if ($this->id) $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('city', $this->city));
        $node->appendChild(new DOMElement('country', $this->country));
        $node->appendChild(new DOMElement('state', $this->state));
        $node->appendChild(new DOMElement('street', $this->street));
        $node->appendChild(new DOMElement('zip', $this->zip));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>