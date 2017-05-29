<?php

namespace AmaTeam\Vagranted\Language;

/**
 * @author Etki <etki@etki.me>
 */
class Strings
{
    /**
     * Indents every line in $input for $depth repeats of $sequence
     *
     * @param string $input
     * @param int $depth
     * @param string $sequence
     * @return string
     */
    public static function indent($input, $depth = 2, $sequence = ' ')
    {
        $prefix = str_repeat($sequence, $depth);
        $lines = explode("\n", $input);
        $upgraded = [];
        foreach ($lines as $line) {
            $upgraded[] = $prefix . $line;
        }
        return implode("\n", $upgraded);
    }
}
