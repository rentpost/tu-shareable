<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a group of Attestations.
 *
 * @author Jacob Thomason <jacob@rentpost.com>
 */
class AttestationGroup
{

    use Validate;


    /** @var AttestationResponse[] */
    private ?array $attestationResponses = null;


    /** @param array<string, int|array<string, mixed>|null> $data */
    public static function fromArray(array $data): self
    {
        $attestationGroupId = $data['attestationGroupId'];

        $attestations = [];
        foreach (($data['attestations'] ?? []) as $attestation) {
            $attestations[] = new Attestation(
                $attestation['attestationId'],
                $attestation['attestationTypeId'],
                $attestation['name'] ?? null,
                $attestation['legalText'] ?? null,
                $attestation['affirmativeRequired'],
                $attestation['additionalInformation'] ?? null,
            );
        }

        return new self($attestationGroupId, $attestations);
    }


    public function __construct(
        private readonly int $attestationGroupId,

        /** @var Attestation[]|null */
        private ?array $attestations = null,
    ) {
        $this->validate();
    }


    public function getAttestationGroupId(): int
    {
        return $this->attestationGroupId;
    }


    /** @return Attestation[] */
    public function getAttestations(): ?array
    {
        return $this->attestations;
    }


    /** @return AttestationResponse[] */
    public function getAttestationsResponses(): ?array
    {
        return $this->attestationResponses;
    }


    public function addAttestationResponse(int $attestationId, bool $isAffirmative): void
    {
        $this->attestationResponses[] = new AttestationResponse($attestationId, $isAffirmative);
    }


    /** @return array<string, int|array<string, mixed>> */
    public function toArray(): array
    {
        $array = [
            'attestationGroupId' => $this->attestationGroupId,
        ];

        foreach (($this->attestations ?? []) as $attestation) {
            $array['attestations'][] = $attestation->toArray();
        }

        foreach (($this->attestationResponses ?? []) as $attestationResponse) {
            $array['attestationResponses'][] = $attestationResponse->toArray();
        }

        return $array;
    }
}
