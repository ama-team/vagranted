<?php

namespace AmaTeam\Vagranted\Language;

/**
 * @author Etki <etki@etki.me>
 */
class Strings
{
    public static function indent($input, $amount = 2, $sequence = ' ')
    {
        $prefix = str_repeat($sequence, $amount);
        $lines = explode("\n", $input);
        $upgraded = array_map(function ($line) use ($prefix) {
            return $prefix . $line;
        }, $lines);
        return implode("\n", $upgraded);
    }
}
