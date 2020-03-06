<?php

namespace Takeaway\Models;

use Takeaway\Takeaway;
use Takeaway\Model;
use Takeaway\Http\Requests\GetRestaurantsRequest;

/**
 * A country where Takeaway has a local business.
 *
 * An example is Thuisbezorgd.nl in The Netherlands.
 *
 * @property string $locale Two-letter locale of the country.
 * @property string $name Name of the company.
 * @property string $url Website of the company.
 * @property string $twitter Twitter handle of the company.
 * @property string $email Email adress of the company.
 * @property string $logo URL of the logo of the company.
 * @property string $icon URL of the icon of the company.
 * @property integer $countryCode
 *  Code representing the country.
 *  The exact meaning of this number is unknown.
 * @property integer $siteCode
 *  Code representing the website.
 *  The exact meaning of this number is unknown.
 * @property string[] $languages Languages supported by this company.
 * @property string[] $names Associative array consisting of the international
 *                           names of the country.
 */
class Country extends Model
{
    public function getRestaurants($postalCode, $latitude, $longitude)
    {
        return (new GetRestaurantsRequest(
            $postalCode, $this->countryCode, $latitude, $longitude, Takeaway::getConfigValue(Takeaway::CFG_LANGUAGE)
        ))->getData()['restaurants'];
    }
}
