<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Entity_Tag implements Highrise_EntityDataObject
{
    public $id;
    public $name;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('tag'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('name', $this->name));
        return $node;
    }
}
?>