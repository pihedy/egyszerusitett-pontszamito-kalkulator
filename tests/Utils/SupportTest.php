<?php declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \App\Utils\Support;

final class SupportTest extends TestCase
{
    public function testSlugifyWithDefaultSeparator(): void
    {
        $this->assertSame('hello_world', Support::slugify('Hello World'));
    }

    public function testSlugifyWithCustomSeparator(): void
    {
        $this->assertSame('hello-world', Support::slugify('Hello World', '-'));
    }

    public function testSlugifyWithSpecialCharacters(): void
    {
        $this->assertSame('cest-la-vie', Support::slugify('Cest la vie!', '-'));
    }

    public function testSlugifyWithMultipleSpaces(): void
    {
        $this->assertSame('hello_world', Support::slugify('Hello    World'));
    }

    public function testSlugifyWithLeadingAndTrailingSpaces(): void
    {
        $this->assertSame('hello_world', Support::slugify('  Hello World  '));
    }

    public function testSlugifyWithEmptyString(): void
    {
        $this->assertSame('', Support::slugify(''));
    }

    public function testSlugifyWithAccentedCharacters(): void
    {
        $this->assertSame('aeroport', Support::slugify('Aéroport'));
    }

    public function testSlugifyWithNumericInput(): void
    {
        $this->assertSame('123-abc', Support::slugify('123 abc', '-'));
    }
}
