<?php
require_once ('Highrise/Api/ClientAbstract.php');
require_once ('Highrise/Api/Request.php');
require_once ('Highrise/Api/Client.php');
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Notes extends Highrise_Api_ClientAbstract
{
    const SUBJECT_PEOPLE    = 'people';
    const SUBJECT_COMPANIES = 'companies';
    const SUBJECT_DEALS     = 'deals';
    const SUBJECT_KASES     = 'kases';
    
    /**
     * Returns a single note. Attachments are included, but comments are kept 
     * separate at /notes/#{id}/comments.xml.
     * @link GET /notes/#{id}.xml
     * @param integer $id
     */
    public function show($id)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/notes/{$id}.xml";
        $request->method   = Highrise_Api_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        $note = new Highrise_Entity_Note();
        $note->fromXml($response->getData());
        return $note;
    }
    
    /**
     * Returns a collection of notes that are visible to the authenticated user 
     * and related to a specific person, company, case or deal. The list is paginated 
     * using offsets. If 25 elements are returned (the page limit), use ?n=25 to fetch 
     * the next 25 and so on.
     * @link GET /#{ people || companies || kases || deals }/#{subject-id}/notes.xml
     */
    public function listBySubject($subjectType,$subjectId)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/{$subjectType}/{$subjectId}/notes.xml";
        $request->method   = Highrise_Api_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        
        $xml = simplexml_load_string($response->getData());
        $result = $xml->xpath('/notes/note');
        $collection = array();
        if (!$result) return $collection;
        foreach ($result as $xmlEntry)
        {
            $note = new Highrise_Entity_Note();
            $note->fromXml($xmlEntry->saveXml());
            $collection[] = $note;
        }
        return $collection;
    }
    
    /**
     * Creates a new note with the currently authenticated user as the author. The XML 
     * for the new note is returned on a successful request with the timestamps recorded 
     * and ids for the contact data associated.
     * 
     * The subject of the note (who it belongs to) can either be set through the url or 
     * through the subject-type and subject-id tags. Using /companies/5/notes.xml as the 
     * target for the POST is the same as using /notes.xml with subject-type ÒPartyÓ and 
     * subject-id Ò5Ó.
     * 
     * By default, a new note is assumed to be visible to Everyone. You can also chose to 
     * make the note only visible to the creator using ÒOwnerÓ as the value for the visible-to 
     * tag. Or ÒNamedGroupÓ and pass in a group-id tag too.
     * 
     * Note: Adding attachments to a note is not yet supported.
     * 
     * As always, the URL for the newly-created note is passed back in the Location header.
	 * @link /notes.xml (or like: /people/#{person-id}/notes.xml)
     */
    public function create(Highrise_Entity_Note $note)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/{$note->subjectType}/{$note->subjectId}/notes.xml";
        $request->method   = Highrise_Api_Client::METHOD_POST;
        $request->expected = 201;
        $request->data     = '<note><body>' . $note->body . '</body></note>';
 
        $response = $this->_client->request($request);
    }
    
    /**
     * Updates an existing note with new details from the submitted XML.
     * @link PUT /notes/#{id}.xml
     */
    public function update(Highrise_Entity_Note $note)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/notes/{$note->id}.xml";
        $request->method   = Highrise_Api_Client::METHOD_PUT;
        $request->expected = 200;
        $request->data     = $note->toXml();
 
        $response = $this->_client->request($request);
    }
    
    /**
     * Destroys the note at the referenced URL.
     * @link DELETE /notes/#{id}.xml
     */
    public function destroy($id)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/notes/{$id}.xml";
        $request->method   = Highrise_Api_Client::METHOD_DELETE;
        $request->expected = 200;
 
        $response = $this->_client->request($request);
    }
}
?>