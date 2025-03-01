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


    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 50)]
        private string $addressLine1,

        #[Assert\Length(max: 100)]
        private ?string $addressLine2,

        #[Assert\Length(max: 100)]
        private ?string $addressLine3,

        #[Assert\Length(max: 100)]
        private ?string $addressLine4,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 27)]
        private string $locality,

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
            message: 'The value is not a valid US state.',
        )]
        private string $region,

        #[Assert\NotBlank]
        #[Assert\Regex('/^[0-9]{5}$/', 'The value is not a valid US postal code.')]
        private string $postalCode,

        #[Assert\Length(exactly: 2)]
        #[Assert\EqualTo('US')]
        private string $country = 'US',
    ) {
        $this->validate();
    }


    public function setAddressLine1(string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }


    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }


    public function setAddressLine2(?string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }


    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }


    public function setAddressLine3(?string $addressLine3): void
    {
        $this->addressLine3 = $addressLine3;
    }


    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }


    public function setAddressLine4(?string $addressLine4): void
    {
        $this->addressLine4 = $addressLine4;
    }


    public function getAddressLine4(): ?string
    {
        return $this->addressLine4;
    }


    public function setLocality(string $locality): void
    {
        $this->locality = $locality;
    }


    public function getLocality(): string
    {
        return $this->locality;
    }


    public function setRegion(string $region): void
    {
        $this->region = $region;
    }


    public function getRegion(): string
    {
        return $this->region;
    }


    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }


    public function getPostalCode(): string
    {
        return $this->postalCode;
    }


    public function setCountry(string $country): void
    {
        $this->country = $country;
    }


    public function getCountry(): string
    {
        return $this->country;
    }


    /** @return string[] */
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


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['addressLine1'],
            $data['addressLine2'] ?? null,
            $data['addressLine3'] ?? null,
            $data['addressLine4'] ?? null,
            $data['locality'],
            $data['region'],
            $data['postalCode'],
            $data['country'],
        );
    }
}
