<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A side dish belonging to a product.
 *
 * @property string $name Name of the side dish.
 * @property string $type Type of the side dish.
 * @property \Takeaway\Models\Choice[] $choices
 *  Available choices of the side dish.
 */
class SideDish extends Model
{
}
