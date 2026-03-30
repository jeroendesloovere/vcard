<?php

namespace JeroenDesloovere\VCard\tests;

// required to load
require_once __DIR__ . '/../vendor/autoload.php';

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Behat\Transliterator\Transliterator;
use JeroenDesloovere\VCard\VCard;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the cocur/slugify-based filename generation in VCard produces
 * identical output to the previous behat/transliterator-based implementation
 * for a broad set of name inputs.
 *
 * The old pipeline (behat/transliterator) that was used in setFilename():
 *   1. implode array parts with separator
 *   2. trim / collapse whitespace
 *   3. strtolower(Transliterator::transliterate($value))
 *   4. Transliterator::urlize($value)
 *
 * The new pipeline (cocur/slugify) must produce byte-for-byte the same slug.
 */
class VCardFilenameParityTest extends TestCase
{
    /**
     * Replicates the setFilename() logic that existed before PR #234
     * (using behat/transliterator).
     *
     * @param array<int,string> $nameParts Non-empty name parts in the same
     *                                     order as addName() passes them to
     *                                     setFilename(): prefix, firstName,
     *                                     additional, lastName, suffix.
     */
    private function buildFilenameWithBehat(array $nameParts, string $separator = '_'): string
    {
        $value = implode($separator, $nameParts);
        $value = trim($value, $separator);
        $value = preg_replace('/\s+/', $separator, $value);

        if (empty($value)) {
            return '';
        }

        $value = strtolower(Transliterator::transliterate($value));
        $value = Transliterator::urlize($value);

        return $value;
    }

    /**
     * @dataProvider namePartsProvider
     *
     * @param string $lastName
     * @param string $firstName
     * @param string $additional
     * @param string $prefix
     * @param string $suffix
     */
    public function testFilenameMatchesBehatTransliterator(
        string $lastName,
        string $firstName,
        string $additional = '',
        string $prefix = '',
        string $suffix = ''
    ): void {
        // Build the expected filename using the old behat/transliterator pipeline.
        $nameParts = array_filter([$prefix, $firstName, $additional, $lastName, $suffix]);
        $expected = $this->buildFilenameWithBehat(array_values($nameParts));

        // Build the actual filename through VCard (cocur/slugify-based pipeline).
        $vcard = new VCard();
        $vcard->addName($lastName, $firstName, $additional, $prefix, $suffix);
        $actual = $vcard->getFilename();

        $this->assertSame(
            $expected,
            $actual,
            sprintf(
                'Filename mismatch for name parts: prefix="%s" firstName="%s" additional="%s" lastName="%s" suffix="%s"',
                $prefix,
                $firstName,
                $additional,
                $lastName,
                $suffix
            )
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function namePartsProvider(): array
    {
        // Each entry: [lastName, firstName, additional, prefix, suffix]
        // (matching addName() parameter order)
        return [
            // ── Existing test cases ───────────────────────────────────────
            'simple ASCII'                  => ['Desloovere', 'Jeroen'],
            'full blown name with &'        => ['Desloovere', 'Jeroen', '&', 'Mister', 'Junior'],
            'Turkish upper-case umlauts'    => ['ÖZSÜT', 'Ali'],
            'French accents'                => ['Jéroèn', 'Garçon'],

            // ── Additional parity cases ───────────────────────────────────
            'plain ASCII'                   => ['Smith', 'John'],
            'hyphenated last name'          => ['Smith-Jones', 'Mary'],
            'Spanish accents'               => ['María', 'José'],
            'German lower-case umlauts'     => ['Müller', 'Hans'],
            'Swedish Å / Ö'                 => ['Ångström', 'Lars'],
            'Danish Ø'                      => ['Sørensen', 'Anders'],
            'Norwegian Ø + Æ'               => ['Bjørnsen', 'Ævar'],
            'Irish apostrophe'              => ["O'Brien", 'Patrick'],
            'Cyrillic characters'           => ['Иванов', 'Иван'],
            'Greek characters'              => ['Αλέξανδρος', 'Νίκος'],
            'Czech diacritics'              => ['Dvořák', 'Jan'],
            'Polish diacritics'             => ['Wałęsa', 'Lech'],
            'mixed accents and plain'       => ['García', 'Carlos'],
            'prefix only'                   => ['Smith', '', '', 'Dr'],
            'suffix only'                   => ['Smith', 'John', '', '', 'Jr'],
            'all parts'                     => ['Doe', 'Jane', 'M', 'Ms', 'PhD'],
        ];
    }
}
