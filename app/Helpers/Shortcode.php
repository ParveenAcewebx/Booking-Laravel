<?php

namespace App\Helpers;

class Shortcode
{
    protected static $shortcodes = [];

    // Register a shortcode
    public static function register(string $tag, callable $callback)
    {
        self::$shortcodes[$tag] = $callback;
    }

    // Parse shortcodes in content
    public static function parse(string $content): string
    {
        foreach (self::$shortcodes as $tag => $callback) {
            $pattern = '/\[' . $tag . '(.*?)\]/';

            $content = preg_replace_callback($pattern, function ($matches) use ($callback) {
                $attributes = self::parseAttributes(trim($matches[1]));
                return call_user_func($callback, $attributes);
            }, $content);
        }

        return $content;
    }

    // Parse attributes like key="value"
        protected static function parseAttributes($text)
        {
            $attributes = [];
            preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $text, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $attributes[$match[1]] = $match[2];
            }

            return $attributes;
        }
}
