<?php

namespace App\Validate;

final class AddressValidator extends StringValidator {
    public const ZIP_CODE_REGEX = "/^\d{5}$/";

    public const MIN_COUNTRY_NAME_LENGTH = 4;
    public const MAX_COUNTRY_NAME_LENGTH = 63;
    public const MIN_CITY_NAME_LENGTH = 1;
    public const MAX_CITY_NAME_LENGTH = 63;
    public const MIN_STREET_NAME_LENGTH = 1;
    public const MAX_STREET_NAME_LENGTH = 63;

    public static function validateCountry(string $country, string $regex = self::GEOGRAPHICAL_NAME_REGEX, string $alias = "country"): array {
        $country = \transliterator_transliterate('Latin-ASCII', $country);

        $violations = self::validateLength($country, self::MIN_COUNTRY_NAME_LENGTH, self::MAX_COUNTRY_NAME_LENGTH, $alias);
        $violations = \array_merge($violations, self::validateContainingCharacters($country, $regex, $alias));

        return $violations;
    }

    public static function validateZipCode(string $zipCode, string $regex = self::ZIP_CODE_REGEX, string $alias = "zip code"): array {
        return self::validateExactRegex($zipCode, $regex, $alias);
    }

    public static function validateCity(string $city, string $regex = self::GEOGRAPHICAL_NAME_REGEX, string $alias = "city"): array {
        $city = \transliterator_transliterate('Latin-ASCII', $city);

        $violations = self::validateLength($city, self::MIN_CITY_NAME_LENGTH, self::MAX_CITY_NAME_LENGTH, $alias);
        $violations = \array_merge($violations, self::validateContainingCharacters($city, $regex, $alias));

        return $violations;
    }

    public static function validateStreet(string $street, string $regex = self::GEOGRAPHICAL_NAME_REGEX, string $alias = "street"): array {
        $street = \transliterator_transliterate('Latin-ASCII', $street);

        $violations = self::validateLength($street, self::MIN_STREET_NAME_LENGTH, self::MAX_STREET_NAME_LENGTH, $alias);
        $violations = \array_merge($violations, self::validateContainingCharacters($street, $regex, $alias));

        return $violations;
        
    }

    public static function validateStreetNr(string $streetNr, string $regex = self::ALPHANUMERIC_WORD_REGEX, string $alias = "street number"): array {
        $streetNr = \transliterator_transliterate('Latin-ASCII', $streetNr);
        return self::validateExactRegex($streetNr, $regex, $alias);
    }

    public static function validateApartmentNr(string $apartmentNr, string $regex = self::ALPHANUMERIC_WORD_REGEX, string $alias = "apartment number"): array {
        $apartmentNr = \transliterator_transliterate('Latin-ASCII', $apartmentNr);
        return self::validateExactRegex($apartmentNr, $regex, $alias);
    }
}