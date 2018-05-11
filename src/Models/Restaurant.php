<?php

namespace Takeaway\Models;

use Takeaway\Model;
use Takeaway\Http\Requests\GetRestaurantRequest;

/**
 * A restaurant.
 *
 * @property string $id Unique identifier of the restaurant.
 * @property string $name Name of the restaurant.
 * @property string $branch Branch of the restaurant.
 * @property boolean $open Whether or not the restaurant is currently open.
 * @property string $logo URL to the logo of the restaurant.
 * @property boolean $new Whether or not the restaurant is new.
 * @property boolean $tip Whether or not the restaurant is a tip.
 * @property boolean $hasDiscounts Whether or not the restaurant has discounts.
 * @property boolean $foodTracker
 *  Whether or not the restaurant has a food tracker.
 * @property string $estimatedDeliveryTime
 *  Estimated delivery time of the restaurant.
 * @property string $description Description of the restaurant.
 * @property string $slogan Slogan of the restaurant.
 * @property string $street Street where the restaurant is located.
 * @property string $postalCode Postal code of the restaurant.
 * @property string $city City where the restaurant is located.
 * @property float $latitude Latitude of the restaurant.
 * @property float $longitude Longitude of the restaurant.
 * @property string $grade Grade of the restaurant. It is unsure what the exact
 *                         meaning of this value is.
 * @property integer $numReviews Amount of reviews of the restaurant.
 * @property \Takeaway\Models\DeliverArea[] $deliverAreas
 *  Areas where the restaurant delivers food.
 * @property integer $oderMethods Available methods of ordering.
 * @property integer $mealPrepTime The current meal preparation time, expressed
 *                                 in minutes.
 * @property float $minimumAmount The minimum order amount.
 * @property \Takeaway\Models\DeliverCosts[] $deliverCosts
 *  Delivery costs of the restaurant per time slot.
 * @property \Takeaway\Models\PaymentMethod[] $paymentMethods
 *  Available payment methods at the restaurant.
 * @property \Takeaway\Models\Category[] $categories
 *  Available food categories in the menu of the restaurant.
 * @property \Takeaway\Models\Discount[] $discounts
 *  Discounts the restaurant currently offers.
 */
class Restaurant extends Model
{
    public const CLOSED = '0';
    public const OPEN = '1';
    public const PREORDER = '2';

    public const SORT_ALPHABET = 9;
    public const SORT_AVG_PRICE = 6;
    public const SORT_DELIVERY = 7;
    public const SORT_DISCOUNTS = 10;
    public const SORT_DISTANCE = 4;
    public const SORT_MIN_AMOUNT = 8;
    public const SORT_NEWEST = 2;
    public const SORT_POPULAR = 5;
    public const SORT_RATED = 3;
    public const SORT_RELEVANT = 1;

    protected function lazyLoad()
    {
        $this->fill((new GetRestaurantRequest(
            $this->id,
            $this->extra['postalCode'],
            $this->extra['countryCode'],
            $this->extra['latitude'],
            $this->extra['longitude'],
            $this->extra['language']
        ))->getData());
    }
}
