bugIf / bugIfEmpty
===================

[![Build Status](https://travis-ci.org/waterada/FileFabricate.svg?branch=master)](https://travis-ci.org/waterada/FileFabricate)


概要(summary)
-------------

```php
if (!is_array($param1)) {
    throw new LogicException(sprintf('$param1 should be array. $param1 = %s', var_export($param1, true)));
}
if (!is_array($param2)) {
    throw new LogicException(sprintf('$param2 should be array. $param2 = %s', var_export($param2, true)));
}
```

上記のように、何らかの前提条件をチェックするコードを仕込むことは実によくある。
これで例外が発生することは無いはずで、発生したらバグだというものである。
定義した関数の使い方を間違えられた際に、原因を特定しやすくするのに役立つ。

ただ、これを入れると、より重要な処理をソースコードの見た目的に圧迫するばかりでなく、
コードカバレッジレポートは、ここを通っていないと警告する。
（本来ありえないのでテストケース書く価値が薄いにもかかわらずだ。）

それを簡単に解決するのがこの関数 `bugIf` だ。
上記の処理はこう書ける。

```php
bugIf(!is_array($param1), $param1);
bugIf(!is_array($param2), $param2);
```

見た目もすっきりするし、カバレッジも下がることはない。
発生したらバグというものなので、カバレッジでバグケースを見てなくても問題ないはずである。

バグのために丁寧にエラーメッセージを書く必要は無いのではと思い、思い切って削っている。
有ったほうがよいと思えば下記のようにできる。
（第２引数以降で渡したものがすべてメッセージの中に展開される。）

```php
bugIf(!is_array($param1), '$param1 should be array.', $param1);
bugIf(!is_array($param2), '$param1 should be array.', $param2);
```

bugIf の使用例(example):
----------------

```php
//第１引数 !is_array($param1) が true なら、下記のようにExceptionが発生する。
bugIf(!is_array($param1));

// Exceptionのメッセージ:
// -------------------------------
// bug!
// -------------------------------



//Exception発生時に値を共に出力することも可能。var_export() を通して出力される。デバッグが楽になるはず。
bugIf(!is_array($param1), $param1);

// $param1 が "abc" の場合のExceptionのメッセージ:
// -------------------------------
// bug because:'abc'
// -------------------------------


//値はいくつでも出力可能。
bugIf(!is_array($param1), $param1, $param2);

// $param1 が "abc"
// $param2 が [3, 4] の場合のExceptionのメッセージ:
// -------------------------------
// bug because:
// 0:'abc'
// 1:array (
//   0 => 3,
//   1 => 4,
// )
// -------------------------------
```


bugIfEmpty
-------------

こちらは基本的に `bugIf` と同じだが、条件が bool ではなく、`empty()` によって評価される点が異なる。
評価の結果が `true` なら（空なら）例外が発生する。


bugIfEmpty の使用例(example):
----------------

```php
bugIfEmpty($param1);

// $param1 が null の場合のExceptionのメッセージ:
// -------------------------------
// bug because:
// condition:null
// -------------------------------


bugIfEmpty($param1, $param2);

// $param1 が []
// $param2 が [3, 4] の場合のExceptionのメッセージ:
// -------------------------------
// bug because:
// condition:array (
// )
// 0:array (
//   0 => 3,
//   1 => 4,
// )
// -------------------------------
```



Install
-------------

- 適当な場所（`bootstrap.php` 等かならず読まれる場所）に [src/BugIf.php](src/BugIf.php) の内容をコピーしてください。
  (Please copy and paste the contents of [src/BugIf.php](src/BugIf.php) into somewhere of your code which will always be called.)

もしくは (Or)

- composer.json に下記を記述してください。(Please write the following into your composer.json.)

  - `"repositories": []` のパートに下記を追加 (inside the `"repositories": []` part):

  ```
        {
            "type": "git",
            "url": "git@github.com:waterada/phplib-bug_if.git"
        }
  ```

  - `"require-dev": {}` のパートに下記を追加 (inside the `"require-dev": {}` part):

  ```
        "waterada/phplib-bug_if": "1.0.*"
  ```

- `comoposer update waterada/phplib-bug_if` を実行してください。(Please run `comoposer update waterada/phplib-bug_if`.)

以上で使えるようになります。
That's all.
