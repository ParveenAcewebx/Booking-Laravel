<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class CheckSlugHelper
{
    protected static $slugCache = [];

    /**
     * Check if slug is used anywhere in project (with cache)
     */
    public static function isSlugUsed($slug)
    {
        $paths = [base_path('app'), base_path('resources/views')];
        $used = false;

        foreach ($paths as $path) {
            $files = File::allFiles($path);

            foreach ($files as $file) {
                $lines = file($file->getRealPath());

                foreach ($lines as $line) {
                    $line = trim($line);

                    if (str_starts_with($line, '//') || str_starts_with($line, '#')) {
                        continue;
                    }

                    if (preg_match('/^\s*\/\*/', $line) || preg_match('/\*\/\s*$/', $line)) {
                        continue;
                    }

                    if (strpos($line, "'$slug'") !== false || strpos($line, "\"$slug\"") !== false) {
                        $used = true;
                        break 3; 
                    }
                }
            }
        }

        return $used;
    }

    public static function canDelete($slug)
    {
        return !self::isSlugUsed($slug);
    }
}
