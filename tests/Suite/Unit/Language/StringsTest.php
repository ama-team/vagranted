<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Language;

use AmaTeam\Vagranted\Language\Strings;
use AmaTeam\Vagranted\Tests\Support\Test;

/**
 * @author Etki <etki@etki.me>
 */
class StringsTest extends Test
{
    public function dataProvider()
    {
        return [
            ['test sentence', '  test sentence', 2, ' '],
            [
                'test' . PHP_EOL . 'sentence',
                '>>>test' . PHP_EOL . '>>>sentence',
                3,
                '>',
            ],
            ['test sentence', 'test sentence', 0, ' ']
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataProvider
     *
     * @param string $input
     * @param string $output
     * @param int $depth
     * @param string $seq
     */
    public function shouldIndentAsJesusDoes($input, $output, $depth, $seq)
    {
        $this->assertEquals($output, Strings::indent($input, $depth, $seq));
    }
}
