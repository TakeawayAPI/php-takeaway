<?php

namespace Takeaway\Http\Requests;

use Takeaway\Http\PrefabRequest;
use Takeaway\Models\Country;

/**
 * A request which retrieves all country where Takeaway is active.
 *
 * @api
 */
class GetCountriesRequest extends PrefabRequest
{
    /**
     * Construct the request.
     *
     * @api
     */
    public function __construct()
    {
        parent::__construct('getcountriesdata');
    }

    /**
     * @inheritDoc
     */
    protected function getMapping()
    {
        return [
            'cd' => [
                'name' => 'countries',
                'type' => 'class',
                'class' => [Country::class],
                'mapping' => [
                    'cy' => 'locale',
                    'nm' => 'name',
                    'su' => 'url',
                    'tw' => 'twitter',
                    'se' => 'email',
                    'lo' => 'logo',
                    'fl' => 'icon',
                    'ic' => '#countryCode',
                    'sc' => '#siteCode',
                    'ls.la' => ['languages'],
                    'cn' => [
                        'name' => 'names',
                        'type' => 'object'
                    ]
                ]
            ]
        ];
    }
}
