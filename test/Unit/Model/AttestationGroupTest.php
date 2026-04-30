<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Attestation;
use Rentpost\TUShareable\Model\AttestationGroup;

class AttestationGroupTest extends TestCase
{

    public function testConstructorWithAttestations(): void
    {
        $attestation = new Attestation(1, 2, 'Name', 'Legal Text', true, 'Info');
        $group = new AttestationGroup(123, [$attestation]);

        $this->assertSame(123, $group->getAttestationGroupId());
        $this->assertCount(1, $group->getAttestations());
        $this->assertNull($group->getAttestationsResponses());
    }


    public function testConstructorWithoutAttestations(): void
    {
        $group = new AttestationGroup(123);

        $this->assertSame(123, $group->getAttestationGroupId());
        $this->assertNull($group->getAttestations());
    }


    public function testFromArrayWithAttestations(): void
    {
        $group = AttestationGroup::fromArray([
            'attestationGroupId' => 123,
            'attestations' => [
                [
                    'attestationId' => 1,
                    'attestationTypeId' => 2,
                    'name' => 'CA ICRAA',
                    'legalText' => 'I certify...',
                    'affirmativeRequired' => true,
                    'additionalInformation' => null,
                ],
            ],
        ]);

        $this->assertSame(123, $group->getAttestationGroupId());
        $this->assertCount(1, $group->getAttestations());
    }


    public function testFromArrayWithMissingAttestationsKey(): void
    {
        $group = AttestationGroup::fromArray(['attestationGroupId' => 123]);

        $this->assertSame(123, $group->getAttestationGroupId());
        $this->assertSame([], $group->getAttestations());
    }


    public function testFromArrayWithNullAttestations(): void
    {
        $group = AttestationGroup::fromArray([
            'attestationGroupId' => 123,
            'attestations' => null,
        ]);

        $this->assertSame(123, $group->getAttestationGroupId());
        $this->assertSame([], $group->getAttestations());
    }


    public function testFromArrayWithNullableAttestationFields(): void
    {
        $group = AttestationGroup::fromArray([
            'attestationGroupId' => 123,
            'attestations' => [
                [
                    'attestationId' => 1,
                    'attestationTypeId' => 1,
                    'affirmativeRequired' => false,
                    // name, legalText, additionalInformation all absent
                ],
            ],
        ]);

        $attestation = $group->getAttestations()[0];
        $this->assertNull($attestation->getName());
        $this->assertNull($attestation->getLegalText());
        $this->assertNull($attestation->getAdditionalInformation());
    }


    public function testToArrayWithoutAttestationsOrResponses(): void
    {
        $group = new AttestationGroup(123);

        $this->assertSame(['attestationGroupId' => 123], $group->toArray());
    }


    public function testToArrayWithAttestationsAndResponses(): void
    {
        $attestation = new Attestation(1, 2, 'Name', 'Legal', true, null);
        $group = new AttestationGroup(123, [$attestation]);
        $group->addAttestationResponse(1, true);

        $array = $group->toArray();

        $this->assertSame(123, $array['attestationGroupId']);
        $this->assertCount(1, $array['attestations']);
        $this->assertCount(1, $array['attestationResponses']);
        $this->assertSame(['attestationId' => 1, 'isAffirmative' => true], $array['attestationResponses'][0]);
    }


    public function testAddAttestationResponseWithFalseAffirmation(): void
    {
        $group = new AttestationGroup(123);
        $group->addAttestationResponse(7, false);

        $responses = $group->getAttestationsResponses();
        $this->assertCount(1, $responses);
        $this->assertFalse($responses[0]->isAffirmative());
    }
}
