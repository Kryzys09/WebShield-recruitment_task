<?php

namespace App\Validate;

/**
 * Class containing simple validations for string values
 */
class StringValidator {
    public const NON_LETTERS_REGEX = "/[^a-zA-Z]/";
    public const ALPHANUMERIC_WORD_REGEX = "/^\w+$/";
    public const GEOGRAPHICAL_NAME_REGEX = "/[^A-Za-z0-9\s.,\-']/";

    public static function validateExactRegex(string $subject, string $regex, string $alias = 'string'): array {
        if(!\preg_match($regex, $subject)) {
            return ["Incorrect $alias format!"];
        }
        return [];
    }

    public static function validateLength(string $subject, ?int $minLength = null, ?int $maxLength = null, string $alias = 'string'): array {
        if ($minLength !== null && \mb_strlen($subject) < $minLength)
            return [\ucfirst($alias) . " too short! (min. $minLength characters)"];
        else if ($maxLength !== null && \mb_strlen($subject) > $maxLength)
            return [\ucfirst($alias) . " too long! (max. $maxLength characters)"];

        return [];
    }

    /**
     * method checks given subject for existence of characters from given regex
     * 
     * @param string subject - string on which the search will be carried on
     * @param string regex - regex string containing rule for finding forbidden characters, eg. "/[0-9,.]/"
     */
    public static function validateContainingCharacters(string $subject, string $regex, string $alias = 'string'): array {
        \preg_match($regex, $subject, $regExViolations);
        if ($regExViolations)
            return ["Given " . $alias . " contains not permitted characters: '" . \implode("', '", $regExViolations). "'!"];

        return [];
    }
}