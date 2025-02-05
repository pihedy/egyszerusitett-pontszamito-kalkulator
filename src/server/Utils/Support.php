<?php declare(strict_types=1);

namespace App\Utils;

/**
 * Provides utility functions for various tasks.
 */
final class Support
{
    /**
     * An array to store words.
     */
    public static array $words = [];

    /**
     * Converts the given text to a URL-friendly slug.
     *
     * @param string $text The input text to be slugified.
     * @param string $separator The separator character to use between words in the slug.
     *
     * @return string The slugified version of the input text.
     */
    public static function slugify(string $text, string $separator = '_'): string
    {
        $map = [
            'Á' => 'A', 'á' => 'a',
            'É' => 'E', 'é' => 'e',
            'Í' => 'I', 'í' => 'i',
            'Ó' => 'O', 'ó' => 'o',
            'Ö' => 'O', 'ö' => 'o',
            'Ő' => 'O', 'ő' => 'o',
            'Ú' => 'U', 'ú' => 'u',
            'Ü' => 'U', 'ü' => 'u',
            'Ű' => 'U', 'ű' => 'u',
        ];

        $text = strtr($text, $map);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9]+/u', $separator, $text);
        $text = trim($text, $separator);

        return $text;
    }

    /**
     * Converts the given word key to its corresponding text representation.
     *
     * @param string $key The word key to be converted.
     *
     * @return string The text representation of the word key.
     */
    public static function changeWordKeyToText(string $key): string
    {
        return self::$words[$key] ?? $key;
    }
}
