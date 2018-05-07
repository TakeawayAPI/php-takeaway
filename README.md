# PHP Takeaway.com API
Unofficial PHP implementation of the [Takeaway.com](https://takeaway.com) API.

## Installation
While this package has not been submitted to Packagist yet, you can install it
manually by including this repository as a source of packages. Add the following
to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:TakeawayAPI/php-takeaway.git"
    }
]
```

Then, install this package:

```json
"require": {
    "woutervdbrink/takeaway": "dev-master"
}
```

## Documentation
Documentation can be found [here](https://github.com/TakeawayAPI/php-takeaway/wiki).

## Giving feedback
Please report any issues with **implemented functionality** using the issue
tracker. I am aware of unimplemented features and will create issues for them.

## Quickstart
You can check out [the examples](examples/) or continue reading below.

This package provides both a high-level and a low-level implementation of the
Takeaway API. The low-level implementation allows you to directly call methods
on the Takeaway API and parse the results yourself, and works like this:

```php
use Takeaway\Http\Request;

$req = new Request('getcountriesdata', []);

var_dump($req->execute());
```

`execute` returns the parsed XML returned by the server, as a `SimpleXMLElement`.

Most methods available in the API are already abstracted as `Request` objects.
The example above could also be implemented like so:

```php
use Takeaway\Http\Requests\GetCountriesRequest;

$req = new GetCountriesRequest();

var_dump($req->execute());
```

This is especially helpful when dealing with complicated requests, which may have
many parameters.

Finally, you may skip the HTTP layer entirely and work on the highest level
offered by this library, which is recommended. The library deals with errors,
caching, lazy loading and loading of related models whenever you need them.

```php
use Takeaway\Takeaway;

$thuisbezorgd = Takeaway::getCountryByCode(1); // 1 is The Netherlands

foreach ($thuisbezorgd->restaurants as $restaurant) {
    echo $restaurant->name.($restaurant->open ? ' (open)' : ' (closed)').PHP_EOL;

    foreach ($restaurant->categories as $category) {
        echo ' - '.$category->name.PHP_EOL;

        foreach ($category->products as $product) {
            echo '  - '.$product->name.' ('.$product->price.')'.PHP_EOL;
        }
    }
}
```

## Development
1. Fork the project
2. Make some changes
3. Create a pull request

All code should comply with [PSR-2](https://www.php-fig.org/psr/psr-2/).
Furthermore, when making a pull request, make sure that your code has
[PHPDoc](https://docs.phpdoc.org/references/phpdoc/index.html) where possible.
