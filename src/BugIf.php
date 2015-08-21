<?php

/**
 * $condition が true ならバグとみなして例外を投げる。
 * これを使うことでソースコードが読みやすくなり、テストカバレッジのために無駄なテストケースを書かなくて良くなる。
 *
 * Throw an Exception as it is a bug if $condition is true.
 * This helps your readable codes and test code coverages.
 *
 * 第2引数以降に値を渡せば、その値がメッセージに表示される。
 *
 * @param $condition
 * @return null
 */
function bugIf($condition) {
    if ($condition == false) {
        return null;
    }

    $args = func_get_args();
    array_shift($args);
    throw BugIf::newException($args);
}

/**
 * @param $condition
 * @return null
 */
function bugIfEmpty(&$condition) {
    if (!empty($condition)) {
        return null;
    }

    $args = func_get_args();
    array_shift($args);
    $args = array_merge([
        'condition' => $condition,
    ], $args);
    throw BugIf::newException($args);
}

class BugIf {
    static $exceptionClass = 'LogicException';

    public static function import($exceptionClass = 'LogicException') {
        self::$exceptionClass = $exceptionClass;
    }

    public static function importForCakePHP() {
        self::import('PHPUnit_Framework_Exception');
    }

    public static function newException($args = []) {
        if (empty($args)) {
            $msg = 'bug!';
        } elseif (count($args) == 1 && array_key_exists(0, $args)) {
            $msg = 'bug because:' . var_export($args[0], true);
        } else {
            $msg = 'bug because:';
            foreach ($args as $i => $a) {
                $msg .= "\n(" . $i . "):" . var_export($a, true);
            }
        }
        $exceptionClass = self::$exceptionClass;
        return new $exceptionClass($msg);
    }
}
