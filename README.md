# VCard PHP library

This is a fork of https://github.com/jeroendesloovere/vcard which fixes the following PHP 8.0 notice:

```
Required parameter $element follows optional parameter $include
```

## Usage

### Adjust your composer.json

```bash
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/lordsimal/vcard"
    }
  ],
  "require": {
    "jeroendesloovere/vcard": "dev-master",
  }
```

See the rest of the doc in the base repo https://github.com/jeroendesloovere/vcard