<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a question on exam.
 */
class ExamQuestion
{

    /** @var ExamQuestionAnswer[] */
    private array $choices = [];


    public function __construct(
        private string $questionKeyName,
        private string $questionDisplayName,
        private string $type,
    ) {}


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


    /** @return ExamQuestionAnswer[] */
    public function getChoices(): array
    {
        return $this->choices;
    }
}
