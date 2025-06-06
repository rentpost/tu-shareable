<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Attestation;
use Rentpost\TUShareable\ValidationException;

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
     * @return array<array<string, string>>
     */
    public static function validationProvider(): array
    {
        return [
            // name missing
            [ [1, 2, '', 'Legal Text', true, 'Additional Information'], 'name', 'This value should not be blank.' ],
            // legal text missing
            [ [1, 2, 'Name', '', true, 'Additional Information'], 'legalText', 'This value should not be blank.' ],
        ];
    }


    /**
     * @param string[] $values
     */
    #[DataProvider('validationProvider')]
    public function testValidationErrors(array $values, string $field, string $errorMessage): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Object(Rentpost\TUShareable\Model\Attestation).$field");
        $this->expectExceptionMessage($errorMessage);

        new Attestation(...$values);
    }
}
