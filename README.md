# VCard PHP class

This VCard PHP class can generate a vCard with some data. When using an iOS device it will export as a .ics file because iOS devices don't support the default .vcf files.

## Installing

### Using Composer

When using [Composer](https://getcomposer.org) you can always load in the latest version.

``` json
{
    "require": {
        "jeroendesloovere/vcard": "1.1.*"
    }
}
```
Check [in Packagist](https://packagist.org/packages/jeroendesloovere/vcard).

### Usage example

``` php
use JeroenDesloovere\VCard\VCard;

// define vcard
$vcard = new VCard();

// define variables
$firstname = 'Jeroen';
$lastname = 'Desloovere';

// add personal data
$vcard->addName($lastname, $firstname);

// add work data
$vcard->addCompany('Siesqo');
$vcard->addJobtitle('Web Developer');
$vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhoneNumber(1234121212, 'PREF;WORK');
$vcard->addPhoneNumber(123456789, 'WORK');
$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
$vcard->addURL('http://www.siesqo.be');

// return vcard as a download
return $vcard->download();
```

Check [the VCard class source](./src/VCard.php) or [view examples](./examples/example.php).


## Documentation

The class is well documented inline. If you use a decent IDE you'll see that each method is documented with PHPDoc.


## Contributing

It would be great if you could help us improve this class. GitHub does a great job in managing collaboration by providing different tools, the only thing you need is a [GitHub](http://github.com) login.

* Use **Pull requests** to add or update code
* **Issues** for bug reporting or code discussions
* Or regarding documentation and how-to's, check out **Wiki**
More info on how to work with GitHub on help.github.com.


## License

The module is licensed under [MIT](./LICENSE.md). In short, this license allows you to do everything as long as the copyright statement stays present.