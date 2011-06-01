<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/DataObject.php';

class Highrise_Entity_ContactData_InstantMessenger implements Highrise_Entity_Interface_XmlProtected
{
    public $id;
    public $address;
    public $protocol;
    public $location;
    
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
            
            if ($childNode->nodeName == 'protocol')
            {
                $this->protocol = $childNode->nodeValue;
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
        $node = $xml->appendChild(new DOMElement('instant-messenger'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('address', $this->address));
        $node->appendChild(new DOMElement('protocol', $this->protocol));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>