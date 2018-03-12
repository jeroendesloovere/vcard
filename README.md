# [WIP] VCard library

<!--[![Latest Stable Version](http://img.shields.io/packagist/v/jeroendesloovere/vcard.svg)](https://packagist.org/packages/jeroendesloovere/vcard)-->
[![License](http://img.shields.io/badge/license-MIT-lightgrey.svg)](https://github.com/jeroendesloovere/vcard/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/jeroendesloovere/vcard.svg?branch=new-version)](https://travis-ci.org/jeroendesloovere/vcard)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeroendesloovere/vcard/badges/quality-score.png?b=new-version)](https://scrutinizer-ci.com/g/jeroendesloovere/vcard/?branch=new-version)

> This VCard PHP class can generate a vCard version 4.0. .vcf file with one or more vCards in it. Parsing is also possible. OOP is our goal-focus, so every property has its own class.

Documentation about vCard 4.0:
* [vCard 4.0 specification: RFC6350](https://tools.ietf.org/html/rfc6350)
* [vCard 4.0 versus vCard 3.0](https://devguide.calconnect.org/vCard/vcard-4/)

## Examples

Since this is a WIP, we refer to the [test class](tests/VCardTest.php) to view multiple examples.

## Properties

### Identification Properties:
* [x] [FN = Full name](./src/Property/FullName.php) - The full name of the object (as a single string). This is the only mandatory property.
* [x] [N = Name](./src/Property/Name.php) - The name of the object represented in structured parts
* [x] [NICKNAME](./src/Property/Nickname.php) - A nickname for the object
* [ ] PHOTO
* [x] [BDAY](./src/Property/Birthdate.php) - Birth date of the object. Should only apply to Individual
* [x] [ANNIVERSARY](./src/Property/Anniversary.php) - Should only apply to Individual
* [x] [GENDER](./src/Property/Gender.php) - Should only apply to Individual

### Delivery Addressing Properties:
* [x] [ADDRESS](./src/Property/Address.php) - The address of the object represented in structured parts

### Communications Properties:
* [ ] TEL - The telephone number as a tel URI
* [x] [EMAIL](./src/Property/Email.php) - The email address as a mailto URI
* [ ] IMPP - The IMPP instant messaging contact information
* [ ] LANG - The language of the object

### Geographical Properties:
* [ ] TZ - The timezone of the object
* [ ] GEO - The geographical coordinates of the object (geo URI)

### Organizational Properties:
* [x] [TITLE](./src/Property/Title.php) - The title of the object
* [ ] ROLE - The role of the object
* [ ] LOGO - The logo of the object (data URI)
* [ ] ORG - The organisation related to the object
* [ ] ORGUNIT - The organisational unit related to the object
* [ ] MEMBER - Can only be used for Group Kind objects. Must point to other Individual or Organization objects.
* [ ] RELATED - Link to related objects.

### Explanatory Properties:
* [ ] CATEGORIES - The categories of the object
* [x] [NOTE](./src/Property/Note.php) - Notes about the object
* [ ] PRODID - The identifier of the product that created the vCard object
* [X] [REV](./src/Property/Parameter/Revision.php) - The revision datetime of the vCard object
* [ ] SOUND - Audio related to the object (data URI)
* [ ] UID - A unique identifier for the object
* [ ] CLIENTPIDMAP - Not required
* [ ] URL - Any URL related to the object
* [X] [VERSION](./src/Property/Parameter/Version.php) - Not required (namespace will capture this)

### Security Properties:
* [ ] KEY - The security key of the object

### Calendar Properties:
* [ ] FBURL - Calendar Busy Time of the object
* [ ] CALADURI - Calendar Request of the object
* [ ] CALURI - Calendar Link of the object

## Documentation

The class is well documented inline. If you use a decent IDE you'll see that each method is documented with PHPDoc.

## Contributing

Contributions are **welcome** and will be fully **credited**.

### Pull Requests

> To add or update code

- **Coding Syntax** - Please keep the code syntax consistent with the rest of the package.
- **Add unit tests!** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behavior** - Make sure the README and any other relevant documentation are kept up-to-date.
- **Consider our release cycle** - We try to follow [semver](http://semver.org/). Randomly breaking public APIs is not an option.
- **Create topic branches** - Don't ask us to pull from your master branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please squash them before submitting.

### Issues

> For bug reporting or code discussions.

More info on how to work with GitHub on help.github.com.

### Coding Syntax

We use [squizlabs/php_codesniffer](https://packagist.org/packages/squizlabs/php_codesniffer) to maintain the code standards.
Type the following to execute them:
```bash
# To view the code errors
vendor/bin/phpcs --standard=psr2 --extensions=php --warning-severity=0 --report=full "src"

# OR to fix the code errors
vendor/bin/phpcbf --standard=psr2 --extensions=php --warning-severity=0 --report=full "src"
```
> [Read documentation about the code standards](https://github.com/squizlabs/PHP_CodeSniffer/wiki)

### Unit Tests

We have build in tests, type the following to execute them:
```bash
vendor/bin/phpunit tests
```

## Credits

- [Jeroen Desloovere](https://github.com/jeroendesloovere)
- [All Contributors](https://github.com/jeroendesloovere/vcard/contributors)

## License

The module is licensed under [MIT](./LICENSE.md). In short, this license allows you to do everything as long as the copyright statement stays present.
