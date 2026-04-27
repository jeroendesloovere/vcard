<?php

declare(strict_types=1);

namespace SEEC\VCard\Tests;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SEEC\VCard\Dto\CardData;
use SEEC\VCard\VCard;
use SEEC\VCard\VCardParser;

final class VCardParserTest extends TestCase
{
    private VCard $vCard;

    protected function setUp(): void
    {
        $this->vCard = new VCard();
    }

    public function test_it_will_throw_an_exception_when_wrong_index_is_requested(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $vCardParser = new VCardParser('');
        $vCardParser->getCardAtIndex(2);
    }

    public function test_it_can_correctly_transform_a_simple_vcard(): void
    {
        $this->vCard->addName('Desloovere', 'Jeroen');
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $this->assertSame($vCardParser->getCardAtIndex(0)->getFirstName(), 'Jeroen');
        $this->assertSame($vCardParser->getCardAtIndex(0)->getLastName(), 'Desloovere');
        $this->assertSame($vCardParser->getCardAtIndex(0)->getName(), 'Jeroen Desloovere');
    }

    public function test_it_can_retrieve_the_birthday_in_the_right_format(): void
    {
        $date = new DateTimeImmutable('01-01-2021');
        $this->vCard->addBirthday($date);
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $this->assertSame(
            $vCardParser->getCardAtIndex(0)->getBirthday()->format('Y-m-d H:i:s'),
            $date->format('Y-m-d H:i:s'),
        );
    }

    public function test_it_can_parse_addresses_correctly(): void
    {
        $this->vCard->addAddress(
            'Lorem Corp.',
            '(extended info)',
            '54th Ipsum Street',
            'PHPsville',
            'Guacamole',
            '01158',
            'Gitland',
        );

        $this->vCard->addAddress(
            'Jeroen Desloovere',
            '(extended info, again)',
            '25th Some Address',
            'Townsville',
            'Area 51',
            '045784',
            'Europe (is a country, right?)',
            ['WORK', 'PERSONAL'],
        );

        $this->vCard->addAddress(
            'Georges Desloovere',
            '(extended info, again, again)',
            '26th Some Address',
            'Townsville-South',
            'Area 51B',
            '04554',
            "Europe (no, it isn't)",
            ['WORK', 'PERSONAL'],
        );

        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getAddress();
        $this->assertSame($resolve['WORK;POSTAL'][0]->toArray(), [
            'name' => 'Lorem Corp.',
            'extended' => '(extended info)',
            'street' => '54th Ipsum Street',
            'city' => 'PHPsville',
            'region' => 'Guacamole',
            'zip' => '01158',
            'country' => 'Gitland',
        ]);

        $this->assertSame($resolve['WORK;PERSONAL'][0]->toArray(), [
            'name' => 'Jeroen Desloovere',
            'extended' => '(extended info, again)',
            'street' => '25th Some Address',
            'city' => 'Townsville',
            'region' => 'Area 51',
            'zip' => '045784',
            'country' => 'Europe (is a country, right?)',
        ]);

        $this->assertSame($resolve['WORK;PERSONAL'][1]->toArray(), [
            'name' => 'Georges Desloovere',
            'extended' => '(extended info, again, again)',
            'street' => '26th Some Address',
            'city' => 'Townsville-South',
            'region' => 'Area 51B',
            'zip' => '04554',
            'country' => "Europe (no, it isn't)",
        ]);
    }

    public function test_it_can_set_and_get_multiple_phone_numbers(): void
    {
        $this->vCard->addPhoneNumber('0984456123');
        $this->vCard->addPhoneNumber('2015123487', ['WORK']);
        $this->vCard->addPhoneNumber('4875446578', ['WORK']);
        $this->vCard->addPhoneNumber('9875445464', ['PREF', 'WORK', 'VOICE']);

        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getPhone();
        $this->assertSame($resolve['default'][0], '0984456123');
        $this->assertSame($resolve['WORK'][0], '2015123487');
        $this->assertSame($resolve['WORK'][1], '4875446578');
        $this->assertSame($resolve['PREF;WORK;VOICE'][0], '9875445464');
    }

    public function test_it_can_get_and_set_the_emails_correctly(): void
    {
        $this->vCard->addEmail('some@email.com');
        $this->vCard->addEmail('site@corp.net', ['WORK']);
        $this->vCard->addEmail('site.corp@corp.net', ['WORK']);
        $this->vCard->addEmail('support@info.info', ['PREF', 'WORK']);

        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getEmails();
        $this->assertSame($resolve['INTERNET'][0], 'some@email.com');
        $this->assertSame($resolve['INTERNET;WORK'][0], 'site@corp.net');
        $this->assertSame($resolve['INTERNET;WORK'][1], 'site.corp@corp.net');
        $this->assertSame($resolve['INTERNET;PREF;WORK'][0], 'support@info.info');
    }

    public function test_it_can_get_and_set_the_org_correctly(): void
    {
        $this->vCard->addCompany('Lorem Corp.');
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getOrganization();
        $this->assertSame($resolve, 'Lorem Corp.');
    }

    public function test_it_can_get_and_set_multiple_urls_correctly(): void
    {
        $this->vCard->addUrl('http://www.SEEC.be');
        $this->vCard->addUrl('http://home.example.com', 'HOME');
        $this->vCard->addUrl('http://work1.example.com', 'PREF;WORK');
        $this->vCard->addUrl('http://work2.example.com', 'PREF;WORK');

        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getUrls();
        $this->assertSame($resolve['default'][0], 'http://www.SEEC.be');
        $this->assertSame($resolve['HOME'][0], 'http://home.example.com');
        $this->assertSame($resolve['PREF;WORK'][0], 'http://work1.example.com');
        $this->assertSame($resolve['PREF;WORK'][1], 'http://work2.example.com');
    }

    public function test_it_can_set_and_get_the_cards_note_correctly(): void
    {
        $this->vCard->addNote('This is a testnote');
        $parser = new VCardParser($this->vCard->buildVCard());

        $vCard = new VCard();
        $vCard->addNote("This is a multiline note\nNew line content!\nLine 2");

        $parserMultiline = new VCardParser($vCard->buildVCard());

        $resolve = $parser->getCardAtIndex(0)->getNote();
        $this->assertSame($resolve, 'This is a testnote');

        $resolve = $parserMultiline->getCardAtIndex(0)->getNote();
        $this->assertSame($resolve, 'This is a multiline note' . \PHP_EOL . 'New line content!' . \PHP_EOL . 'Line 2');
    }

    public function test_it_can_set_and_get_categories_correctly(): void
    {
        $this->vCard->addCategories([
            'Category 1',
            'cat-2',
            'another long category!',
        ]);
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $resolve = $vCardParser->getCardAtIndex(0)->getCategories();
        $this->assertSame($resolve[0], 'Category 1');
        $this->assertSame($resolve[1], 'cat-2');
        $this->assertSame($resolve[2], 'another long category!');
    }

    public function test_it_can_set_and_get_titlecorrectly(): void
    {
        $this->vCard->addJobtitle('Ninja');
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $this->assertSame($vCardParser->getCardAtIndex(0)->getTitle(), 'Ninja');
    }

    public function test_it_can_set_and_get_raw_logo_correctly(): void
    {
        $image = __DIR__ . '/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $card = new VCard();
        $card->addLogo($image, true);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getRawLogo(), file_get_contents($image));

        $card = new VCard();
        $card->addLogo($image, false);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getLogo(), __DIR__ . '/image.jpg');

        $card = new VCard();
        $card->addLogo($imageUrl, false);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getLogo(), $imageUrl);
    }

    public function test_it_can_set_and_get_raw_photo_correctly(): void
    {
        $image = __DIR__ . '/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $card = new VCard();
        $card->addPhoto($image, true);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getRawPhoto(), file_get_contents($image));

        $card = new VCard();
        $card->addPhoto($image, false);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getPhoto(), __DIR__ . '/image.jpg');

        $card = new VCard();
        $card->addPhoto($imageUrl, false);

        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getPhoto(), $imageUrl);
    }

    public function test_it_can_handle_multiple_vcards_on_input_correctly(): void
    {
        $db = '';
        $card = new VCard();
        $card->addName('Desloovere', 'Jeroen');
        $db .= $card->buildVCard();

        $card2 = new VCard();
        $card2->addName('Lorem', 'Ipsum');
        $db .= $card2->buildVCard();

        $vCardParser = new VCardParser($db);
        $this->assertSame($vCardParser->getCardAtIndex(0)->getName(), 'Jeroen Desloovere');
        $this->assertSame($vCardParser->getCardAtIndex(1)->getName(), 'Ipsum Lorem');
    }

    public function test_it_can_iterate_over_multiple_cards_correctly(): void
    {
        $db = '';

        $card = new VCard();
        $card->addName('Desloovere', 'Jeroen');
        $db .= $card->buildVCard();

        $card2 = new VCard();
        $card2->addName('Lorem', 'Ipsum');
        $db .= $card2->buildVCard();

        $vCardParser = new VCardParser($db);
        foreach ($vCardParser as $i => $card) {
            $this->assertInstanceOf(CardData::class, $card);
            $this->assertSame(
                $card->getName(),
                $i === 0
                    ? 'Jeroen Desloovere'
                    : 'Ipsum Lorem',
            );
        }
    }

    public function test_it_can_load_vcard_from_file(): void
    {
        $vCardParser = VCardParser::parseFromFile(__DIR__ . '/example.vcf');
        $cards = $vCardParser->getCards();
        foreach ($cards as $card) {
            $this->assertInstanceOf(CardData::class, $card);
        }

        $this->assertSame($cards[0]->getFirstName(), 'Jeroen');
        $this->assertSame($cards[0]->getLastName(), 'Desloovere');
        $this->assertSame($cards[0]->getName(), 'Jeroen Desloovere');
        $this->assertSame($cards[0]->getUrls()['default'][0], 'https://www.schimmelmann.org');
        $this->assertSame($cards[0]->getEmails()['INTERNET'][0], 'site@example.com');
    }

    public function test_it_will_throw_an_exception_when_file_cannot_be_loaded(): void
    {
        $this->expectException(InvalidArgumentException::class);
        VCardParser::parseFromFile(__DIR__ . '/does-not-exist.vcf');
    }

    public function test_it_can_correctly_return_a_label(): void
    {
        $label = 'street, worktown, workpostcode Belgium';

        $this->vCard->addLabel($label, 'work');
        $vCardParser = new VCardParser($this->vCard->buildVCard());
        $this->assertSame($vCardParser->getCardAtIndex(0)->getLabel(), $label);
    }
}
