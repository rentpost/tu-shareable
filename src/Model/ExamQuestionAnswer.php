<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents an answer to a question on exam.
 */
class ExamQuestionAnswer
{

    public function __construct(
        private string $choiceKeyName,
        private string $choiceDisplayName,
    ) {}


    public function getChoiceKeyName(): string
    {
        return $this->choiceKeyName;
    }


    public function getChoiceDisplayName(): string
    {
        return $this->choiceDisplayName;
    }
}
