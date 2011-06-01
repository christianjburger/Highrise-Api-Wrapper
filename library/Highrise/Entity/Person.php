<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Object.php';
require_once 'Highrise/Entity/ContactData.php';
require_once 'Highrise/Entity/Tag.php';

class Highrise_Entity_Person extends Highrise_Client_ProxyAbstract implements Highrise_Entity_Object
{
    public $id;
    public $firstName;
    public $lastName;
    public $title;
    public $background = null;
    
    protected $_tags = array();
    
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
        if ($data instanceof Highrise_Client_Response)
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
    
    public function __construct()
    {
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
    
    public function getTags()
    {
        return $this->_tags;
    }
    
    public function save($account, $token, $debug = false)
    {
        $people = new Highrise_People($account, $token, $debug);
        $this->_client = $people->getClient();
        if ($this->id)
        {
            $people->update($this);
        } else {
            $people->create($this);
        }
        
        $this->saveTags($account, $token, $debug);
        $this->saveNotes($account, $token, $debug);
    }
    
    public function saveTags($account, $token, $debug = false)
    {
        $tags = new Highrise_Tags($account, $token, $debug);
        $this->_client = $tags->getClient();
        foreach ($this->_tags as $tag)
        {
            if ($tag->isNew === true)
            {
                $tags->add(Highrise_Tags::SUBJECT_PEOPLE, $this->id, $tag->name);
            }
            
            if ($tag->isRemoved === true && $tag->id)
            {
                $tags->remove(Highrise_Tags::SUBJECT_PEOPLE, $this->id, $tag->id);
            }
        }
    }
    
    public function saveNotes($account, $token, $debug = false)
    {
        
    }
    
}
?>