<?php
/**
 * This example finds a restaurant, and then lists its menu.
 *
 * Usage: open a terminal and execute the script, for example using
 * `php list_menu.php`.
 */

require_once __DIR__.'../vendor/autoload.php';

use Takeaway\Takeaway;

// Thuisbezorgd is in country 1 (The Netherlands).
$thuisbezorgd = Takeaway::getCountryByCode(1);

// We find all restaurants at a given postal code, latitude and longitude.
$restaurants = $thuisbezorgd->getRestaurants('7500', '52', '6');

// We then select the first restaurant.
$restaurant = $restaurants[0];

echo $restaurant->name . PHP_EOL;

// Data like the menu card and other data is lazy loaded whenever it is needed,
// preventing useless requests from being made.
foreach ($restaurant->categories as $category) {
    echo '  ' . $category->name . PHP_EOL;

    foreach ($category->products as $product) {
        echo '    - ' . $product->name . PHP_EOL;
    }
}
