<?php
/** 
 * @author cjb
 * 
 * 
 */
class Highrise_Tags extends Highrise_Client_ProxyAbstract
{
    const SUBJECT_PEOPLE    = 'people';
    const SUBJECT_COMPANIES = 'companies';
    const SUBJECT_DEALS     = 'deals';
    const SUBJECT_KASES     = 'kases';
    
    /**
     * Return all tags used in the account.
     * @link GET /tags.xml
     * @return array $collection
     */
    public function listAll()
    {
        $request = new Highrise_Client_Request();
        $request->endpoint = '/tags.xml';
        $request->method   = Highrise_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        
        $xml = simplexml_load_string($response->getData());
        $result = $xml->xpath('/tags/tag');
        $collection = array();
        if (!$result) return $collection;
        foreach ($result as $xmlEntry)
        {
            $tag = new Highrise_Entity_Tag();
            $tag->fromXml($xmlEntry->saveXml());
            $collection[] = $tag;
        }
        return $collection;
    }
    
    /**
     * Return the tags on a person, company, case, or deal.
     * @link GET /#{subject_type}/#{subject_id}/tags.xml
     * 
     * @return array $collection
     */
    public function listSubject($subjectType,$subjectId)
    {
        $request = new Highrise_Client_Request();
        $request->endpoint = "/{$subjectType}/{$subjectId}/tags.xml";
        $request->method   = Highrise_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        
        $xml = simplexml_load_string($response->getData());
        $result = $xml->xpath('/tags/tag');
        $collection = array();
        if (!$result) return $collection;
        foreach ($result as $xmlEntry)
        {
            $tag = new Highrise_Entity_Tag();
            $tag->fromXml($xmlEntry->saveXml());
            $collection[] = $tag;
        }
        return $collection;
    }
    
    /**
     * 
     * Return all parties (people and companies) associated with a given tag. 
     * Note, though, that this will not include deals and cases for that tag.
     * @link GET /tags/#{id}.xml
     * @param integer $tagId
     * @return array $collection collection of Highrise_Entity_Party objects
     */
    public function listParties($tagId)
    {
        $request = new Highrise_Client_Request();
        $request->endpoint = "/tags/{$tagId}.xml";
        $request->method   = Highrise_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        
        $xml = simplexml_load_string($response->getData());
        $result = $xml->xpath('/parties/party');
        $collection = array();
        if (!$result) return $collection;
        foreach ($result as $xmlEntry)
        {
            $tag = new Highrise_Entity_Party();
            $tag->fromXml($xmlEntry->saveXml());
            $collection[] = $tag;
        }
        return $collection;
    }
    
    /**
     * Adds a tag to a person, company, deal, or case.
     * @link POST /#{subject_type}/#{subject_id}/tags.xml
     * @param string $subjectType
     * @param integer $subjectId
     * @param string $tagName
     * 
     */
    public function add($subjectType,$subjectId,$tagName)
    {
        $request = new Highrise_Client_Request();
        $request->endpoint = "/{$subjectType}/{$subjectId}/tags.xml";
        $request->method   = Highrise_Client::METHOD_POST;
        $request->expected = 201;
        $request->data     = "<name>$tagName</name>";
 
        $response = $this->_client->request($request);
        print_r($response->getData());
    }
    
    /**
     * Removes a tag from a person, company, deal, or case.
     * @link DELETE /#{subject_type}/#{subject_id}/tags/#{id}.xml
     * @param string $subjectType
     * @param integer $subjectId
     * @param intger $tagId
     */
    public function remove($subjectType,$subjectId,$tagId)
    {
        $request = new Highrise_Client_Request();
        $request->endpoint = "/{$subjectType}/{$subjectId}/tags/{$tagId}.xml";
        $request->method   = Highrise_Client::METHOD_DELETE;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
    }
}
?>