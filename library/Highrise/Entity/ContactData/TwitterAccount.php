<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/EntityDataObject.php';

class Highrise_Entity_ContactData_TwitterAccount implements Highrise_EntityDataObject
{
    public $id;
    public $username;
    public $url;
    public $location;
    
    public function getXmlNode()
    {
        $xml = new DOMDocument();
        $node = $xml->appendChild(new DOMElement('twitter-account'));
        $node->appendChild(new DOMElement('id', $this->id));
        $node->appendChild(new DOMElement('username', $this->username));
        $node->appendChild(new DOMElement('url', $this->url));
        $node->appendChild(new DOMElement('location', $this->location));
        return $node;
    }
}
?>