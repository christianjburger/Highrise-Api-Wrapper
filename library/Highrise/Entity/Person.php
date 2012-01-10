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

class Highrise_Entity_Person extends Highrise_Entity_Party 
    implements Highrise_Entity_Interface_XmlReadWrite
{
    public $firstName;
    public $lastName;
    public $title;
    
    protected $_subjectType = 'people';
    protected $_xmlRoot     = 'person';
    
    protected function _getApi()
    {
        return new Highrise_People($this->_client);
    }
    
    /**
     * Static method to create an instance of the Person class from a XML document
     * @param string|Highrise_Response $xml
     * @return Highrise_Entity_Person $person
     */
    public function fromXml($data)
    {
        parent::fromXml($data);
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

        $this->firstName  = $doc->getElementsByTagName('first-name')->item(0)->nodeValue;
        $this->lastName   = $doc->getElementsByTagName('last-name')->item(0)->nodeValue;
        $this->title      = $doc->getElementsByTagName('title')->item(0)->nodeValue;
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function toXml()
    {
        $doc = new DOMDocument();
        $person = $doc->createElement('person');
        
        parent::copyToXml($doc, $person);
        
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
        
        $doc->appendChild($person);
        return $doc->saveXML();
    }
}
?>