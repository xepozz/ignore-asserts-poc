# PoC: ignoring assertions in "prod" mode

## About

This is proof of concept how to increase performance in PHP applications.

## Main idea

The main idea is that assertions make some influence to application execution speed, that is exactly correct.

You can ignore these assertions in production to increase the speed.

## Recipe

Set value of parameter `zend.assertions` to `0` in `php.ini` to disable any asserts. Or add the following code into "
index.php":

```php
ini_set('zend.assertions', '0');
```

#### Specified asserts

Wrap some assertions with `flag` that will disable ones when the flag is off.

```php
if (ASSERTIONS_ENABLED) {
  assert(...)

  if (codition) {
    throw new Exception('The contidion should be false');
  }
}
```

### What can be disabled

1. Any assertions of configuration (i.g. parameters from DIC):
    1. Checking type of argument
    2. Checking argument value
    3. Checking arguments amount
    4. Checking file existing
2. Any pre-runtime checks
3. TBD

## Tests

I made a few tests:

`WithAssertsBench`: tests with enabled either assertions mechanism and throwing exceptions.
`AssertsWithWarningsBench`: tests with enabled assertions mechanism and showing warnings instead of exceptions.
`WithoutAssertsBench`: tests with disabled assertions mechanism.

Run `./vendor/bin/phpbench run tests/Bench/ --report=aggregate` to see results.

Result:

```
+--------------------------+---------+-----+------+-----+-----------+---------+--------+
| benchmark                | subject | set | revs | its | mem_peak  | mode    | rstdev |
+--------------------------+---------+-----+------+-----+-----------+---------+--------+
| WithAssertsBench         | bench   |     | 1000 | 10  | 586.280kb | 5.302μs | ±0.66% |
| AssertsWithWarningsBench | bench   |     | 1000 | 10  | 586.312kb | 5.275μs | ±0.54% |
| WithoutAssertsBench      | bench   |     | 1000 | 10  | 586.296kb | 4.233μs | ±0.21% |
+--------------------------+---------+-----+------+-----+-----------+---------+--------+
```

`mode` = average time to each test.

`5.302μs` = ~5 milliseconds 

### Summary

As you can see above only 5 assertions in `\Xepozz\IgnoreAssertsPoc\StringHasher` can slow application in 1 ms. 

If you have 500 assertions and 1000 rps (request per second) you will spend 100 sec (1 min 40 sec) only to asserting. 

## Restrictions

1. You should be completely confident in application configuration.
2. You should test all your configuration before you will release your product to productive system.

# Useful link

https://www.php.net/manual/ru/function.assert

https://www.php.net/manual/ru/info.configuration.php#ini.assert.exception
