<?php

namespace Takeaway\Models;
use Takeaway\Model;

/**
 * An area where a restaurant delivers food.
 *
 * @property string[] $postalCodes Postal codes in the area.
 * @property float $minimumAmount Minium order amount of the area.
 * @property \Takeaway\Models\DeliverCosts $deliverCosts
 *  Delivery costs of the area.
 */
class DeliverArea extends Model
{
}
