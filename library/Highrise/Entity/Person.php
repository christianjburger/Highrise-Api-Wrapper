<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Object.php';
require_once 'Highrise/Entity/ContactData.php';
require_once 'Highrise/Entity/Tag.php';

class Highrise_Entity_Person extends Highrise_Api_ClientAbstract 
    implements Highrise_Entity_Interface_XmlReadWrite
{
    public $id;
    public $firstName;
    public $lastName;
    public $title;
    public $background = null;
    
    protected $_tags  = array();
    protected $_notes = array();
    
    /**
     * @var Highrise_Entity_ContactData
     */
    protected $_contactData;
    
    /**
     * Static method to create an instance of the Person class from a XML document
     * @param string|Highrise_Response $xml
     * @return Highrise_Entity_Person $person
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
        $this->firstName  = $doc->getElementsByTagName('first-name')->item(0)->nodeValue;
        $this->lastName   = $doc->getElementsByTagName('last-name')->item(0)->nodeValue;
        $this->title      = $doc->getElementsByTagName('title')->item(0)->nodeValue;
        $this->background = $doc->getElementsByTagName('background')->item(0)->nodeValue;
        $this->_contactData->fromXml($doc->getElementsByTagName('contact-data')->item(0));
             
        if ($doc->getElementsByTagName('tags')->item(0))
        {
            foreach ($doc->getElementsByTagName('tags')->item(0)->childNodes as $childNode)
            {
                if ($childNode->nodeName == 'tag')
                {
                    $object = new Highrise_Entity_Tag();
                    $object->fromXml($childNode);
                    $this->_tags[] = $object;
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
        
    public function toXml()
    {
        $doc = new DOMDocument();
        $person = $doc->createElement('person');
        
        if ($this->id) 
        {
            $id = $doc->createElement('id',$this->id);
            $id->setAttribute('type', 'integer');
            $person->appendChild($id);
        }
        
        if ($this->firstName)
        {
            $person->appendChild($doc->createElement('first-name', $this->firstName));
        }
        
        if ($this->lastName)
        {
            $person->appendChild($doc->createElement('last-name' , $this->lastName));
        }
        
        if ($this->title) 
        {
            $person->appendChild($doc->createElement('title'     , $this->title));
        }
        
        if ($this->background)
        {
            $person->appendChild($doc->createElement('background', $this->background));
        }
        
        $person->appendChild($doc->importNode($this->getContactData()->getXmlNode(),true));
        
        $doc->appendChild($person);
        return $doc->saveXML();
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
        $tag = new Highrise_Entity_Tag();
        $tag->id = $id;
        $tag->name = $name;
        $tag->markNew();
        $this->_tags[] = $tag;
    }
    
    public function addNote($body, $id = null)
    {
        $note = new Highrise_Entity_Note();
        $note->isNew       = true;
        $note->body        = $body;
        $note->id          = $id;
        $note->subjectId   = $this->id;
        $note->subjectType = Highrise_Notes::SUBJECT_PEOPLE;
        
        $this->_notes[] = $note;
    }
    
    public function getTags()
    {
        return $this->_tags;
    }
    
    public function save()
    {
        $people = new Highrise_People($this->_client);
        
        if ($this->id)
        {
            $people->update($this);
        } else {
            $people->create($this);
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
                $tags->add(Highrise_Tags::SUBJECT_PEOPLE, $this->id, $tag->name);
            }
            
            if ($tag->isRemoved == true && $tag->id !== null)
            {
                $tags->remove(Highrise_Tags::SUBJECT_PEOPLE, $this->id, $tag->id);
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