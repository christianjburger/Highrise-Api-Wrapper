<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Entity/Interface/XmlProtected.php';
require_once 'Highrise/Entity/ContactData/EmailAddress.php';
require_once 'Highrise/Entity/ContactData/Address.php';
require_once 'Highrise/Entity/ContactData/PhoneNumber.php';
require_once 'Highrise/Entity/ContactData/InstantMessenger.php';
require_once 'Highrise/Entity/ContactData/TwitterAccount.php';
require_once 'Highrise/Entity/ContactData/WebAddress.php';
require_once 'Highrise/Entity/ContactData/CustomField.php';

class Highrise_Entity_ContactData implements Highrise_Entity_Interface_XmlProtected
{
    protected $_emailAddresses    = array();
    protected $_addresses         = array();
    protected $_phoneNumbers      = array();
    protected $_customFields      = array();
    protected $_instantMessengers = array();
    protected $_twitterAccounts   = array();
    protected $_webAddresses      = array();
    
    /**
     * Static method to create an instance of the ContactData class from xml document
     * @param string $xml
     * @return Highrise_Entity_Person $person
     */
    public function fromXml($node)
    {
        /* @var $node DOMNode */
        if (!$node instanceof DOMNode)
        {
            throw new Exception('Not a valid XML object');
        }
        /*
        $doc = new DOMDocument();
        if ($xml instanceof DOMNode)
        {
            $doc->appendChild($doc->importNode($xml,true));
        } elseif (is_string($xml)) {
            $doc->loadXML($xml);
        } else {
            throw new Exception('Not a valid XML string/object');
        }
        */
        foreach ($node->childNodes as $childNode)
        {
            if (!$childNode instanceof DOMElement) continue;
            switch ($childNode->nodeName)
            {
                case 'email-addresses':
                    $this->_emailAddresses = $this->_createFromXml(
                        $childNode,
                        'email-address',
                        'Highrise_Entity_ContactData_EmailAddress'
                    );
                    break;
                case 'phone-numbers':
                    $this->_phoneNumbers = $this->_createFromXml(
                        $childNode,
                        'phone-number',
                        'Highrise_Entity_ContactData_PhoneNumber'
                    ); 
                    break;
                case 'addresses':
                    $this->_addresses = $this->_createFromXml(
                        $childNode,
                        'address',
                        'Highrise_Entity_ContactData_Address'
                    ); 
                    break;
                case 'instant-messengers':
                    $this->_instantMessengers = $this->_createFromXml(
                        $childNode,
                        'instant-messenger',
                        'Highrise_Entity_ContactData_InstanceMessenger'
                    ); 
                    break;
                case 'twitter-accounts':
                    $this->_twitterAccounts = $this->_createFromXml(
                        $childNode,
                        'twitter-account',
                        'Highrise_Entity_ContactData_TwitterAccount'
                    );
                    break;
                case 'web-addresses':
                    $this->_webAddresses = $this->_createFromXml(
                        $childNode,
                        'web-address',
                        'Highrise_Entity_ContactData_WebAddress'
                    ); 
                    break;
                default:
                    $object = new Highrise_Entity_ContactData_CustomField();
                    $object->fromXml($childNode);
                    $this->_customFields[] = $object;
                    break;
            }
        }

    }
    
    protected function _createFromXml($node, $name, $class)
    {
        $collection = array();
        
        foreach ($node->childNodes as $childNode)
        {
            if (!$childNode instanceof DOMElement) continue;
            
            $object = new $class();
            $object->fromXml($childNode);
            $collection[] = $object;
        }
        return $collection;
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
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $contact = $xml->appendChild(new DOMElement('contact-data'));
        
        if (count($this->_emailAddresses) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'email-addresses',    $this->_emailAddresses));
        }
        
        if (count($this->_addresses) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'addresses',          $this->_addresses));
        }
        
        if (count($this->_phoneNumbers) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'phone-numbers',      $this->_phoneNumbers));
        }
        
        if (count($this->_instantMessengers) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'instant-messengers', $this->_instantMessengers));
        }
        
        if (count($this->_twitterAccounts) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'twitter-accounts',   $this->_twitterAccounts));
        }
        
        if (count($this->_webAddresses) > 0)
        {
            $contact->appendChild($this->_createXmlElements($xml, 'web-addresses',      $this->_webAddresses));
        }
        
        if (count($this->_customFields) > 0)
        {
            foreach ($this->_customFields as $field)
            {
                $contact->appendChild($xml->importNode($field->getXmlNode(),true));
            }
        }
        
        return $contact;
    }
    
    public function getEmailAddresses()
    {
        return $this->_emailAddresses;
    }

    public function getPhoneNumbers()
    {
        return $this->_phoneNumbers;
    }
    
    public function getAddresses()
    {
        return $this->_addresses;
    }
    
    public function getInstantMessengers()
    {
        return $this->_instantMessengers;
    }
    
    public function getTwitterAccounts()
    {
        return $this->_twitterAccounts;
    }
    
    public function getWebAddresses()
    {
        return $this->_webAddresses;
    }
    
    public function getCustomFields()
    {
        return $this->_customFields;
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
    
    public function addPhoneNumber($number, $id = null, $location = 'Work')
    {
        $object = new Highrise_Entity_ContactData_PhoneNumber();
        $object->number = $number;
        $object->id = $id;
        $object->location = $location;
        $object->validate();
        $this->_phoneNumbers[] = $object;
        return $this;
    }

    public function addAddress($city, $id = null, $country = null, $state = null, $street = null, $zip = null, $location = 'Work')
    {
        $object = new Highrise_Entity_ContactData_Address();
        $object->city = $city;
        $object->id = $id;
        $object->country = $country;
        $object->state = $state;
        $object->street = $street;
        $object->zip = $zip;
        $object->location = $location;
        $object->validate();
        $this->_addresses[] = $object;
        return $this;
    }
    
    public function addCustomField($label, $value, $id = null, $subjectFieldId = null)
    {
        $object = new Highrise_Entity_ContactData_CustomField();
        
        $object->label          = $label;
        $object->value          = $value;
        $object->id             = $id;
        $object->subjectFieldId = $subjectFieldId;
        
        $this->_customFields[] = $object;
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