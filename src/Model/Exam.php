<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents an exam.
 */
class Exam
{

    protected int $examId;

    /**
     * @var ExamQuestion[]
     */
    protected array $authenticationQuestions = [];

    protected string $result;

    protected ?string $externalReferenceNumber = null;


    public function __construct(int $examId, string $result)
    {
        $this->examId = $examId;
        $this->result = $result;
    }


    public function addQuestion(ExamQuestion $question): self
    {
        $this->authenticationQuestions[] = $question;

        return $this;
    }


    public function getExamId(): int
    {
        return $this->examId;
    }


    /**
     * @return ExamQuestion[]
     */
    public function getAuthenticationQuestions(): array
    {
        return $this->authenticationQuestions;
    }


    public function getResult(): string
    {
        return $this->result;
    }


    public function getExternalReferenceNumber(): ?string
    {
        return $this->externalReferenceNumber;
    }


    public function setExternalReferenceNumber(?string $val): void
    {
        $this->externalReferenceNumber = $val;
    }
}
