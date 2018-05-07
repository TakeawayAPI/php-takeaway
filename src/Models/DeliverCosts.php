<?php

namespace Takeaway\Models;
use Takeaway\Model;

/**
 * The costs of delivering food in a certain time range.
 *
 * @property string $from The start time of the time range.
 * @property string $to The end time of the time range.
 * @property float $costs The delivery costs during this time range.
 */
class DeliverCosts extends Model
{
}
