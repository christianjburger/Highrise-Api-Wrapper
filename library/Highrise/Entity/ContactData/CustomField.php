<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/DataObject.php';

class Highrise_Entity_ContactData_CustomField implements Highrise_Entity_DataObject
{
    public $id;
    public $subjectFieldId;
    public $value;
    public $label;
    
    public function fromXml($node)
    {
        if (!$node instanceof DOMNode)
        {
            throw new Exception('Not a valid XML object');
        }
        /* @var $node DOMNode */
        
        $this->label = $node->nodeName;
        
        foreach ($node->childNodes as $childNode)
        {
            if ($childNode->nodeName == 'id')
            {
                $this->id = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'value')
            {
                $this->value = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'subject-field-id')
            {
                $this->subjectFieldId = $childNode->nodeValue;
            }
        }
    }
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement($this->label));
        if ($this->id !== null)
        {
            $node->appendChild(new DOMElement('id', $this->id));
        }
        
        if ($this->value !== null)
        {
            $node->appendChild(new DOMElement('value', $this->value));
        }
        
        if ($this->subjectFieldId !== null)
        {
            $node->appendChild(new DOMElement('subject-field-id', $this->subjectFieldId));
        }
        return $node;
    }
}
?>