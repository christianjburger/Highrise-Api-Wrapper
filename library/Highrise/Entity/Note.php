<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Entity_Note extends Highrise_Api_ClientAbstract 
    implements Highrise_Entity_Interface_XmlReadWrite, Highrise_Entity_Interface_Attachable
{
    public $id            = null;
    public $body          = null;
    public $subjectType   = null;
    public $subjectId     = null;
    
    public $isNew         = false;
    public $isDeleted     = false;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function fromXml($data)
    {
        if ($data instanceof Highrise_Api_Response)
        {
            $xml = $data->getData();
        } elseif (is_string($data)) {
            $xml = $data;
        } else {
            throw new Exception('Could not regocnise XML string/object provided');
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        /* @var $node DOMNode */
        
        foreach ($doc->firstChild as $childNode)
        {
            if ($childNode->nodeName == 'id')
            {
                $this->id = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'body')
            {
                $this->body = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == 'subject-id')
            {
                $this->subjectId = $childNode->nodeValue;
            }
            
            if ($childNode->nodeName == "subject-type")
            {
                $this->subjectType = $childNode->nodeValue;
            }
        }
    }
    
    public function toXml()
    {
        $doc = new DOMDocument();
        $note = $doc->createElement('note');
        
        if ($this->id) 
        {
            $id = $doc->createElement('id',$this->id);
            $id->setAttribute('type', 'integer');
            $note->appendChild($id);
        }
        
        if ($this->body)
        {
            $note->appendChild($doc->createElement('body', $this->body));
        }
        
        if ($this->subjectId)
        {
            $note->appendChild($doc->createElement('subject-id', $this->subjectId));
        }
        
        if ($this->subjectType) 
        {
            $note->appendChild($doc->createElement('subject-type', $this->subjectType));
        }
        
        $doc->appendChild($note);
        return $doc->saveXML();
    }
    
    public function markNew()
    {
        $this->isNew = true;
    }
    
    public function markDeleted()
    {
        if (!$this->id) return false;
        $this->isRemoved = true;
    }
}
?>