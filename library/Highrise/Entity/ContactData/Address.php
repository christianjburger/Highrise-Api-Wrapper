<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';

class Highrise_Entity_ContactData_Address implements Highrise_Entity_Interface_XmlProtected
{
    public $id;
    public $city;
    public $country;
    public $state;
    public $street;
    public $zip;
    public $location = 'Work';
    
    const LOCATION_WORK = 'Work';
    const LOCATION_HOME = 'Home';
    const LOCATION_OTHER = 'Other';
    
    protected $_validLocations = array('Work','Home','Other');
    
    public function fromXml($node)
    {
        if (!$node instanceof DOMNode)
        {
            throw new Exception('Not a valid XML object');
        }
        /* @var $node DOMNode */
        
        foreach ($node->childNodes as $childNode)
        {
            if ($childNode->nodeName == 'id')
            {
                $this->id = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'city')
            {
                $this->city = $childNode->nodeValue;
            }

            if ($childNode->nodeName == 'country')
            {
                $this->country = $childNode->nodeValue;
            }

            if ($childNode->nodeName == 'state')
            {
                $this->state = $childNode->nodeValue;
            }

            if ($childNode->nodeName == 'street')
            {
                $this->street = $childNode->nodeValue;
            }

            if ($childNode->nodeName == 'zip')
            {
                $this->zip = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'location')
            {
                $this->location = $childNode->nodeValue;
            }
        }
        
        $this->validate();
    }
    
    
    public function validate()
    {
        if ($this->location === null)
        {
            throw new Exception('Location may not be empty');
        }
        
        if (!in_array($this->location,$this->_validLocations))
        {
            throw new Exception('"' . $this->location . '" is not a valid location');
        }
    }
    
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