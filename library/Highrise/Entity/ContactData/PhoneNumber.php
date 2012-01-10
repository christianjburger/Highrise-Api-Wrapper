<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';

class Highrise_Entity_ContactData_PhoneNumber implements Highrise_Entity_Interface_XmlProtected
{
    public $location = 'Work';
    public $number   = null;
    public $id       = null;
    
    protected $_validLocations = array('Work','Mobile','Fax','Pager','Home','Skype','Other');
    
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
            
            if ($childNode->nodeName == 'number')
            {
                $this->number = $childNode->nodeValue;
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
        $node = $xml->appendChild(new DOMElement('phone-number'));
        if ($this->id !== null) 
        {
            $node->appendChild(new DOMElement('id', $this->id));
        }
        
        if ($this->number !== null)
        {
            $node->appendChild(new DOMElement('number', (string) $this->number));
        }
        
        if ($this->location !== null)
        {
            $node->appendChild(new DOMElement('location', $this->location));
        }
        return $node;
    }
}
?>