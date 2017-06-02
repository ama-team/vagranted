<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Language;

use AmaTeam\Vagranted\Language\MappingIterator;
use AmaTeam\Vagranted\Tests\Support\Test;
use ArrayIterator;

/**
 * @author Etki <etki@etki.me>
 */
class MappingIteratorTest extends Test
{
    /**
     * @test
     */
    public function shouldBehave()
    {
        $data = ['alpha' => 2, 'beta' => 4, 'gamma' => 6, 'delta' => 8,];
        $expected = ['alpha' => 2, 'beta' => 4, 'gamma' => 1, 'delta' => 3,];
        $iterator = new ArrayIterator($data);
        $wrapper = new MappingIterator($iterator, function ($item) {
            return $item % 5;
        });
        $this->assertTrue($wrapper->valid());
        foreach ($expected as $key => $value) {
            $this->assertTrue($wrapper->valid());
            $this->assertEquals($key, $wrapper->key());
            $this->assertEquals($value, $wrapper->current());
            $wrapper->next();
        }
        $this->assertFalse($wrapper->valid());
        $wrapper->rewind();
        $this->assertTrue($wrapper->valid());
    }
}
