<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class for validation exceptions.
 */
class ValidationException extends \RuntimeException
{

    protected ConstraintViolationListInterface $violations;


    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->error = $violations;

        parent::__construct((string) $violations);
    }


    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
