<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a question on exam.
 */
class ExamQuestion
{

    protected string $questionKeyName;

    protected string $questionDisplayName;

    protected string $type;

    protected array $choices = [];


    public function __construct(string $questionKeyName, string $questionDisplayName, string $type)
    {
        $this->questionKeyName = $questionKeyName;
        $this->questionDisplayName = $questionDisplayName;
        $this->type = $type;
    }


    public function addChoice(ExamQuestionAnswer $answer): self
    {
        $this->choices[] = $answer;

        return $this;
    }


    public function getQuestionKeyName(): string
    {
        return $this->questionKeyName;
    }


    public function getQuestionDisplayName(): string
    {
        return $this->questionDisplayName;
    }


    public function getType(): string
    {
        return $this->type;
    }


    public function getChoices(): array
    {
        return $this->choices;
    }
}
