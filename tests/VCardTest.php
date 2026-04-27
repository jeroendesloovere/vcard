<?php

declare(strict_types=1);

namespace SEEC\VCard\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use SEEC\VCard\VCard;

final class VCardTest extends TestCase
{
    private VCard $vCard;

    private string $firstName;

    private string $lastName;

    private string $additional;

    private string $prefix;

    private string $suffix;

    private string $emailAddress1;

    private string $emailAddress2;

    private string $firstName2;

    private string $lastName2;

    private string $firstName3;

    private string $lastName3;

    public static function emailDataProvider(): array
    {
        return [
            [['john@doe.com']],
            [['john@doe.com', 'WORK' => 'john@work.com']],
            [['WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
            [['PREF;WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
        ];
    }

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Berlin');
        $this->vCard = new VCard();
        $this->firstName = 'Jeroen';
        $this->lastName = 'Desloovere';
        $this->additional = '&';
        $this->prefix = 'Mister';
        $this->suffix = 'Junior';
        $this->emailAddress1 = '';
        $this->emailAddress2 = '';
        $this->firstName2 = 'Ali';
        $this->lastName2 = 'ÖZSÜT';
        $this->firstName3 = 'Garçon';
        $this->lastName3 = 'Jéroèn';

        $this->vCard->addEmail($this->emailAddress1);
        $this->vCard->addEmail($this->emailAddress2);

        $this->vCard->addAddress(
            '',
            '88th Floor',
            '555 East Flours Street',
            'Los Angeles',
            'CA',
            '55555',
            'USA',
        );
    }

    public function test_it_can_add_an_address(): void
    {
        $output = $this->vCard->getOutput();
        $this->assertStringContainsString(
            'ADR;WORK;POSTAL;CHARSET=utf-8:;88th Floor;555 East Flours Street;Los Angele',
            $output,
        );

        $this->assertStringNotContainsString(
            'ADR;WORK;POSTAL;CHARSET=utf-8:;88th Floor;555 East Flours Street;Los Angeles;CA;55555;',
            $output,
        );
    }

    public function test_it_cannot_add_a_remote_text_file_as_photo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addPhoto('https://raw.githubusercontent.com/SEEC/vcard/master/tests/empty.jpg');
    }

    public function test_it_cannot_add_an_empty_picture_as_photo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addPhotoContent('');
    }

    public function test_it_cannot_add_a_remote_text_file_as_logo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addLogoContent('');
    }

    public function test_it_cannot_add_an_empty_picture_as_logo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addPhoto(__DIR__ . '/emptyfile');
    }

    public function test_it_cannot_add_a_empty_photo_as_logo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addLogo(__DIR__ . '/emptyfile');
    }

    public function test_it_cannot_add_a_empty_photo_as_photo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addPhoto(__DIR__ . '/wrongfile', true);
    }

    public function test_it_cannot_add_empty_image_as_logo(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Returned data is not an image.');
        $this->vCard->addLogo(__DIR__ . '/wrongfile');
    }

    public function test_it_will_correctly_return_the_charset(): void
    {
        $charset = 'ISO-8859-1';
        $this->vCard->setCharset($charset);
        $this->assertSame($charset, $this->vCard->getCharset());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('emailDataProvider')]
    public function test_it_can_set_emails_as_expected(array $emails): void
    {
        foreach ($emails as $key => $email) {
            $this->vCard->addEmail(
                $email,
                is_string($key)
                ? explode(';', $key)
                : [],
            );
        }

        $output = $this->vCard->getOutput();
        foreach ($emails as $key => $email) {
            if (is_string($key)) {
                $this->assertStringContainsString(sprintf('EMAIL;INTERNET;%s:%s', $key, $email), $output);
            } else {
                $this->assertStringContainsString(sprintf('EMAIL;INTERNET:%s', $email), $output);
            }
        }
    }

    public function test_it_can_set_and_transform_names_correctly(): void
    {
        $this->vCard->addName(
            $this->lastName,
            $this->firstName,
        );

        $this->assertEquals('jeroen-desloovere', $this->vCard->getFilename());
    }

    public function test_it_can_correctly_evaluate_full_name(): void
    {
        $this->vCard->addName(
            $this->lastName,
            $this->firstName,
            $this->additional,
            $this->prefix,
            $this->suffix,
        );

        $this->assertEquals('mister-jeroen-desloovere-junior', $this->vCard->getFilename());
    }

    public function test_it_can_evaluate_special_characters_properly(): void
    {
        $this->vCard->addName(
            $this->lastName2,
            $this->firstName2,
        );

        $this->assertEquals('ali-ozsut', $this->vCard->getFilename());
    }

    public function test_it_can_evaluate_special_characters_properly_second(): void
    {
        $this->vCard->addName(
            $this->lastName3,
            $this->firstName3,
        );

        $this->assertEquals('garcon-jeroen', $this->vCard->getFilename());
    }

    public function test_property_count_and_contents(): void
    {
        $this->assertCount(3, $this->vCard->getProperties());
        $this->vCard->addLabel('My label');
        $this->vCard->addLabel('My work label', 'WORK');

        $resolve = $this->vCard->getOutput();
        $this->assertStringContainsString('LABEL;CHARSET=utf-8:My label', $resolve);
        $this->assertStringContainsString('LABEL;WORK;CHARSET=utf-8:My work label', $resolve);
    }

    public function test_it_can_correctly_invoke_ChunkSplitUnicode(): void
    {
        $reflectionClass = new \ReflectionClass(\SEEC\VCard\VCard::class);
        $reflectionMethod = $reflectionClass->getMethod('chunkSplitUnicode');
        $reflectionMethod->setAccessible(true);

        $ascii_input = 'Lorem ipsum dolor sit amet,';
        $ascii_output = $reflectionMethod->invokeArgs(new VCard(), [$ascii_input, 10, '|']);
        $unicode_input = 'Τη γλώσσα μου έδωσαν ελληνική το σπίτι φτωχικό στις αμμουδιές του Ομήρου.';
        $unicode_output = $reflectionMethod->invokeArgs(new VCard(), [$unicode_input, 10, '|']);

        $this->assertEquals(
            'Lorem ipsu|m dolor si|t amet,|',
            $ascii_output,
        );
        $this->assertEquals(
            'Τη γλώσσα |μου έδωσαν| ελληνική |το σπίτι φ|τωχικό στι|ς αμμουδιέ|ς του Ομήρ|ου.|',
            $unicode_output,
        );
    }
}
