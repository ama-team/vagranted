<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Logger;

use AmaTeam\Vagranted\Logger\NameFactory;
use AmaTeam\Vagranted\Tests\Support\Test;

/**
 * @author Etki <etki@etki.me>
 */
class NameFactoryTest extends Test
{
    public function dataProvider()
    {
        return [
            ['test_name', 'test_name',],
            ['test_Name', 'test_name',],
            ['testName', 'test_name',],
            ['Test\\Name', 'test.name',],
            ['Prefix\\Pit\\Test\\Name', 'test.name', ['Prefix\\Pit\\',],],
            ['\\Prefix\\Pit\\Test\\Name', 'test.name', ['Prefix\\Pit\\',],],
            ['test_name', 'prefix.test_name', [], 'prefix.'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataProvider
     *
     * @param $input
     * @param $expectation
     * @param array $namespaces
     * @param string $prefix
     */
    public function shouldConvertAsExpected(
        $input,
        $expectation,
        array $namespaces = [],
        $prefix = ''
    ) {
        $extractor = new NameFactory($namespaces, $prefix);
        $this->assertEquals($expectation, $extractor->convert($input));
    }
}
