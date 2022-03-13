<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents an address.
 */
class Address
{

    use ValidateTrait;


    #[Assert\Length(min: 1, max: 50)]
    protected string $line1;

    #[Assert\Length(max: 100)]
    protected string $line2;

    #[Assert\Length(max: 100)]
    protected string $line3;

    #[Assert\Length(max: 100)]
    protected string $line4;

    #[Assert\Length(min: 2, max: 27)]
    protected string $locality;

    #[Assert\Choice(["AK", "AL", "AR", "AS", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "FM", "GA", "GU", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MH", "MI", "MN", "MO", "MP", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "PR", "PW", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VI", "VT", "WA", "WI", "WV", "WY"])]
    protected string $region;

    #[Assert\Regex('/^[0-9]{5}$/')]
    protected string $postalCode;

    #[Assert\EqualTo('US')]
    protected string $country;


    public function __construct(
        string $line1,
        string $line2,
        string $line3,
        string $line4,
        string $locality,
        string $region,
        string $postalCode,
        string $country = 'US'
    ) {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->line4 = $line4;
        $this->locality = $locality;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->country = $country;

        $this->validate();
    }


    public function getLine1(): string
    {
        return $this->line1;
    }


    public function getLine2(): string
    {
        return $this->line2;
    }


    public function getLine3(): string
    {
        return $this->line3;
    }


    public function getLine4(): string
    {
        return $this->line4;
    }


    public function getLocality(): string
    {
        return $this->locality;
    }


    public function getRegion(): string
    {
        return $this->region;
    }


    public function getPostalCode(): string
    {
        return $this->postalCode;
    }


    public function getCountry(): string
    {
        return $this->country;
    }
}
