<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';

class Highrise_Entity_Tag implements Highrise_Entity_Interface_XmlProtected
{
    public $id        = null;
    public $name      = null;
    public $isNew     = false;
    public $isRemoved = false;
    
    public function fromXml($xml)
    {
        $doc = new DOMDocument();
        if ($xml instanceof DOMNode)
        {
            $doc->appendChild($doc->importNode($xml,true));
        } elseif (is_string($xml)) {
            $doc->loadXML($xml);
        } else {
            throw new Exception('Not a valid XML string/object');
        }

        $this->id    = $doc->getElementsByTagName('id')->item(0)->nodeValue;
        $this->name  = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    }
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('tag'));
        if ($this->id !== null)
        {
            $node->appendChild(new DOMElement('id', $this->id));
        }
        
        if ($this->name !== null)
        {
            $node->appendChild(new DOMElement('name', $this->name));
        }
        return $node;
    }
    
    public function markNew()
    {
        $this->isNew = true;
    }
    
    public function markRemoved()
    {
        if (!$this->id) return false;
        $this->isRemoved = true;
    }
    
    
}
?>