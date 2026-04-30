<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a single attestation.
 */
class Attestation
{

    use Validate;


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['attestationId'],
            $data['attestationTypeId'],
            $data['name'],
            $data['legalText'],
            $data['affirmativeRequired'],
            $data['additionalInformation'],
        );
    }


    public function __construct(
        private int $attestationId,
        private int $attestationTypeId,
        private ?string $name,
        private ?string $legalText,
        private bool $affirmativeRequired,
        private ?string $additionalInformation,
    ) {
        $this->validate();
    }


    public function getAttestationId(): int
    {
        return $this->attestationId;
    }


    public function getAttestationTypeId(): int
    {
        return $this->attestationTypeId;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function getLegalText(): ?string
    {
        return $this->legalText;
    }


    public function isAffirmativeRequired(): bool
    {
        return $this->affirmativeRequired;
    }


    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }


    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'attestationId' => $this->attestationId,
            'attestationTypeId' => $this->attestationTypeId,
            'name' => $this->name,
            'legalText' => $this->legalText,
            'affirmativeRequired' => $this->affirmativeRequired,
            'additionalInformation' => $this->additionalInformation,
        ];
    }
}
