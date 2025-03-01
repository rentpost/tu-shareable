<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class for validation exceptions.
 */
class ValidationException extends \RuntimeException
{

    public function __construct(
        private ConstraintViolationListInterface $violations,
    ) {
        parent::__construct((string)$violations);
    }


    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
