<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents an answer to a question on exam.
 */
class ExamQuestionAnswer
{

    protected string $choiceKeyName;

    protected string $choiceDisplayName;


    public function __construct(string $choiceKeyName, string $choiceDisplayName)
    {
        $this->choiceKeyName = $choiceKeyName;
        $this->choiceDisplayName = $choiceDisplayName;
    }


    public function getChoiceKeyName(): string
    {
        return $this->choiceKeyName;
    }


    public function getChoiceDisplayName(): string
    {
        return $this->choiceDisplayName;
    }
}
