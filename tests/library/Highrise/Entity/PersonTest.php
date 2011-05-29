<?php
require_once 'Highrise/Entity/Person.php';
/**
 * Highrise_Entity_Person test case.
 */
class Highrise_Entity_PersonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Highrise_Entity_Person
     */
    private $Highrise_Entity_Person;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated Highrise_Entity_PersonTest::setUp()
        $this->Highrise_Entity_Person = new Highrise_Entity_Person();
        $this->Highrise_Entity_Person->id = 2;
        
        $this->Highrise_Entity_Person->contactData()
            ->addEmailAddress('christian@thinkopen.biz',null,'work')
            ->addAddress('JHB');
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Highrise_Entity_PersonTest::tearDown()
        $this->Highrise_Entity_Person = null;
        parent::tearDown();
    }
    
    /**
     * Tests Highrise_Entity_Person->toXml()
     */
    public function testToXml ()
    {
        print $this->Highrise_Entity_Person->toXml();
    }
    
    
    /**
     * Tests Highrise_Entity_Person->fromXml()
     */
    public function testfromXml ()
    {
        $Parties = new Highrise_Api_People('annalienrealty', 'b5f72e50efe0981f947a5d8da1d531c3');
        //print $this->Highrise_Entity_Person->fromXml($Parties->show(57176377));
    }
}

