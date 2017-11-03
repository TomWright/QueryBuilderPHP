<?php


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\QueryHelper;

class QueryHelperTest extends TestCase
{

    /**
     * @var QueryHelper
     */
    private $helper;

    public function setUp()
    {
        parent::setUp();
        $this->helper = new QueryHelper();
    }

    public function testTypeIsDetectedIsStandardQuery()
    {
        $sql = 'SELECT * FROM MY_TABLE';
        $this->assertEquals('SELECT', $this->helper->getQueryType($sql));
    }

    public function testTypeIsDetectedInQueryWithWhitespaceBeginning()
    {
        $sql = '  
          SELECT 
          * FROM MY_TABLE';
        $this->assertEquals('SELECT', $this->helper->getQueryType($sql));
    }

    public function testTypeIsDetectedWithWhitespaceThroughout()
    {
        $sql = '  
          SELECT    * FROM
          MY_TABLE';
        $this->assertEquals('SELECT', $this->helper->getQueryType($sql));
    }

}