<?php

namespace App\Services;

class HelperFunctionService
{
    /**
     * Get the color class for a tag name.
     */
    public static function getTagColor($tagName)
    {
        $tagName = strtolower($tagName);
        switch ($tagName) {
            case 'software engineering':
                return 'bg-indigo-600';
            case 'multimedia arts':
                return 'bg-red-600';
            case 'animation':
                return 'bg-yellow-500';
            case 'game development':
                return 'bg-[#0C0929]';
            case 'real estate management':
                return 'bg-green-500';
            default:
                return 'bg-gray-600';
        }
    }

    /**
     * Get the abbreviation for a tag name.
     */
    public static function abbreviateTag($tagName)
    {
        $tagName = strtolower($tagName);
        switch ($tagName) {
            case 'software engineering':
                return 'SE';
            case 'animation':
                return 'ANIM';
            case 'real estate management':
                return 'REM';
            case 'game development':
                return 'GD';
            case 'multimedia arts':
                return 'MMA';
            default:
                return strtoupper(substr($tagName, 0, 3));
        }
    }
}
