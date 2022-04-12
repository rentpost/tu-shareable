<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Person;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\SocialSecurityNumber;
use Rentpost\TUShareable\ValidationException;

class RenterTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $renter = $this->makeObject('1000', 'PerMonth', '5000', 'PerYear', '12000', 'SelfEmployed', new Date('2022-12-30'));

        $this->assertInstanceOf(Date::class, $renter->getMultiShareExpirationDate());
        $this->assertInstanceOf(Money::class, $renter->getIncome());
        $this->assertInstanceOf(Money::class, $renter->getOtherIncome());
        $this->assertInstanceOf(Money::class, $renter->getAssets());

        $this->assertSame('1000', $renter->getIncome()->getValue());
        $this->assertSame('5000', $renter->getOtherIncome()->getValue());
        $this->assertSame('12000', $renter->getAssets()->getValue());

        $this->assertSame('PerMonth', $renter->getIncomeFrequency());
        $this->assertSame('PerYear', $renter->getOtherIncomeFrequency());
        $this->assertSame('2022-12-30', $renter->getMultiShareExpirationDate()->getValue());

        $this->assertSame([
            'person' => [
                'emailAddress' => 'test@example.com',
                'firstName' => 'First',
                'middleName' => 'Middle',
                'lastName' => 'Last',
                'phoneNumber' => '0123456789',
                'phoneType' => 'Home',
                'socialSecurityNumber' => '012345789',
                'dateOfBirth' => '1990-03-15',
                'homeAddress' => [
                    'addressLine1' => 'Streetname',
                    'addressLine2' => 'Apartment',
                    'locality' => 'Los Angeles',
                    'region' => 'CA',
                    'postalCode' => '12345',
                    'country' => 'US',
                ],
                'acceptedTermsAndConditions' => true,
            ],
            'income' => '1000',
            'incomeFrequency' => 'PerMonth',
            'otherIncome' => '5000',
            'otherIncomeFrequency' => 'PerYear',
            'assets' => '12000',
            'employmentStatus' => 'SelfEmployed',
            'multiShareExpirationDate' => '2022-12-30',
        ], $renter->toArray());
    }


    public function validationProvider()
    {
        return [
            // Invalid income frequency
            ['1000', 'Invalid', '5000', 'PerYear', '12000', 'SelfEmployed', null,
                'The value you selected is not a valid choice.', 'incomeFrequency'],
            // Invalid other income frequency
            ['1000', 'PerMonth', '5000', 'Invalid', '12000', 'SelfEmployed', null,
                'The value you selected is not a valid choice.', 'otherIncomeFrequency'],
            // Invalid employment status
            ['1000', 'PerMonth', '5000', 'PerYear', '12000', 'Invalid', null,
                'The value you selected is not a valid choice.', 'employmentStatus'],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $income, string $incomeFrequency, string $otherIncome,
        string $otherIncomeFrequency, string $assets, string $employmentStatus,
        ?Date $multiShareExpirationDate, string $errorMessage, string $fieldname)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);
        $this->expectExceptionMessage(".$fieldname");

        $this->makeObject($income, $incomeFrequency, $otherIncome, $otherIncomeFrequency, $assets,
            $employmentStatus, $multiShareExpirationDate);
    }


    protected function makePerson(): Person
    {
        $address = new Address('Streetname', 'Apartment', '', '', 'Los Angeles', 'CA', '12345');
        $email = new Email('test@example.com');
        $phone = new Phone('0123456789', 'Home');
        $ssn = new SocialSecurityNumber('012345789');
        $date = new Date('1990-03-15');

        return new Person(
            $email,
            'First',
            'Middle',
            'Last',
            $phone,
            $ssn,
            $date,
            $address,
            true
        );
    }


    protected function makeObject(string $income, string $incomeFrequency, string $otherIncome,
        string $otherIncomeFrequency, string $assets, string $employmentStatus,
        ?Date $multiShareExpirationDate = null): Renter
    {
        return new Renter(
            $this->makePerson(),
            new Money($income),
            $incomeFrequency,
            new Money($otherIncome),
            $otherIncomeFrequency,
            new Money($assets),
            $employmentStatus,
            $multiShareExpirationDate
        );
    }
}
