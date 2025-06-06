<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents an Attestation response.
 *
 * @author Jacob Thomason <jacob@rentpost.com>
 */
class AttestationResponse
{

    use Validate;


    public function __construct(
        private readonly int $attestationId,
        private readonly bool $isAffirmative,
    ) {
        $this->validate();
    }


    public function getAttestationId(): int
    {
        return $this->attestationId;
    }


    public function isAffirmative(): bool
    {
        return $this->isAffirmative;
    }


    /** @return array<string, int|bool> */
    public function toArray(): array
    {
        return [
            'attestationId' => $this->attestationId,
            'isAffirmative' => $this->isAffirmative,
        ];
    }
}
