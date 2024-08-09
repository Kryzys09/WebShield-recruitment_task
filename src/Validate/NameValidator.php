<?php

namespace App\Validate;

final class NameValidator extends StringValidator {
    public const FORBIDDEN_NAME_CHARS_REGEX = "/[^a-zA-Z .,'-]/";
    public const FORBIDDEN_COMPANY_NAME_CHARS_REGEX = "/[^a-zA-Z0-9\s.,\-_:'\"\/]/";

    public const MIN_NAME_LENGTH = 2;
    public const MAX_NAME_LENGTH = 127;
    public const MIN_COMPANY_NAME_LENGTH = 4;
    public const MAX_COMPANY_NAME_LENGTH = 127;

    /**
     * @param string regex - string containing a regex to find forbidden characters inside given name 
     * 
     * @return array - array of name violations
     */
    public static function validateName(
        string $name,
        int $minLength = self::MIN_NAME_LENGTH,
        int $maxLength = self::MAX_NAME_LENGTH,
        string $regex = self::FORBIDDEN_NAME_CHARS_REGEX,
        string $alias = 'name'
    ): array {
        // translating latin chars into their ASCII equivalents (eg.: ł => l, ö => o)
        $name = \transliterator_transliterate('Latin-ASCII', $name);

        $violations = self::validateLength($name, $minLength, $maxLength, $alias);
        $violations = \array_merge($violations, self::validateContainingCharacters($name, $regex, $alias));

        return $violations;
    }

    /**
     * @return array - array of company name violations
     */
    public static function validateCompanyName(string $name, string $alias = 'company name'): array {
        return self::validateName(
            $name,
            self::MIN_COMPANY_NAME_LENGTH,
            self::MAX_COMPANY_NAME_LENGTH,
            self::FORBIDDEN_COMPANY_NAME_CHARS_REGEX,
            $alias
        );
    }
}