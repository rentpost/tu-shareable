<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Rentpost\TUShareable\ValidationException;
use Symfony\Component\Validator\Validation;

trait ValidateTrait
{

    protected function validate()
    {
        $valBuilder = Validation::createValidatorBuilder();
        $validator = $valBuilder->enableAnnotationMapping()->getValidator();
        $errors = $validator->validate($this);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
