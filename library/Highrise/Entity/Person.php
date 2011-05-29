<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/EntityObject.php';
require_once 'Highrise/Entity/ContactData.php';

class Highrise_Entity_Person implements Highrise_EntityObject
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
    
    public static function createFromXml($xml)
    {
        if ($xml instanceof Highrise_Response)
        {
            $xml = $xml->getData();
        }
        $doc = new DOMDocument();
        $doc->loadXML($xml);
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
        $xml = new DOMDocument();
        $person = $xml->createElement('person');
        
        $id = $xml->createElement('id',$this->id);
        $id->setAttribute('type', 'integer');
        
        $firstName   = $xml->createElement('first-name', $this->firstName);
        $lastName    = $xml->createElement('last-name' , $this->lastName);
        $title       = $xml->createElement('title'     , $this->title);
        $background  = $xml->createElement('background', $this->background);
        $tags        = $xml->createElement('tags');
        
        if ($this->id) $person->appendChild($id);
        $person->appendChild($firstName);
        $person->appendChild($lastName);
        $person->appendChild($title);
        $person->appendChild($background);
        $person->appendChild($xml->importNode($this->contactData()->getXmlNode(),true));
        
        foreach ($this->_tags as $tag)
        {
            $tags->appendChild($xml->importNode($tag->getXmlNode(),true));
        }
        if (count($this->_tags) > 0) $person->appendChild($tags);
        
        $xml->appendChild($person);
        return $xml->saveXML();
    }
    
    public function fromXml($string)
    {
        $xml = simplexml_load_string($string);
        return $xml->asXml();
    }

    /**
     * @return Highrise_Entity_ContactData
     */
    public function contactData()
    {
        return $this->_contactData;
    }
    
    public function addTag($id,$name)
    {
        $tag = new Highrise_Entity_Tag();
        $tag->id = $id;
        $tag->name = $name;
        $this->_tags[] = $tag;
    }
}
?>