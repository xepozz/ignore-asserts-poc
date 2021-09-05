<?php
declare(strict_types=1);

namespace Xepozz\IgnoreAssertsPoc;

final class StringHasher
{
    public static function hash(?string $string): string
    {
        assert($string !== null && $string !== '');
        assert(str_contains($string, 'bad word') === false);
        assert(strlen($string) === 2000);
        assert(preg_match('/^X{2000}$/', $string) === 1);
        assert($string[1] === 'X');

        return md5($string);
    }
}