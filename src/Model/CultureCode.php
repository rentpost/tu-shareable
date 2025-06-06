<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

enum CultureCode: string
{

    case USEnglish = 'en-US';
    case CAEnglish = 'en-CA';
    case CAFrench = 'fr-CA';


    public static function fromAddress(Address $address): self
    {
        return match ($address->getCountry()) {
            'US' => self::USEnglish,
            'CA' => $address->getRegion() === 'QC' ? self::CAFrench : self::CAEnglish,
            default => self::USEnglish,
        };
    }
}
