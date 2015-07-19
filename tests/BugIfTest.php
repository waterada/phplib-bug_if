<?php
// vendor/bin/phpunit tests/BugIfTest.php

/** @noinspection PhpIncludeInspection */
require_once realpath(dirname(dirname(__FILE__)) . "/vendor/autoload.php");
BugIf::import();

class BugIfTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage bug!
     */
    public function test_bugIf_第1引数がtrueなら例外が発生する() {
        $condition = true;
        bugIf($condition);
    }

    public function test_bugIf_第1引数がfalseなら例外が発生しない() {
        $condition = false;
        $param1 = "aaa";
        $param2 = "bbb";
        bugIf($condition);
        bugIf($condition, $param1);
        bugIf($condition, $param1, $param2);
    }

    public function provider_bugIf_いろいろな引数型() {
        return [
            [null],
            [false],
            [1],
            ["1"],
            ["abc"],
            [[1, "1"]],
        ];
    }

    /**
     * @dataProvider provider_bugIf_いろいろな引数型
     * @param $arg
     * @expectedException LogicException
     */
    public function test_bugIf_第2引数が出力される($arg) {
        $condition = true;
        try {
            bugIf($condition, $arg);
        } catch (LogicException $e) {
            $this->assertEquals("bug because:" . var_export($arg, true), $e->getMessage());
            throw $e;
        }
    }


    public function provider_bugIf_引数の数による出力の変化() {
        return [
            [
                [true],
                "bug!",
            ],
            [
                [true, 'a'],
                sprintf("bug because:%s",
                    var_export('a', true)),
            ],
            [
                [true, 'a', 'b'],
                sprintf("bug because:\n(0):%s\n(1):%s",
                    var_export('a', true),
                    var_export('b', true)),
            ],
            [
                [true, 'a', 'b', 'c'],
                sprintf("bug because:\n(0):%s\n(1):%s\n(2):%s",
                    var_export('a', true),
                    var_export('b', true),
                    var_export('c', true)),
            ],
            [
                [true, 'a', 'b', 'c', 'd'],
                sprintf("bug because:\n(0):%s\n(1):%s\n(2):%s\n(3):%s",
                    var_export('a', true),
                    var_export('b', true),
                    var_export('c', true),
                    var_export('d', true)),
            ],
        ];
    }

    /**
     * @dataProvider provider_bugIf_引数の数による出力の変化
     * @param $args
     * @param $expected
     * @expectedException LogicException
     */
    public function test_bugIf_引数の数による出力の変化($args, $expected) {
        try {
            call_user_func_array('bugIf', $args);
        } catch (LogicException $e) {
            array_shift($args);
            $this->assertEquals($expected, $e->getMessage());
            throw $e;
        }
    }

    public function test_bugIf_戻り値でnullを返す() {
        $result = bugIf(false);
        $this->assertNull($result);
    }

    public function provider_bugIfEmpty_例外発生するcondition() {
        return [
            [null],
            [false],
            [0],
            [0.0],
            ["0"],
            [""],
            [[]],
        ];
    }

    /**
     * @dataProvider             provider_bugIfEmpty_例外発生するcondition
     * @expectedException LogicException
     * @expectedExceptionMessage bug because:
     * @expectedExceptionMessage condition:
     * @param $condition
     */
    public function test_bugIfEmpty_第1引数により例外が発生する($condition) {
        bugIfEmpty($condition);
    }

    public function provider_bugIfEmpty_例外発生しないcondition() {
        $obj = (object) [];
        return [
            [$obj],
            [true],
            [1],
            [-1],
            [0.1],
            ["1"],
            ["a"],
            [[1]],
        ];
    }

    /**
     * @dataProvider             provider_bugIfEmpty_例外発生しないcondition
     * @param $condition
     */
    public function test_bugIfEmpty_第1引数により例外が発生しない($condition) {
        $param1 = "aaa";
        $param2 = "bbb";
        bugIfEmpty($condition);
        bugIfEmpty($condition, $param1);
        bugIfEmpty($condition, $param1, $param2);
    }

    public function provider_bugIfEmpty_いろいろな引数型() {
        return [
            [null],
            [false],
            [1],
            ["1"],
            ["abc"],
            [[1, "1"]],
        ];
    }

    /**
     * @dataProvider provider_bugIfEmpty_いろいろな引数型
     * @param $arg
     * @expectedException LogicException
     */
    public function test_bugIfEmpty_第2引数が出力される($arg) {
        $condition = false;
        try {
            bugIfEmpty($condition, $arg);
        } catch (LogicException $e) {
            $this->assertEquals("bug because:\n(condition):false\n(0):" . var_export($arg, true), $e->getMessage());
            throw $e;
        }
    }


    public function provider_bugIfEmpty_引数の数による出力の変化() {
        return [
            [
                [false],
                sprintf("bug because:\n(condition):%s",
                    var_export(false, true)),
            ],
            [
                [false, 'a'],
                sprintf("bug because:\n(condition):%s\n(0):%s",
                    var_export(false, true),
                    var_export('a', true)),
            ],
            [
                [false, 'a', 'b'],
                sprintf("bug because:\n(condition):%s\n(0):%s\n(1):%s",
                    var_export(false, true),
                    var_export('a', true),
                    var_export('b', true)),
            ],
            [
                [false, 'a', 'b', 'c'],
                sprintf("bug because:\n(condition):%s\n(0):%s\n(1):%s\n(2):%s",
                    var_export(false, true),
                    var_export('a', true),
                    var_export('b', true),
                    var_export('c', true)),
            ],
        ];
    }

    /**
     * @dataProvider provider_bugIfEmpty_引数の数による出力の変化
     * @param $args
     * @param $expected
     * @expectedException LogicException
     */
    public function test_bugIfEmpty_引数の数による出力の変化($args, $expected) {
        try {
            switch (count($args)) {
                case 1:
                    bugIfEmpty($args[0]);
                    break;
                case 2:
                    bugIfEmpty($args[0], $args[1]);
                    break;
                case 3:
                    bugIfEmpty($args[0], $args[1], $args[2]);
                    break;
                case 4:
                    bugIfEmpty($args[0], $args[1], $args[2], $args[3]);
                    break;
            }
        } catch (LogicException $e) {
            array_shift($args);
            $this->assertEquals($expected, $e->getMessage());
            throw $e;
        }
    }

    public function test_bugIfEmpty_戻り値でnullを返す() {
        $param = "aaa";
        $result = bugIfEmpty($param);
        $this->assertNull($result);
    }

    /**
     * @expectedException LogicException
     */
    public function test_bugIfEmpty_存在しない配列の要素を参照していても警告出ない() {
        $param = [];
        bugIfEmpty($param['存在しない要素']);
    }
}
