<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Entity_ContactData implements Highrise_EntityDataObject
{
    protected $_emailAddresses = array();
    protected $_addresses = array();
    protected $_phoneNumbers = array();
    protected $_customFields = array();
    protected $_instantMessengers = array();
    protected $_twitterAccounts = array();
    protected $_webAddresses = array();
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $contact = $xml->appendChild(new DOMElement('contact-data'));
        
        $contact->appendChild($this->_createXmlElements($xml, 'email-addresses',    $this->_emailAddresses));
        $contact->appendChild($this->_createXmlElements($xml, 'addresses',          $this->_addresses));
        $contact->appendChild($this->_createXmlElements($xml, 'phone-numbers',      $this->_phoneNumbers));
        $contact->appendChild($this->_createXmlElements($xml, 'instant-messengers', $this->_instantMessengers));
        $contact->appendChild($this->_createXmlElements($xml, 'twitter-accounts',   $this->_twitterAccounts));
        $contact->appendChild($this->_createXmlElements($xml, 'web-addresses',      $this->_webAddresses));
        
        return $contact;
    }
    
    protected function _createXmlElements(DOMDocument $xml, $name, $objects)
    {
        $element = $xml->createElement($name);
        foreach ($objects as $object)
        {
            $element->appendChild($xml->importNode($object->getXmlNode(),true));
        }
        return $element;
    }
    
    public function addEmailAddress($address, $id = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_EmailAddress();
        $object->address = $address;
        $object->id = $id;
        $object->location = $location;
        $this->_emailAddresses[] = $object;
        return $this;
    }
    
    public function addPhoneNumber($number, $id = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_PhoneNumber();
        $object->number = $number;
        $object->id = $id;
        $object->location = $location;
        $this->_phoneNumbers[] = $object;
        return $this;
    }
    
    public function addAddress($city, $id = null, $country = null, $state = null, $street = null, $zip = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_Address();
        $object->city = $city;
        $object->id = $id;
        $object->country = $country;
        $object->state = $state;
        $object->street = $street;
        $object->zip = $zip;
        $object->location = $location;
        $this->_addresses[] = $object;
        return $this;
    }
    
    public function addCustomField(Highrise_ContactData_CustomField $field)
    {
        $this->_customFields[] = $field;
        return $this;
    }
    
    public function addInstantMessenger($address, $id = null, $protocol = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_InstantMessenger();
        $object->address = $address;
        $object->id = $id;
        $object->protocol = $protocol;
        $object->location = $location;
        $this->_instantMessengers[] = $object;
        return $this;
    }
    
    public function addTwitterAccount($username, $id = null, $url = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_TwitterAccount();
        $object->username = $username;
        $object->id = $id;
        $object->url = $url;
        $object->location = $location;
        $this->_twitterAccounts[] = $object;
        return $this;
    }
    
    public function addWebAddress($url, $id = null, $location = null)
    {
        $object = new Highrise_Entity_ContactData_WebAddress();
        $object->url = $url;
        $object->id = $id;
        $object->location = $location;
        $this->_webAddresses[] = $object;
        return $this;
    }
}
?>