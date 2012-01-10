<?php
/** 
 * @author cjb
 * 
 * 
 */

class Highrise_Deals extends Highrise_Api_ClientAbstract
{

    
    /**
     * Returns a single deal.
     * 
     * @link GET /deals/#{id}.xml
     * @param int $id
     * @return Highrise_Entity_Deal $deal
     */
    public function show($id)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = "/deals/{$id}.xml";
        $request->method = Highrise_Api_Client::METHOD_GET;
        $request->expected = 200;
        $response = $this->_client->request($request);
        
        $object = new Highrise_Entity_Deal();
        $object->fromXml($response->getData());
        return $object;
    }
    
    /**
     * Returns a list of deals that are visible to the authenticated user. 
     * Returns pending, won, and lost deals. Use the ?status=won query parameter 
     * to filter the list to a single status. The list is paginated using offsets. 
     * If 500 deals are returned (the page limit), use ?n=500 to check for the next page.
     * 
     * @link GET /deals.xml
     * @param int $offset
     * @param string $status pending|won|lost
     */
    public function listAll($offset = null, $status = null)
    {
        $collection = array();
        $params     = array();
        
        if ($offset) $params['n'] = $offset;
        if ($status) $params['status'] = $status;
        
        $append = (count($params) > 0) ? '?' . $this->_paramsToString($params) : null;
        
        $request = new Highrise_Api_Request();
        $request->endpoint = '/deals.xml' . $append;
        $request->method = Highrise_Api_Client::METHOD_GET;
        $request->expected = 200;
        
        $response = $this->_client->request($request);
        
        $xml = simplexml_load_string($response->getData());
        $result = $xml->xpath('/deals/deal');
        $collection = array();
        if (!$result) return $collection;
        foreach ($result as $xmlEntry)
        {
            $object = new Highrise_Entity_Deal();
            $object->fromXml($xmlEntry->saveXml());
            $collection[] = $object;
        }
        return $collection;
    }
  
    
    /**
     * Creates a new deal with the currently authenticated user as the author.
     * 
     * By default, a new deal is assumed to be visible to Everyone. You can also 
     * choose to make the deal only visible to the creator using “Owner” as the value 
     * for the visible-to tag. Or “NamedGroup” and pass in a group-id tag too.
     * 
     * If the account doesn’t allow for more deals to be created, a “507 Insufficient Storage” 
     * response will be returned.
     * 
     * See the Show API call for a description of what the different fields mean.
     * 
     * @link POST /deals.xml
     * @param Highrise_Entity_Deal $deal
     * @return integer $id
     */
    public function create(Highrise_Entity_Deal $deal)
    {
        if (!$this->_client) throw new Exception('Api client not available');

        $request = new Highrise_Api_Request();
        $request->endpoint = '/deals.xml';
        $request->method = Highrise_Api_Client::METHOD_POST;
        $request->expected = 201;
        $request->data = $deal->toXml();
        $response = $this->_client->request($request);
        $deal->fromXml($response->getData());
        return $deal->getId();
    }
    
    /**
     * Updates information about the given deal. Note that changes to a deal’s 
     * status should be done via the Status Update API call, and not via this call. 
     * Attempts to update the status via this API call will silently ignore the 
     * status field.
     * 
     * @link PUT /deals/#{id}.xml
     * @param Highrise_Entity_Deal $deal
     */
    public function update(Highrise_Entity_Deal $deal)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = '/deals/' . $deal->getId() . '.xml';
        $request->method = Highrise_Api_Client::METHOD_PUT;
        $request->expected = 200;
        $request->data = $deal->toXml();
        $response = $this->_client->request($request);
    }
    
    /**
     * Destroys the given deal. Note that this will also destroy any notes, 
     * emails, or files that are associated with the deal.
     *  
     * @link DELETE /deals/#{id}.xml
     */
    public function destroy($id)
    {
        $request = new Highrise_Api_Request();
        $request->endpoint = '/deals/' . $id . '.xml';
        $request->method = Highrise_Api_Client::METHOD_DELETE;
        $request->expected = 200;
        $response = $this->_client->request($request);
    }
    
    /**
     * Changes the status of the given deal. The value of the status name must 
     * be ‘pending’, ‘won’, or ‘lost’. Note that changing the status of a deal 
     * to ‘pending’ will fail with a 507 (Insufficient Storage) if you have 
     * reached your account limit of pending deals.
     *  
     * @link DELETE /deals/#{id}.xml
     */
    public function status($id, $status)
    {
        $xml = "<status><name>{$status}</name></status>";
        
        $request = new Highrise_Api_Request();
        $request->endpoint = '/deals/' . $id . '/status.xml';
        $request->method = Highrise_Api_Client::METHOD_PUt;
        $request->expected = 200;
        $request->data = $xml;
        $response = $this->_client->request($request);
    }
}
?>