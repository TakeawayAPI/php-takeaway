<?php

namespace Takeaway\Http\Requests;

use Takeaway\Http\Request;
use Takeaway\Traits\MapsResponses;
use Takeaway\Models\Category;
use Takeaway\Models\Choice;
use Takeaway\Models\DeliverCosts;
use Takeaway\Models\DeliverArea;
use Takeaway\Models\PaymentMethod;
use Takeaway\Models\Product;
use Takeaway\Models\Restaurant;
use Takeaway\Models\SideDish;

/**
 * A request which retrieves more information about a specific restaurant.
 *
 * @api
 */
class GetRestaurantRequest extends Request
{
    use MapsResponses;

    /**
     * Construct the request.
     *
     * @api
     *
     * @param string $restaurantId Unique identifier of the restaurant.
     * @param string $postalCode Postal code of the user.
     * @param float $latitude Latitude of the user.
     * @param float $longitude Longitude of the user.
     */
    public function __construct($restaurantId, $postalCode, $latitude, $longitude)
    {
        parent::__construct('getrestaurantdata', [
            $restaurantId, $postalCode, '1', $latitude, $longitude, ''
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getMapping()
    {
        return [
            'ri' => 'id',
            'nm' => 'name',
            'op' => '!open',
            'oo.lu' => 'logo',
            'oo.sl' => 'slogan',
            'oo.rv' => '#grade',
            'oo.bd' => '#numReviews',
            'oo.ft' => '!foodTracker',
            'ad.st' => 'street',
            'ad.tn' => 'city',
            'ad.pc' => 'postalCode',
            'ad.lt' => '.latitude',
            'ad.ln' => '.longitude',
            'dd.da' => [
                'name' => 'deliverAreas',
                'type' => 'class',
                'class' => [DeliverArea::class],
                'mapping' => [
                    'pc.pp' => ['postalCodes'],
                    'ma' => '.minimumAmount',
                    'co' => [
                        'name' => 'deliverCosts',
                        'type' => 'class',
                        'class' => DeliverCosts::class,
                        'mapping' => [
                            'fr' => 'from',
                            'to' => 'to',
                            'ct' => '.costs'
                        ]
                    ]
                ]
            ],
            'dm.ah' => '#orderMethods',
            'dm.dl.op' => '!open',
            'dm.dl.mpt' => '#mealPrepTime',
            'dc.ma' => '.minimumAmount',
            'dc.co' => [
                'name' => 'deliverCosts',
                'type' => 'class',
                'class' => [DeliverCosts::class],
                'mapping' => [
                    'fr' => 'from',
                    'to' => 'to',
                    'ct' => '.costs'
                ]
            ],
            'pm.me' => [
                'name' => 'paymentMethods',
                'type' => 'class',
                'class' => [PaymentMethod::class],
                'mapping' => [
                    'mi' => '#id',
                    'mt' => '.fixedCosts',
                    'mf' => '.percentageCosts'
                ]
            ],
            'mc.cs.ct' => [
                'name' => 'categories',
                'type' => 'class',
                'class' => [Category::class],
                'mapping' => [
                    'id' => 'id',
                    'nm' => 'name',
                    'cti' => 'image',
                    'ps.pr' => [
                        'name' => 'products',
                        'type' => 'class',
                        'class' => [Product::class],
                        'mapping' => [
                            'id' => 'id',
                            'nm' => 'name',
                            'ds' => 'description',
                            'ah' => '#orderMethods',
                            'pc' => '.deliveryPrice',
                            'tc' => '.pickupPrice',
                            'ss.sd' => [
                                'name' => 'sideDishes',
                                'type' => 'class',
                                'class' => [SideDish::class],
                                'mapping' => [
                                    'nm' => 'name',
                                    'tp' => 'type',
                                    'cc.ch' => [
                                        'name' => 'choices',
                                        'type' => 'class',
                                        'class' => [Choice::class],
                                        'mapping' => [
                                            'id' => 'id',
                                            'nm' => 'name',
                                            'pc' => '.deliveryPrice',
                                            'tc' => '.pickupPrice',
                                            'xfm' => '!excludedFromMinimum'
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            'mc.cs.ks.kk' => [
                'name' => 'discounts',
                'type' => 'class',
                'class' => [Discount::class],
                'mapping' => [
                    'tp' => 'type',
                    'nm' => 'name',
                    'ds' => 'description',
                    'pr' => '.price',
                    'da' => '#amount',
                    'dp' => '.percentage',
                    'dn' => 'productNumber',
                    'kg.ki.id' => ['discountId'],
                    'en' => '!repeat',
                    'is' => '!calculateSideDishes',
                    'doo' => '.minimumAmount',
                    'ddm' => 'deliveryMode'
                ]
            ]
        ];
    }
}
