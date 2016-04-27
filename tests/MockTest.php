<?php

require_once __DIR__.'/classes/Test1.php';

require_once __DIR__.'/../src/Mock.php';

/**
 * Date: 27.04.2016
 * Time: 12:28
 */
class MockTest extends PHPUnit_Framework_TestCase
{
    function testMock1() {
        $mockTest1 = new \Insperedia\Expose\Mock('test1');
        $mockTest1->listenMethod('sum');
        $test1 = $mockTest1->createClass(1,2);
        $this->assertEquals(3, $test1->sum());
        $this->assertEquals(1, $mockTest1->getCallCount('sum'));
    }
}