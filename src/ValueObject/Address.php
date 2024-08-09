<?php

namespace App\ValueObject;

use App\Exception\InvalidPropertyException;
use App\Validate\AddressValidator;

final readonly class Address {
    // Ideally there would be a dictionary of const here so that there's no separate entries like 'Holland' and 'Netherlands', 'poland' and 'Poland', etc.
    private string  $country,
    $zipCode,
    $city,
    $street,
    $streetNr,
    $apartmentNr;

    /** @throws InvalidPropertyException */
    function __construct(
        string $country,
        string $zipCode,
        string $city,
        string $street,
        string $streetNr,
        ?string $apartmentNr = null
    ) {
        $this->setCountry($country);
        $this->setZipCode($zipCode);
        $this->setCity($city);
        $this->setStreet($street);
        $this->setStreetNr($streetNr);
        if ($apartmentNr) $this->setApartmentNr($apartmentNr);
    }

    public function getCountry(): string  {
        return $this->country;
    }
    
    public function setCountry($country): self {
        $violations = AddressValidator::validateCountry($country);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->country = $country;

        return $this;
    }

    public function getZipCode(): string {
        return $this->zipCode;
    }

    public function setZipCode($zipCode): self {
        $violations = AddressValidator::validateZipCode($zipCode);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function setCity($city): self {
        $violations = AddressValidator::validateCity($city);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->city = $city;

        return $this;
    }

    public function getStreet(): string {
        return $this->street;
    }

    public function setStreet($street): self {
        $violations = AddressValidator::validateStreet($street);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->street = $street;

        return $this;
    }

    public function getStreetNr(): string {
        return $this->streetNr;
    }

    public function setStreetNr($streetNr): self {
        $violations = AddressValidator::validateStreetNr($streetNr);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->streetNr = $streetNr;

        return $this;
    }

    public function getApartmentNr(): string {
        return $this->apartmentNr;
    }

    public function setApartmentNr($apartmentNr): self {
        $violations = AddressValidator::validateApartmentNr($apartmentNr);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->apartmentNr = $apartmentNr;

        return $this;
    }
}