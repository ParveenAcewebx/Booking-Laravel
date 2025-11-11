<?php

namespace App\Helpers;

class Shortcode
{
    protected static $shortcodes = [];

    public static function register(string $tag, callable $callback)
    {
        self::$shortcodes[$tag] = $callback;
    }

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

    protected static function parseAttributes($text)
    {
        $attributes = [];
        preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[2];
        }
        return $attributes;
    }

    public static function render(string $name, $class, array $attrs = []): string
    {
        if (isset(static::$shortcodes[$name])) {
            return call_user_func(static::$shortcodes[$name], $attrs, $class);
        }
        return '';
    }
    

    public static function getRegisteredShortcodes(): array
    {
        return array_keys(self::$shortcodes);
    }
}
