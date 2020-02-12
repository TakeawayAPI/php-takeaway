<?php

namespace Takeaway;

use Takeaway\Models\Country;
use Takeaway\Models\Restaurant;
use Takeaway\Traits\MakesRequests;
use Takeaway\Http\Requests\GetCountriesRequest;

class Takeaway
{

    public const CFG_BASE_URL       = 'BASE_URL';
    public const CFG_LANGUAGE       = 'LANGUAGE';
    public const CFG_PASSWORD       = 'PASSWORD';
    public const CFG_VERSION        = 'VERSION';
    public const CFG_SYSTEM_VERSION = 'SYSTEM_VERSION';
    public const CFG_APP_VERSION    = 'APP_VERSION';
    public const CFG_APP_NAME       = 'APP_NAME';

    protected static $configuration = [
        self::CFG_BASE_URL       => 'https://nl.citymeal.com/android/android.php',
        self::CFG_LANGUAGE       => 'nl',
        self::CFG_PASSWORD       => '4ndro1d',
        self::CFG_VERSION        => '5.7',
        self::CFG_SYSTEM_VERSION => '24',
        self::CFG_APP_VERSION    => '4.15.3.2',
        self::CFG_APP_NAME       => 'Takeaway.com',
    ];

    /**
     * Configures the TakeAway class.
     * @param array $config configuration values, key is one of the CFG_* constants, value is the new setting
     */
    public static function configure($config): void
    {
        static::$configuration = array_merge(static::$configuration, $config);
    }

    /**
     * Get a configuration value
     * @param string $name one of the CFG_* constants
     * @return string configuration value
     */
    public static function getConfigValue($name): string
    {
        return static::$configuration[$name] ?? '';
    }

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
