<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Api/ClientAbstract.php';
require_once 'Highrise/Entity/Interface/XmlReadWrite.php';

class Highrise_Entity_Deal extends Highrise_Api_ClientAbstract 
    implements Highrise_Entity_Interface_XmlReadWrite
{
    public $id;
    public $name;
    public $price;
    public $priceType;
    public $currency;
    public $ownerId;
    public $duration;
    public $partyId;
    public $responsiblePartyId;
    public $parties;
    public $background = null;
    public $status;
    public $category;
    public $visibleTo;
    public $groupId;
    
    protected $_parties  = array();
    protected $_notes    = array();
        
    const VISIBLE_EVERYONE = 'Everyone';
    const VISIBLE_OWNER    = 'Owner';
    const VISIBLE_NAMEDGROUP = 'NamedGroup';
    
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
        $this->name       = $doc->getElementsByTagName('name')->item(0)->nodeValue;
        $this->price      = $doc->getElementsByTagName('price')->item(0)->nodeValue;
        $this->priceType  = $doc->getElementsByTagName('price-type')->item(0)->nodeValue;
        $this->currency   = $doc->getElementsByTagName('currency')->item(0)->nodeValue;
        $this->duration   = $doc->getElementsByTagName('duration')->item(0)->nodeValue;
        $this->ownerId    = $doc->getElementsByTagName('owner-id')->item(0)->nodeValue;
        $this->partyId    = $doc->getElementsByTagName('party-id')->item(0)->nodeValue;
        $this->responsiblePartyId    = $doc->getElementsByTagName('responsible-party-id')->item(0)->nodeValue;
        $this->price      = $doc->getElementsByTagName('price')->item(0)->nodeValue;
        $this->priceType  = $doc->getElementsByTagName('price-type')->item(0)->nodeValue;
        $this->background = $doc->getElementsByTagName('background')->item(0)->nodeValue;
        $this->status     = $doc->getElementsByTagName('status')->item(0)->nodeValue;
        //$this->category   = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $this->visibleTo  = $doc->getElementsByTagName('visible-to')->item(0)->nodeValue;
        $this->groupId    = $doc->getElementsByTagName('group-id')->item(0)->nodeValue;
       
        /*
        if ($doc->getElementsByTagName('parties')->item(0))
        {
            foreach ($doc->getElementsByTagName('parties')->item(0)->childNodes as $childNode)
            {
                if ($childNode->nodeName == 'party')
                {
                    $object = new Highrise_Entity_Party();
                    $object->fromXml($childNode);
                    $this->_parties[$object->name] = $object;
                }
            }
        }
        */
    }
    
    public function __construct($client = null)
    {
        parent::__construct($client);
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function toXml()
    {
        $doc = new DOMDocument();
        $deal = $doc->createElement('deal');
        
        if ($this->id) 
        {
            $id = $doc->createElement('id',$this->id);
            $id->setAttribute('type', 'integer');
            $deal->appendChild($id);
        }
        
        if ($this->name)
        {
            $deal->appendChild($doc->createElement('name', $this->name));
        }
        
        if ($this->price)
        {
            $deal->appendChild($doc->createElement('price' , $this->price));
        }
        
        if ($this->priceType)
        {
            $deal->appendChild($doc->createElement('price-type' , $this->priceType));
        }
        
        if ($this->background)
        {
            $deal->appendChild($doc->createElement('background' , $this->background));
        }
        
        if ($this->category)
        {
            $deal->appendChild($doc->createElement('category' , $this->category));
        }
        
        if ($this->currency)
        {
            $deal->appendChild($doc->createElement('currency' , $this->currency));
        }
        
        if ($this->duration)
        {
            $deal->appendChild($doc->createElement('duration' , $this->duration));
        }
        
        if ($this->groupId)
        {
            $deal->appendChild($doc->createElement('group-id' , $this->groupId));
        }
        
        if ($this->ownerId)
        {
            $deal->appendChild($doc->createElement('owner-id' , $this->ownerId));
        }
        
        if ($this->partyId)
        {
            $deal->appendChild($doc->createElement('party-id' , $this->partyId));
        }
        
        if ($this->responsiblePartyId)
        {
            $deal->appendChild($doc->createElement('responsible-party-id' , $this->responsiblePartyId));
        }
        
        if ($this->status)
        {
            $deal->appendChild($doc->createElement('status' , $this->status));
        }
        
        if ($this->visibleTo)
        {
            $deal->appendChild($doc->createElement('visible-to' , $this->visibleTo));
        }
        
        /*
        if ($this->parties)
        {
            $deal->appendChild($doc->createElement('owner-id' , $this->ownerId));
        }
        */
       
        $doc->appendChild($deal);
        return $doc->saveXML();
    }
    
    public function addNote($body, $id = null)
    {
        if (empty($body)) throw new Exception('Note body cannot be empty');
        $note = new Highrise_Entity_Note();
        $note->isNew       = true;
        $note->body        = $body;
        $note->id          = $id;
        $note->subjectId   = $this->id;
        $note->subjectType = Highrise_Notes::SUBJECT_DEALS;
        
        $this->_notes[] = $note;
    }
    
    public function save()
    {
        $deal = new Highrise_Deals($this->_client);
        
        if ($this->id)
        {
            $deal->update($this);
        } else {
            $deal->create($this);
        }
        
        $this->saveNotes();
        
        return $this->getId();
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