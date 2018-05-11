<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A review of a restaurant.
 *
 * @property string $name Name of the reviewer.
 * @property string $time Time the review was posted.
 * @property string $description Description provided by the reviewer.
 * @property int $quality Quality of the food.
 * @property int $delivery Quality of the delivery.
 * @property boolean $sunday Whether or not the review was placed on a Sunday.
 * @property int $orderMethod The method of ordering of the reviewer's order.
 */
class Review extends Model
{
}
