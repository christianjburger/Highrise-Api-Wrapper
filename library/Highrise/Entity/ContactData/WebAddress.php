<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';

class Highrise_Entity_ContactData_WebAddress implements Highrise_Entity_Interface_XmlProtected
{
    public $id;
    public $url;
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
            
            if ($childNode->nodeName == 'url')
            {
                $this->url = $childNode->nodeValue;
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
        $node = $xml->appendChild(new DOMElement('web-address'));
        if ($this->id !== null)
        {
            $node->appendChild(new DOMElement('id', $this->id));
        }
        
        if ($this->url !== null)
        {
            $node->appendChild(new DOMElement('url', $this->url));
        }
        
        if ($this->location !== null) 
        {
            $node->appendChild(new DOMElement('location', $this->location));
        }
        return $node;
    }
}
?>