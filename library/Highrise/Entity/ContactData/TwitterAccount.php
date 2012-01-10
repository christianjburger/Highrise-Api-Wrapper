<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';

class Highrise_Entity_ContactData_TwitterAccount implements Highrise_Entity_Interface_XmlProtected
{
    public $id;
    public $username;
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
            
            if ($childNode->nodeName == 'username')
            {
                $this->username = $childNode->nodeValue;
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
        $node = $xml->appendChild(new DOMElement('twitter-account'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('username', $this->username));
        $node->appendChild(new DOMElement('url', $this->url));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>