<?php
require_once 'Highrise/Api.php';
require_once 'Highrise/Api/People.php';
/**
 * Highrise_Api_People test case.
 */
class Highrise_Api_PeopleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Highrise_Api_People
     */
    private $Highrise_Api_People;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated Highrise_Api_PeopleTest::setUp()
        $this->Highrise_Api_People = new Highrise_Api_People();
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest::tearDown()
        $this->Highrise_Api_People = null;
        parent::tearDown();
    }
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
    /**
     * Tests Highrise_Api_People->show()
     */
    public function testShow ()
    {
        $api = new Highrise_Api('annalienrealty', 'b5f72e50efe0981f947a5d8da1d531c3', false);
        
        $request = $this->Highrise_Api_People->show(72813248);
        $response = $api->request($request);
        //$person = Highrise_Entity_Person::createFromXml($response);
        $this->assertEquals($request->getExpectedResponse(),$response->getCode());
        
    }
    /**
     * Tests Highrise_Api_People->listAll()
     */
    public function testListAll ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest->testListAll()
        $this->markTestIncomplete("listAll test not implemented");
        $this->Highrise_Api_People->listAll(/* parameters */);
    }
    /**
     * Tests Highrise_Api_People->listSince()
     */
    public function testListSince ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest->testListSince()
        $this->markTestIncomplete("listSince test not implemented");
        $this->Highrise_Api_People->listSince(/* parameters */);
    }
    /**
     * Tests Highrise_Api_People->listByCriteria()
     */
    public function testListByCriteria ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest->testListByCriteria()
        $this->markTestIncomplete("listByCriteria test not implemented");
        $this->Highrise_Api_People->listByCriteria(/* parameters */);
    }
    /**
     * Tests Highrise_Api_People->create()
     */
    public function testCreate ()
    {
        $this->markTestIncomplete("listAll test not implemented");
        $person = new Highrise_Entity_Person();
        $person->firstName = 'ted';
        $person->contactData()
            ->addEmailAddress('ted@thinkopen.biz',null,'work')
            ->addAddress('JHB',null,'South Africa','Gauteng', '123', '456','work')
            ;
        $api = new Highrise_Api('annalienrealty', 'b5f72e50efe0981f947a5d8da1d531c3', true);
        $response = $api->request($this->Highrise_Api_People->create($person));
        //print_r($response);
    }
    /**
     * Tests Highrise_Api_People->update()
     */
    public function testUpdate ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest->testUpdate()
        $this->markTestIncomplete("update test not implemented");
        $this->Highrise_Api_People->update(/* parameters */);
    }
    /**
     * Tests Highrise_Api_People->destroy()
     */
    public function testDestroy ()
    {
        // TODO Auto-generated Highrise_Api_PeopleTest->testDestroy()
        $this->markTestIncomplete("destroy test not implemented");
        $this->Highrise_Api_People->destroy(/* parameters */);
    }
}

