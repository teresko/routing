<?php


namespace Fracture\Routing;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class RouteTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Fracture\Routing\Route::__construct
     * @covers Fracture\Routing\Route::getMatch
     *
     * @covers Fracture\Routing\Route::cleanMatches
     */
    public function testPatternExpressionRetrieved()
    {
        $pattern = $this->getMock('Fracture\Routing\Pattern', ['getExpression'], ['']);
        $pattern->expects($this->once())
                ->method('getExpression')
                ->will($this->returnValue('##'));


        $unit = new Route($pattern, 'foo');
        $unit->getMatch('/uri');
    }

    /**
     * @dataProvider provideSimpleMatches
     * @covers Fracture\Routing\Route::__construct
     * @covers Fracture\Routing\Route::getMatch
     *
     * @covers Fracture\Routing\Route::cleanMatches
     *
     * @depends testPatternExpressionRetrieved
     */
    public function testSimpleMatches($expression, $url, $expected)
    {
        $pattern = $this->getMock('Fracture\Routing\Pattern', ['getExpression'], ['']);
        $pattern->expects($this->once())
                ->method('getExpression')
                ->will($this->returnValue($expression));

        $route = new Route($pattern, 'not-important');
        $this->assertEquals($expected, $route->getMatch($url));
    }

    public function provideSimpleMatches()
    {
        return include FIXTURE_PATH . '/routes-simple.php';
    }


    /**
     * @dataProvider provideMatchesWithDefaults
     * @covers Fracture\Routing\Route::__construct
     * @covers Fracture\Routing\Route::getMatch
     *
     * @covers Fracture\Routing\Route::cleanMatches
     *
     * @depends testPatternExpressionRetrieved
     */
    public function testWithDefaultMatches($expression, $url, $defaults, $expected)
    {
        $pattern = $this->getMock('Fracture\Routing\Pattern', ['getExpression'], ['']);
        $pattern->expects($this->once())
                ->method('getExpression')
                ->will($this->returnValue($expression));

        $route = new Route($pattern, 'not-important', $defaults);
        $this->assertEquals($expected, $route->getMatch($url));
    }

    public function provideMatchesWithDefaults()
    {
        return include FIXTURE_PATH . '/routes-with-defaults.php';
    }


    /**
     * @dataProvider provideFailingMatch
     * @covers Fracture\Routing\Route::__construct
     * @covers Fracture\Routing\Route::getMatch
     *
     * @covers Fracture\Routing\Route::cleanMatches
     *
     * @depends testPatternExpressionRetrieved
     */
    public function testFailingMatches($expression, $url)
    {
        $pattern = $this->getMock('Fracture\Routing\Pattern', ['getExpression'], ['']);
        $pattern->expects($this->once())
                ->method('getExpression')
                ->will($this->returnValue($expression));

        $route = new Route($pattern, 'not-important');
        $this->assertFalse($route->getMatch($url));
    }

    public function provideFailingMatch()
    {
        return include FIXTURE_PATH . '/routes-unmatched.php';
    }
}
