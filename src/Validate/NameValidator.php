<?php

namespace App\Validate;

final class NameValidator {
    /**
     * @param string regex - Regex for searching violations (should match all forbidden characters inside given name).
     *                       Default regex checks for characters outside of latin alphabets and different than " ", ".", ",", "'", or "-".
     * 
     * @return array - array of name violations
     */
    public static function validateName(string $name, int $minLength = 2, int $maxLength = 127, string $regex = "/[^a-zA-Z .,'-]/"): array {
        $violations = [];
        // translating non-latin chars into their latin equivalents (eg.: ł => l, ö => o)
        $name = \transliterator_transliterate('Latin-ASCII', $name);

        if (\mb_strlen($name) < $minLength)
            $violations[] = "Name too short! (min. $minLength characters)";
        else if (\mb_strlen($name) > $maxLength)
            $violations[] = "Name too long! (max. $maxLength characters)";
        
        \preg_match($regex, $name, $regExViolations);
        if ($regExViolations)
            $violations[] = "Given name contains not permitted characters: '" . \implode("', '", $regExViolations). "'!";;

        return $violations;
    }

    /**
     * @return array - array of company name violations
     */
    public static function validateCompanyName(string $name): array {
        $regex = "/[^a-zA-Z0-9\s.,\-_:'\"\/]/";

        return self::validateName(
            $name,
            4,
            127,
            $regex
        );
    }
}