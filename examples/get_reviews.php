<?php
/**
 * This example requires a restaurant's ID, and lists some reviews.
 *
 * Usage: open a terminal and execute the script, for example using
 * `php list_menu.php <id>`.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Takeaway\Takeaway;
use Takeaway\Http\Requests\GetReviewsRequest;

// Parse arguments
if ($argc < 3) {
    echo 'Usage: php '.$argv[0].' <countryCode> <id>'.PHP_EOL;
    die(1);
}

$countryCode = $argv[1];
$restaurantId = $argv[2];

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

// Request reviews for restaurant
$req = new GetReviewsRequest($restaurantId, 1);
$reviews = $req->getData();

foreach ($reviews['reviews'] as $review) {
    echo 'Review by '.($review->name ?: 'Anonymous').' - Q: ' .$review->quality.' D: '.$review->delivery.PHP_EOL;

    if ($review->description) {
        echo $review->description.PHP_EOL;
    }

    if ($review->sunday) {
        echo '(This review was placed on a Sunday.)'.PHP_EOL;
    }

    echo PHP_EOL;
}
