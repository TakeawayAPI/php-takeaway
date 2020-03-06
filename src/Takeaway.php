<?php

namespace Takeaway;

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

    public static function configure($config): void
    {
        static::$configuration = array_merge(static::$configuration, $config);
    }

    public static function getConfigValue($name)
    {
        return static::$configuration[$name] ?? '';
    }

    public static function setConfigValue($name, $value)
    {
        return $configuration[$name] = $value;
    }

    public static function getCountries()
    {
        return (new GetCountriesRequest())->getData()['countries'];
    }

    public static function getCountryByCode($countryCode)
    {
        $countries = self::getCountries();

        $countries = array_values(array_filter($countries, function ($country) use ($countryCode) {
            return $country->countryCode === $countryCode;
        }));

        return $countries[0] ?? null;
    }

    public static function getCountryByLocale($countryLocale)
    {
        $countries = self::getCountries();

        $countries = array_values(array_filter($countries, function ($country) use ($countryLocale) {
            echo $country->locale.' - '.$countryLocale.' --> '.strcasecmp($country->locale, $countryLocale).PHP_EOL;
            return strcasecmp($country->locale, $countryLocale) === 0;
        }));

        return $countries[0] ?? null;
    }
}
