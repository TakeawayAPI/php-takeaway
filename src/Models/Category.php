<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A food or drinks category on the menu of a restaurant.
 *
 * @property string $id Unique identifier of the category.
 * @property string $name Name of the category.
 * @property string $image URL to the image of the category.
 * @property \Takeaway\Models\Product[] $products
 *  Products of the category.
 */
class Category extends Model
{
}
