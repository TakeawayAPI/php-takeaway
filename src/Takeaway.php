<?php

namespace Takeaway;

use Takeaway\Models\Country;
use Takeaway\Models\Restaurant;
use Takeaway\Traits\MakesRequests;
use Takeaway\Http\Requests\GetCountriesRequest;

class Takeaway
{
    public const BASE_URL = 'https://nl.citymeal.com/android/android.php';
    public const LANGUAGE = 'nl';
    public const PASSWORD = '4ndro1d';
    public const VERSION = '5.7';
    public const SYSTEM_VERSION = '24';
    public const APP_VERSION = '4.15.3.2';
    public const APP_NAME = 'Takeaway.com';

    public static function getCountries()
    {
        return (new GetCountriesRequest())->getData()['countries'];
    }

    public static function getCountryByCode($countryCode)
    {
        $countries = self::getCountries();

        $countries = array_filter($countries, function ($country) use ($countryCode) {
            return $country->countryCode === $countryCode;
        });

        return $countries[0] ?? null;
    }
}
