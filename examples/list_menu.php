<?php
/**
 * This example finds a restaurant, and then lists its menu.
 *
 * Usage: open a terminal and execute the script, for example using
 * `php list_menu.php`.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Takeaway\Takeaway;

// Parse arguments
if ($argc < 3) {
    echo 'Usage: php ' . $argv[0] . ' <countryCode> <postalCode> [<lat> <lon>]' . PHP_EOL;
} else if ($argc > 3 && $argc < 5) {
    echo 'Usage: php ' . $argv[0] . ' <countryCode> <postalCode> [<lat> <lon>]' . PHP_EOL;
    die(1);
}

$countryCode = $argv[1];
$postalCode = $argv[2];
$lat = $argc >= 4 ? $argv[3] : '';
$lon = $argc >= 4 ? $argv[4] : '';

// Set base URL
Takeaway::setConfigValue(Takeaway::CFG_BASE_URL, 'https://' . $countryCode . '.citymeal.com/android/android.php');

// Find country by given locale code
$country = Takeaway::getCountryByLocale($countryCode);
if (!$country) {
    echo 'Unknown country: '.$countryCode.PHP_EOL;
    die(1);
}

// Update language
Takeaway::setConfigValue(Takeaway::CFG_LANGUAGE, $country->languages[0]);

// We find all restaurants at a given postal code, latitude and longitude.
$restaurants = $country->getRestaurants($postalCode, $lat, $lon);

// We then select the first restaurant.
$restaurant = $restaurants[0];

echo $restaurant->id . ' - ' . $restaurant->name . PHP_EOL;

// Data like the menu card and other data is lazy loaded whenever it is needed,
// preventing useless requests from being made.
foreach ($restaurant->categories as $category) {
    echo '  ' . $category->name . PHP_EOL;

    foreach ($category->products as $product) {
        echo '    - ' . $product->name . PHP_EOL;
    }
}
