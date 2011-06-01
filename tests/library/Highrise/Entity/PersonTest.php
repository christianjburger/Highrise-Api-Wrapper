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
        
        $this->Highrise_Entity_Person->getContactData()
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
        $xml = file_get_contents(dirname(__FILE__) . '/person.xml');
        $this->Highrise_Entity_Person->fromXml($xml);
        $expected = '<?xml version="1.0"?>
<person><id type="integer">57176377</id><first-name>Christian</first-name><last-name>Burger</last-name><contact-data><email-addresses><email-address><id>27307285</id><address>christian@thinkopen.biz</address><location>Work</location></email-address></email-addresses><addresses/><phone-numbers><phone-number><id>61464083</id><number>0110837837</number><location>Work</location></phone-number><phone-number><id>61464083</id><number>0835661172</number><location>Mobile</location></phone-number></phone-numbers><instant-messengers/><twitter-accounts><twitter-account><id>61459935</id><username>christianZA</username><url>http://twitter.com/christianZA</url><location>Personal</location></twitter-account></twitter-accounts><web-addresses><web-address><id>61459935</id><url>http://za.linkedin.com/christianburger</url><location>Personal</location></web-address><web-address><id>61459935</id><url>http://thinkopen.biz</url><location>Personal</location></web-address></web-addresses></contact-data><tags><tag><id>1181584</id><name>Owner</name></tag><tag><id>1181586</id><name>Investor</name></tag></tags></person>
';
        $actual = $this->Highrise_Entity_Person->toXml();
        $this->assertEquals($expected,$actual);
    }
    
    
    /**
     * Tests Highrise_Entity_Person->fromXml()
     */
    public function testFromXml()
    {
        $xml = file_get_contents(dirname(__FILE__) . '/person.xml');
        $this->Highrise_Entity_Person->fromXml($xml);
        //print_r($this->Highrise_Entity_Person);
    }
}

