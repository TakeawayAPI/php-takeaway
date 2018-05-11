<?php

namespace Takeaway\Http\Requests;

use Takeaway\Http\PrefabRequest;
use Takeaway\Traits\MapsResponses;
use Takeaway\Models\Restaurant;

/**
 * A request which retrieves all country where Takeaway is active.
 *
 * @api
 */
class GetRestaurantsRequest extends PrefabRequest
{
    /**
     * Construct the request.
     *
     * @api
     *
     * @param string $postalCode Postal code of the user.
     * @param string $countryCode Country code of the country of the user.
     * @param float $latitude Latitude of the user.
     * @param float $longitude Latitude of the user.
     * @param string $language Language of the user.
     */
    public function __construct($postalCode, $countryCode, $latitude, $longitude, $language)
    {
        parent::__construct('getrestaurants', [
            $postalCode, $countryCode, $latitude, $longitude, $language
        ], [
            'postalCode' => $postalCode,
            'countryCode' => $countryCode,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'language' => $language
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getMapping()
    {
        return [
            'rt' => [
                'name' => 'restaurants',
                'type' => 'class',
                'class' => [Restaurant::class],
                'extra' => $this->extra,
                'mapping' => [
                    'id' => 'id',
                    'nm' => 'name',
                    'bn' => 'branch',
                    'op' => '!open',
                    'lo' => 'logo',
                    'new' => '!new',
                    'tip' => '!tip',
                    'nt' => 'description',
                    'bd' => '#numReviews',
                    'ft' => '!foodTracker',
                    'hd' => '!hasDiscounts',
                    'est' => 'estimatedDeliveryTime',
                    'ad.st' => 'street',
                    'ad.ci' => 'city',
                    'ad.lt' => '.latitude',
                    'ad.ln' => '.longitude',
                    'rv' => '#grade'
                ]
            ]
        ];
    }
}
