<?php

namespace Takeaway\Models;

use Takeaway\Model;

/**
 * A method of paying at a restaurant.
 *
 * @property integer $id Unique identifier of the payment method.
 * @property float $fixedCosts Fixed costs of the payment method.
 * @property float $percentageCosts Variable costs of the payment method, in
 *                                  percent of the total amount.
 */
class PaymentMethod extends Model
{
}
