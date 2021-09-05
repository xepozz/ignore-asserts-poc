<?php
declare(strict_types=1);

namespace Xepozz\IgnoreAssertsPoc\Tests\Bench;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use Xepozz\IgnoreAssertsPoc\StringHasher;

ini_set('zend.assertions', '0');
ini_set('assert.exception', '0');

/**
 * @BeforeMethods("setUp")
 */
class WithoutAssertsBench
{
    private string $string = '';

    public function setUp(): void
    {
        $this->string = str_repeat('X', 2000);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     * @Warmup(10)
     */
    public function bench(): void
    {
        StringHasher::hash($this->string);
    }
}