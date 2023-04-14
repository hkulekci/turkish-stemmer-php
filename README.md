# Turkish Stemmer for PHP

PHP port of https://github.com/otuncelli/turkish-stemmer-python


### Usage

```php
$stemmer = new \TurkishStemmer\Stemmer();
var_dump($stemmer->stem('okuldan'));
# string(4) "okul"
```