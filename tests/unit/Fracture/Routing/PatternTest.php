<?php


namespace Fracture\Routing;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class PatternTest extends PHPUnit_Framework_TestCase
{


    /**
     * @dataProvider provideSimplePatterns
     * @covers Fracture\Routing\Pattern::__construct
     * @covers Fracture\Routing\Pattern::prepare
     * @covers Fracture\Routing\Pattern::getExpression
     *
     * @covers Fracture\Routing\Pattern::cleanNotation
     * @covers Fracture\Routing\Pattern::addSlash
     * @covers Fracture\Routing\Pattern::parseNotation
     * @covers Fracture\Routing\Pattern::applyConditions
     */
    public function testSimplePatterns($notation, $result)
    {
        $pattern = new Pattern($notation);
        $pattern->prepare();

        $this->assertEquals($result, $pattern->getExpression());

    }

    public function provideSimplePatterns()
    {
        return include FIXTURE_PATH . '/patterns-simple.php';
    }


    /**
     * @dataProvider provideConditionalPatterns
     * @covers Fracture\Routing\Pattern::__construct
     * @covers Fracture\Routing\Pattern::prepare
     * @covers Fracture\Routing\Pattern::getExpression
     *
     * @covers Fracture\Routing\Pattern::cleanNotation
     * @covers Fracture\Routing\Pattern::addSlash
     * @covers Fracture\Routing\Pattern::parseNotation
     * @covers Fracture\Routing\Pattern::applyConditions
     */
    public function testConditionalPatterns($notation, $conditions, $result)
    {
        $pattern = new Pattern($notation, $conditions);
        $pattern->prepare();

        $this->assertEquals($result, $pattern->getExpression());

    }

    public function provideConditionalPatterns()
    {
        return include FIXTURE_PATH . '/patterns-conditional.php';
    }
}
