<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Api/ClientAbstract.php';
require_once 'Highrise/Entity/Interface/XmlReadWrite.php';
require_once 'Highrise/Entity/Tag.php';
require_once 'Highrise/Entity/Note.php';

abstract class Highrise_Entity_Party extends Highrise_Api_ClientAbstract 
    implements Highrise_Entity_Interface_XmlReadWrite
{
    public $id;
    public $background = null;
    public $visibleTo;
    public $groupId;
    
    const VISIBLE_EVERYONE   = 'Everyone';
    const VISIBLE_OWNER      = 'Owner';
    const VISIBLE_NAMEDGROUP = 'NamedGroup';
    
    protected $_tags  = array();
    protected $_notes = array();
    
    /**
     * @var Highrise_Entity_ContactData
     */
    protected $_contactData;
    
    protected $_xmlRoot;
    protected $_subjectType;
    
    /**
     * @return Highrise_Api_ClientAbstract $api
     */
    abstract protected function _getApi(); 
    
    /**
     * Static method to create an instance of the Person class from a XML document
     * @param string|Highrise_Response $xml
     */
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

        $this->id         = $doc->getElementsByTagName('id')->item(0)->nodeValue;
        $this->background = $doc->getElementsByTagName('background')->item(0)->nodeValue;
        $this->visibleTo  = $doc->getElementsByTagName('visible-to')->item(0)->nodeValue;
        $this->groupId    = $doc->getElementsByTagName('group-id')->item(0)->nodeValue;
        $this->_contactData->fromXml($doc->getElementsByTagName('contact-data')->item(0));
             
        if ($doc->getElementsByTagName('tags')->item(0))
        {
            foreach ($doc->getElementsByTagName('tags')->item(0)->childNodes as $childNode)
            {
                if ($childNode->nodeName == 'tag')
                {
                    $object = new Highrise_Entity_Tag();
                    $object->fromXml($childNode);
                    $this->_tags[$object->name] = $object;
                }
            }
        }
    }
    
    public function __construct($client = null)
    {
        parent::__construct($client);
        $this->_contactData = new Highrise_Entity_ContactData();
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function copyToXml(DOMDocument $doc, DOMElement $xml)
    {
        if ($this->id) 
        {
            $id = $doc->createElement('id',$this->id);
            $id->setAttribute('type', 'integer');
            $xml->appendChild($id);
        }
        
        if ($this->background)
        {
            $xml->appendChild($doc->createElement('background', $this->background));
        }
        
        if ($this->visibleTo)
        {
            $xml->appendChild($doc->createElement('visible-to', $this->visibleTo));
        }
        
        if ($this->groupId)
        {
            $xml->appendChild($doc->createElement('group-id', $this->groupId));
        }
        
        $xml->appendChild($doc->importNode($this->getContactData()->getXmlNode(),true));
    }
    
    /**
     * @return Highrise_Entity_ContactData
     */
    public function getContactData()
    {
        return $this->_contactData;
    }

    public function addTag($name, $id = null)
    {
        if (isset($this->_tags[$name])) return;
        if (empty($name)) throw new Exception('Tag label cannot be empty');
        $tag = new Highrise_Entity_Tag();
        $tag->id = $id;
        $tag->name = $name;
        $tag->markNew();
        
        $this->_tags[$name] = $tag;
    }
    
    public function addNote($body, $id = null)
    {
        if (empty($body)) throw new Exception('Note body cannot be empty');
        $note = new Highrise_Entity_Note();
        $note->isNew       = true;
        $note->body        = $body;
        $note->id          = $id;
        $note->subjectId   = $this->id;
        $note->subjectType = $this->_subjectType;
        
        $this->_notes[] = $note;
    }
    
    public function getTags()
    {
        return $this->_tags;
    }
    
    public function getNotes()
    {
        return $this->_notes;
    }
    
    public function save()
    {
        $api = $this->_getApi();
        
        if ($this->id)
        {
            $api->update($this);
        } else {
            $api->create($this);
        }
        
        $this->saveTags();
        $this->saveNotes();
        
        return $this->getId();
    }
    
    public function saveTags()
    {
        $tags = new Highrise_Tags($this->_client);
        
        foreach ($this->_tags as $tag)
        {
            if ($tag->isNew == true)
            {
                $tags->add($this->_subjectType, $this->id, $tag->name);
            }
            
            if ($tag->isRemoved == true && $tag->id !== null)
            {
                $tags->remove($this->_subjectType, $this->id, $tag->id);
            }
        }
    }
    
    public function saveNotes()
    {
        $notes = new Highrise_Notes($this->_client);
        
        foreach ($this->_notes as $note)
        {
            if ($note->isNew == true)
            {
                if ($note->subjectId === null) $note->subjectId = $this->id;
                $notes->create($note);
            }
            
            if ($note->isRemoved == true && $note->id !== null)
            {
                $notes->destroy($note->id);
            }
        }
    }
    
}
?>