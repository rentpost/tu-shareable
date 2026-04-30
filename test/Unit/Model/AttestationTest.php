<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Attestation;

class AttestationTest extends TestCase
{

    public function testConstructorAndGetters(): void
    {
        $attestation = new Attestation(
            1,
            2,
            'Name',
            'Legal Text',
            true,
            'Additional Information',
        );

        $this->assertSame(1, $attestation->getAttestationId());
        $this->assertSame(2, $attestation->getAttestationTypeId());
        $this->assertSame('Name', $attestation->getName());
        $this->assertSame('Legal Text', $attestation->getLegalText());
        $this->assertTrue($attestation->isAffirmativeRequired());
        $this->assertSame('Additional Information', $attestation->getAdditionalInformation());

        $this->assertSame([
            'attestationId' => 1,
            'attestationTypeId' => 2,
            'name' => 'Name',
            'legalText' => 'Legal Text',
            'affirmativeRequired' => true,
            'additionalInformation' => 'Additional Information',
        ], $attestation->toArray());
    }


    /**
     * Per the TU API spec, name, legalText, and additionalInformation are nullable.
     * Construction must not throw when any (or all) are null.
     */
    public function testNullableStringFields(): void
    {
        $attestation = new Attestation(1, 2, null, null, false, null);

        $this->assertNull($attestation->getName());
        $this->assertNull($attestation->getLegalText());
        $this->assertNull($attestation->getAdditionalInformation());
        $this->assertFalse($attestation->isAffirmativeRequired());

        $this->assertSame([
            'attestationId' => 1,
            'attestationTypeId' => 2,
            'name' => null,
            'legalText' => null,
            'affirmativeRequired' => false,
            'additionalInformation' => null,
        ], $attestation->toArray());
    }
}
