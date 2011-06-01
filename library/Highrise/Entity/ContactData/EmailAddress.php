<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/DataObject.php';

class Highrise_Entity_ContactData_EmailAddress implements Highrise_Entity_Interface_XmlProtected
{
    public $location;
    public $address;
    public $id;
    
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
            
            if ($childNode->nodeName == 'address')
            {
                $this->address = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'location')
            {
                $this->location = $childNode->nodeValue;
            }
        }
    }
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $address = $xml->appendChild(new DOMElement('email-address'));
        if ($this->id !== null)
        {
            $address->appendChild(new DOMElement('id', $this->id));
        }
        
        if ($this->address !== null)
        {
            $address->appendChild(new DOMElement('address', $this->address));
        }
        
        if ($this->location !== null)
        {
            $address->appendChild(new DOMElement('location', $this->location));
        }
        return $address;
    }
    
}
?>