<?php
/**
 * This example requires a restaurant's ID, and lists some reviews.
 *
 * Usage: open a terminal and execute the script, for example using
 * `php list_menu.php <id>`.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Takeaway\Http\Requests\GetReviewsRequest;

if ($argc < 2) {
    echo 'Usage: php '.$argv[0].' <id>'.PHP_EOL;
    die(1);
}

$req = new GetReviewsRequest($argv[1], 1);

$reviews = $req->getData();

foreach ($reviews['reviews'] as $review) {
    echo 'Review by '.($review->name ?: 'Anonymous').' - Q: ' .$review->quality.' D: '.$review->delivery.PHP_EOL;

    if ($review->description) {
        echo $review->description.PHP_EOL;
    }

    if ($review->sunday) {
        echo 'This review was placed on a Sunday.'.PHP_EOL;
    }

    echo PHP_EOL;
}
