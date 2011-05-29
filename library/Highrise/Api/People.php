<?php
/** 
 * @author cjb
 * 
 * 
 */

require_once 'Highrise/Request.php';

class Highrise_Api_People
{
    /**
     * Returns a single person.
     * 
     * @link GET /people/#{id}.xml
     * @param int $id
     */
    public function show($id)
    {
        $request = new Highrise_Request();
        $request->endpoint = "/people/{$id}.xml";
        $request->method = Highrise_Api::METHOD_GET;
        $request->expected = 200;
        return $request;
 
    }
    
    /**
     * Returns a collection of people that are visible to the authenticated user. 
     * The list is paginated using offsets. If 500 elements are returned (the page 
     * limit), use ?n=500 to check for the next 500 and so on.
     * 
     * @link GET /people.xml
     * @param int $offset
     */
    public function listAll($offset = null)
    {
        $append = ($offset) ? '?n=' . $offset : null;
        return $this->_sendRequest('/people.xml' . $append,Highrise_Http::METHOD_GET);
    }
    
    /**
     * Returns a collection of people that have been created or updated 
     * since the given time
     * 
     * @link GET /people.xml?since=20070425154546.
     * @param int $time
     */
    public function listSince($time)
    {
        return $this->_sendRequest('/people.xml?since=' . $time,Highrise_Http::METHOD_GET);
    }
    
    /**
     * Returns people who match your search criteria. Search by any criteria you 
     * can on the Contacts tab, including custom fields. Combine criteria to narrow 
     * results.
     * 
     * If no people with the given criteria exist an empty people container will be 
     * returned. Results are paged in groups of 25. Use ?n=25 to check for the next 
     * 25 results and so on.
     * 
     * @link GET /people/search.xml?criteria[state]=CA&criteria[zip]=90210&criteria[custom_field]=foobar
     * @param array $search
     * @param int $offset
     * @return array $collection
     */
    public function listByCriteria(array $search, $offset = null)
    {
        foreach ($search as $key => $value)
        {
            $pieces[] = "criteria[$key]=$value";
        }
        if ($offset) $pieces[] = 'n=' . $offset;
        return $this->_sendRequest('/people/search.xml?' . implode('&', $pieces),Highrise_Http::METHOD_GET);
    }
    
    /**
     * Creates a new person with the currently authenticated user as the author. 
     * The XML for the new person is returned on a successful request with the 
     * timestamps recorded and ids for the contact data associated.
     * 
     * Additionally, the company-name is used to either lookup a company with 
     * that name or create a new one if it didnÕt already exist. You can also 
     * refer to an existing company instead using company-id.
     * 
     * By default, a new person is assumed to be visible to Everyone. You can 
     * also chose to make the person only visible to the creator using ÒOwnerÓ 
     * as the value for the visible-to tag. Or ÒNamedGroupÓ and pass in a 
     * group-id tag too.
     * 
     * If the account doesnÕt allow for more people to be created, a 
     * Ò507 Insufficient StorageÓ response will be returned.
     * 
     * @link POST /people.xml
     * @param Highrise_Person $person
     * @return Highrise_Person $person
     */
    public function create(Highrise_Entity_Person $person)
    {
        //$response = $this->_sendRequest('/people.xml', Highrise_Http::METHOD_POST, 201, $person->toXml());
        $request = new Highrise_Request();
        $request->endpoint = '/people.xml';
        $request->method = Highrise_Api::METHOD_POST;
        $request->expected = 201;
        $request->data = $person->toXml();
        return $request;
    }
    
    /**
     * Contact data that includes an id will be updated, contact data that 
     * doesnÕt will be assumed to be new and created from scratch. To remove 
     * a piece of contact data, prefix its id with a minus sign
     * 
     * @link PUT /people/#{id}.xml
     * @param Highrise_Person $person
     * @param bool $reload get XML of the successfully updated person.
     */
    public function update(Highrise_Entity_Person $person,$reload = false)
    {
        $append = ($reload) ? '?reload=true' : null;
        return $this->_sendRequest('/people/' . $person->getId() . '.xml' . $append, Highrise_Http::METHOD_PUT, 200, $person->toXml());
    }
    
    /**
     * Destroys the person at the referenced URL.
     *  
     * @link DELETE /people/#{id}.xml
     */
    public function destroy($id)
    {
        return $this->_sendRequest('/people/' . $id . '.xml', Highrise_Http::METHOD_DELETE);
    }
}
?>