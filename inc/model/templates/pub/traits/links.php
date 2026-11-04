<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\traits;

/**
 * Trait for common functions for Twig based frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait links {

    /**
     * Parse links in article text
     * @param string $content
     * @param array $attributes
     * @return string
     */
    final protected function parseLinks(
        string $content,
        array $attributes = []
    ) : string
    {
        $attrStr = '';

        if (count($attributes)) {

            $attributes = array_unique($attributes);

            array_walk($attributes, function(&$val, $key) {
                $val = sprintf('%s="%s"', $key, $val);
            });

            $attrStr = implode(' ', array_values($attributes));
        }

        return preg_replace(
            '/((http|https?):\/\/\S+[^\s.,>)\]\"\'<\/])/i',
            "<a href=\"$0\" {$attrStr}>$0</a>",
            $content
        );
    }
}
