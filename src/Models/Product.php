<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A product offered by a restaurant.
 *
 * @property string $id Unique identifier of the product.
 * @property string $name Name of the product.
 * @property string $description Description of the product.
 * @property integer $oderMethods Available ordering methods of the product.
 * @property float $deliveryPrice Delivery price of the product.
 * @property float $pickupPrice Pickup price of the product.
 * @property \Takeaway\Models\SideDish[] $sideDishes
 *  Available side dishes of the product.
 */
class Product extends Model
{
}
