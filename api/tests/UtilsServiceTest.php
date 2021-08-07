<?php

namespace App\Tests;

use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UtilsServiceTest extends WebTestCase
{

    public function init() {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        $this->logger = UtilsService::setLogger($container->get('mylogger'));
    }

    public function testPermutation(): void
    {
        $param = [1,2];
        $expected = [[1,2],[2,1]];
        $this->assertEquals($expected, UtilsService::permutation($param));
    }

    public function testAssociations() {
        $params = [[1,2],['a','b']];
        $expected = [[1,'a'],[1,'b'],[2, 'a'], [2, 'b']];
        $this->assertEquals($expected, UtilsService::associations($params));

    }

    public function testCombination() {
        $params = ['a', 'b'];
        $expected = [["a"], ["b"], ["a","b"]];
        $this->assertEquals($expected, UtilsService::combination($params));
        $params = [1, 2];
        $expected = [[1], [2], [1,2]];
        $this->assertEquals($expected, UtilsService::combination($params));
    }

    public function testAssociationsAndCombination() {
        $params = [[1,2],['a','b']];
        $expected = [[1],[1, 'b'],['b'], [2, 'b'], [2, 'a'], ['a'], ['b']];
        $res = UtilsService::associationAndCombination($params);
        $this->assertEquals([1],array_shift($res));
        $this->assertEquals(['a'],array_shift($res));
        $this->assertEquals([1,'a'],array_shift($res));
        $this->assertEquals(['b'],array_shift($res));
        $this->assertEquals([1,'b'],array_shift($res));
        $this->assertEquals([2],array_shift($res));
        $this->assertEquals([2, 'a'],array_shift($res));
        $this->assertEquals([2, 'b'],array_shift($res));
        $this->assertEquals([],$res);

    }

    public function testContains() {
        $a = [1,'a', 'c'];
        $b = [1, 'c'];
        $this->assertTrue(UtilsService::contains($a, $b));
        $c = [1, 'b'];
        $this->assertFalse(UtilsService::contains($a, $c ));
    }
}
