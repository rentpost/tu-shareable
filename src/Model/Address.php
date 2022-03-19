<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents an address.
 */
class Address
{

    use Validate;


    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    protected string $addressLine1;

    #[Assert\Length(max: 100)]
    protected ?string $addressLine2;

    #[Assert\Length(max: 100)]
    protected ?string $addressLine3;

    #[Assert\Length(max: 100)]
    protected ?string $addressLine4;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 27)]
    protected string $locality;

    #[Assert\NotBlank]
    #[Assert\Choice(
        choices: [
            'AK',
            'AL',
            'AR',
            'AS',
            'AZ',
            'CA',
            'CO',
            'CT',
            'DC',
            'DE',
            'FL',
            'FM',
            'GA',
            'GU',
            'HI',
            'IA',
            'ID',
            'IL',
            'IN',
            'KS',
            'KY',
            'LA',
            'MA',
            'MD',
            'ME',
            'MH',
            'MI',
            'MN',
            'MO',
            'MP',
            'MS',
            'MT',
            'NC',
            'ND',
            'NE',
            'NH',
            'NJ',
            'NM',
            'NV',
            'NY',
            'OH',
            'OK',
            'OR',
            'PA',
            'PR',
            'PW',
            'RI',
            'SC',
            'SD',
            'TN',
            'TX',
            'UT',
            'VA',
            'VI',
            'VT',
            'WA',
            'WI',
            'WV',
            'WY',
        ],
        message: 'The value is not a valid US state.'
    )]
    protected string $region;

    #[Assert\NotBlank]
    #[Assert\Regex('/^[0-9]{5}$/', 'The value is not a valid US postal code.')]
    protected string $postalCode;

    #[Assert\NotBlank]
    #[Assert\EqualTo('US')]
    protected string $country;


    public function __construct(
        string $addressLine1,
        ?string $addressLine2,
        ?string $addressLine3,
        ?string $addressLine4,
        string $locality,
        string $region,
        string $postalCode,
        string $country = 'US'
    ) {
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->addressLine3 = $addressLine3;
        $this->addressLine4 = $addressLine4;
        $this->locality = $locality;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->country = $country;

        $this->validate();
    }


    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }


    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }


    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }


    public function getAddressLine4(): ?string
    {
        return $this->addressLine4;
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


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $array = [
            'addressLine1' => $this->addressLine1,
        ];

        if ($this->addressLine2) {
            $array['addressLine2'] = $this->addressLine2;
        }

        if ($this->addressLine3) {
            $array['addressLine3'] = $this->addressLine3;
        }

        if ($this->addressLine4) {
            $array['addressLine4'] = $this->addressLine4;
        }

        $array = array_merge($array, [
            'addressLine1' => $this->addressLine1,
            'locality' => $this->locality,
            'region' => $this->region,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
        ]);

        return $array;
    }
}
