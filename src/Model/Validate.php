<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Rentpost\TUShareable\ValidationException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

trait Validate
{

    protected function validate(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(
                new ConstraintValidatorFactory([
                    EmailValidator::class => new EmailValidator(Email::VALIDATION_MODE_HTML5),
                ])
            )
            ->enableAttributeMapping()
            ->getValidator();

        $errors = $validator->validate($this);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
