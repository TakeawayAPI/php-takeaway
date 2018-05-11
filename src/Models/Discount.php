<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A discount offered by a restaurant.
 *
 * @property string $type Type of the discount.
 * @property string $name Name of the discount.
 * @property string $description Description of the discount.
 * @property float $price Price of the discount.
 * @property integer $amount Amount of items in the discount.
 * @property float $percentage Percentage of the discount.
 * @property string $productNumber Unknown function.
 * @property string $dicountId Identifiers of the discount.
 * @property boolean $repeat Whether or not the discount repeats.
 * @property boolean $calculateSideDishes
 *  Whether or not side dishes count for the discount.
 * @property float $minimumAmount Minimum order amount for the discount.
 * @property string $deliveryMode Delivery mode of the discount.
 */
class Discount extends Model
{
}
