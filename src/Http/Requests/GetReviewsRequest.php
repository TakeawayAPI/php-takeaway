<?php

namespace Takeaway\Http\Requests;

use Takeaway\Http\PrefabRequest;
use Takeaway\Traits\MapsResponses;
use Takeaway\Models\Review;

/**
 * A request which retrieves reviews of a restaurant, by page.
 *
 * @api
 */
class GetReviewsRequest extends PrefabRequest
{
    /**
     * Construct the request.
     *
     * @api
     *
     * @param string $restaurantId Unique identifier of the restaurant.
     * @param int|null $page Page to query.
     */
    public function __construct($restaurantId, $page = 1)
    {
        parent::__construct('restaurantreviews', [
            $restaurantId, $page
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getMapping()
    {
        return [
            'rv' => [
                'name' => 'reviews',
                'type' => 'class',
                'class' => [Review::class],
                'mapping' => [
                    'nm' => 'name',
                    'ti' => 'time',
                    'rm' => 'description',
                    'kw' => '#quality',
                    'be' => '#delivery',
                    'zo' => '!sunday',
                    'dm' => 'orderMethod'
                ]
            ]
        ];
    }
}
