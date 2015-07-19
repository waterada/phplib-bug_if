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
    switch (count($args)) {
        case 0:
            $msg = 'bug!';
            break;
        case 1:
            $msg = 'bug because:' . var_export($args[0], true);
            break;
        default:
            $msg = 'bug because:';
            foreach ($args as $i => $a) {
                $msg .= "\n(" . $i . "):" . var_export($args[$i], true);
            }
            break;
    }
    throw new LogicException($msg);
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
    $msg = sprintf("bug because:\n(condition):%s", var_export($condition, true));
    foreach ($args as $i => $a) {
        $msg .= "\n(" . $i . "):" . var_export($args[$i], true);
    }
    throw new LogicException($msg);
}

class BugIf {
    public static function import() {
    }
}
