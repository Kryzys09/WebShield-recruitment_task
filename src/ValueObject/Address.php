<?php

namespace App\ValueObject;

final readonly class Address {
    function __construct(
        private string $country, // Ideally there would be a dictionary of const here so that there's no separate entries like 'Holland' and 'Netherlands', 'poland' and 'Poland', etc.
        private string $zipCode,
        private string $city,
        private string $street,
        private string $streetNr,
        private ?string $apartmentNr = null
    ) {
        
    }
}