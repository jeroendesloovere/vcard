<?php

declare(strict_types=1);

namespace JeroenDesloovere\VCard\Tests;

use DateTimeImmutable;
use InvalidArgumentException;
use JeroenDesloovere\VCard\Dto\CardData;
use JeroenDesloovere\VCard\VCard;
use JeroenDesloovere\VCard\VCardParser;
use PHPUnit\Framework\TestCase;

final class VCardParserTest extends TestCase
{
    private VCard $vcard;

    public function setUp(): void
    {
        $this->vcard = new VCard();
    }

    public function test_it_will_throw_an_exception_when_wrong_index_is_requested(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $parser = new VCardParser('');
        $parser->getCardAtIndex(2);
    }

    public function test_it_can_correctly_transform_a_simple_vcard(): void
    {
        $this->vcard->addName('Desloovere', 'Jeroen');
        $parser = new VCardParser($this->vcard->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getFirstName(), 'Jeroen');
        $this->assertSame($parser->getCardAtIndex(0)->getLastName(), 'Desloovere');
        $this->assertSame($parser->getCardAtIndex(0)->getName(), 'Jeroen Desloovere');
    }

    public function test_it_can_retrieve_the_birthday_in_the_right_format(): void
    {
        $date = new DateTimeImmutable('01-01-2021');
        $this->vcard->addBirthday($date);
        $parser = new VCardParser($this->vcard->buildVCard());
        $this->assertSame(
            $parser->getCardAtIndex(0)->getBirthday()->format('Y-m-d H:i:s'),
            $date->format('Y-m-d H:i:s')
        );
    }

    public function test_it_can_parse_addresses_correctly(): void
    {
        $this->vcard->addAddress(
            'Lorem Corp.',
            '(extended info)',
            '54th Ipsum Street',
            'PHPsville',
            'Guacamole',
            '01158',
            'Gitland',
        );

        $this->vcard->addAddress(
            'Jeroen Desloovere',
            '(extended info, again)',
            '25th Some Address',
            'Townsville',
            'Area 51',
            '045784',
            'Europe (is a country, right?)',
            ['WORK', 'PERSONAL'],
        );

        $this->vcard->addAddress(
            'Georges Desloovere',
            '(extended info, again, again)',
            '26th Some Address',
            'Townsville-South',
            'Area 51B',
            '04554',
            "Europe (no, it isn't)",
            ['WORK', 'PERSONAL'],
        );

        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getAddress();
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
        $this->vcard->addPhoneNumber('0984456123');
        $this->vcard->addPhoneNumber('2015123487', ['WORK']);
        $this->vcard->addPhoneNumber('4875446578', ['WORK']);
        $this->vcard->addPhoneNumber('9875445464', ['PREF', 'WORK', 'VOICE']);

        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getPhone();
        $this->assertSame($resolve['default'][0], '0984456123');
        $this->assertSame($resolve['WORK'][0], '2015123487');
        $this->assertSame($resolve['WORK'][1], '4875446578');
        $this->assertSame($resolve['PREF;WORK;VOICE'][0], '9875445464');
    }

    public function test_it_can_get_and_set_the_emails_correctly(): void
    {
        $this->vcard->addEmail('some@email.com');
        $this->vcard->addEmail('site@corp.net', ['WORK']);
        $this->vcard->addEmail('site.corp@corp.net', ['WORK']);
        $this->vcard->addEmail('support@info.info', ['PREF', 'WORK']);
        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getEmails();
        $this->assertSame($resolve['INTERNET'][0], 'some@email.com');
        $this->assertSame($resolve['INTERNET;WORK'][0], 'site@corp.net');
        $this->assertSame($resolve['INTERNET;WORK'][1], 'site.corp@corp.net');
        $this->assertSame($resolve['INTERNET;PREF;WORK'][0], 'support@info.info');
    }

    public function test_it_can_get_and_set_the_org_correctly(): void
    {
        $this->vcard->addCompany('Lorem Corp.');
        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getOrganization();
        $this->assertSame($resolve, 'Lorem Corp.');
    }

    public function test_it_can_get_and_set_multiple_urls_correctly(): void
    {
        $this->vcard->addUrl('http://www.jeroendesloovere.be');
        $this->vcard->addUrl('http://home.example.com', 'HOME');
        $this->vcard->addUrl('http://work1.example.com', 'PREF;WORK');
        $this->vcard->addUrl('http://work2.example.com', 'PREF;WORK');
        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getUrls();
        $this->assertSame($resolve['default'][0], 'http://www.jeroendesloovere.be');
        $this->assertSame($resolve['HOME'][0], 'http://home.example.com');
        $this->assertSame($resolve['PREF;WORK'][0], 'http://work1.example.com');
        $this->assertSame($resolve['PREF;WORK'][1], 'http://work2.example.com');
    }

    public function test_it_can_set_and_get_the_cards_note_correctly(): void
    {
        $this->vcard->addNote('This is a testnote');
        $parser = new VCardParser($this->vcard->buildVCard());

        $vcardMultiline = new VCard();
        $vcardMultiline->addNote("This is a multiline note\nNew line content!\nLine 2");
        $parserMultiline = new VCardParser($vcardMultiline->buildVCard());

        $resolve = $parser->getCardAtIndex(0)->getNote();
        $this->assertSame($resolve, 'This is a testnote');

        $resolve = $parserMultiline->getCardAtIndex(0)->getNote();
        $this->assertSame($resolve, 'This is a multiline note'.\PHP_EOL.'New line content!'.\PHP_EOL.'Line 2');
    }

    public function test_it_can_set_and_get_categories_correctly(): void
    {
        $this->vcard->addCategories([
            'Category 1',
            'cat-2',
            'another long category!',
        ]);
        $parser = new VCardParser($this->vcard->buildVCard());
        $resolve = $parser->getCardAtIndex(0)->getCategories();
        $this->assertSame($resolve[0], 'Category 1');
        $this->assertSame($resolve[1], 'cat-2');
        $this->assertSame($resolve[2], 'another long category!');
    }

    public function test_it_can_set_and_get_titlecorrectly(): void
    {
        $this->vcard->addJobtitle('Ninja');
        $parser = new VCardParser($this->vcard->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getTitle(), 'Ninja');
    }

    public function test_it_can_set_and_get_raw_logo_correctly(): void
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $card = new VCard();
        $card->addLogo($image, true);
        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getRawLogo(), file_get_contents($image));

        $card = new VCard();
        $card->addLogo($image, false);
        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getLogo(), __DIR__.'/image.jpg');

        $card = new VCard();
        $card->addLogo($imageUrl, false);
        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getLogo(), $imageUrl);
    }

    public function test_it_can_set_and_get_raw_photo_correctly(): void
    {
        $image = __DIR__.'/image.jpg';
        $imageUrl = 'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg';

        $card = new VCard();
        $card->addPhoto($image, true);
        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getRawPhoto(), file_get_contents($image));

        $card = new VCard();
        $card->addPhoto($image, false);
        $parser = new VCardParser($card->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getPhoto(), __DIR__.'/image.jpg');

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

        $parser = new VCardParser($db);
        $this->assertSame($parser->getCardAtIndex(0)->getName(), 'Jeroen Desloovere');
        $this->assertSame($parser->getCardAtIndex(1)->getName(), 'Ipsum Lorem');
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

        $parser = new VCardParser($db);
        foreach ($parser as $i => $card) {
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
        $parser = VCardParser::parseFromFile(__DIR__.'/example.vcf');
        $cards = $parser->getCards();
        foreach ($cards as $card) {
            $this->assertInstanceOf(CardData::class, $card);
        }

        $this->assertSame($cards[0]->getFirstName(), 'Jeroen');
        $this->assertSame($cards[0]->getLastName(), 'Desloovere');
        $this->assertSame($cards[0]->getName(), 'Jeroen Desloovere');
        $this->assertSame($cards[0]->getUrls()['default'][0], 'http://www.jeroendesloovere.be');
        $this->assertSame($cards[0]->getEmails()['INTERNET'][0], 'site@example.com');
    }

    public function test_it_will_throw_an_exception_when_file_cannot_be_loaded(): void
    {
        $this->expectException(InvalidArgumentException::class);
        VCardParser::parseFromFile(__DIR__.'/does-not-exist.vcf');
    }

    public function test_it_can_correctly_return_a_label(): void
    {
        $label = 'street, worktown, workpostcode Belgium';

        $this->vcard->addLabel($label, 'work');
        $parser = new VCardParser($this->vcard->buildVCard());
        $this->assertSame($parser->getCardAtIndex(0)->getLabel(), $label);
    }
}
