<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents an exam.
 */
class Exam
{

    /** @var ExamQuestion[] */
    private array $authenticationQuestions = [];
    private ?string $externalReferenceNumber = null;


    public function __construct(private int $examId, private string $result) {}


    public function addQuestion(ExamQuestion $question): self
    {
        $this->authenticationQuestions[] = $question;

        return $this;
    }


    public function getExamId(): int
    {
        return $this->examId;
    }


    /** @return ExamQuestion[] */
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


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $exam = new self($data['examId'], $data['result']);
        $exam->setExternalReferenceNumber($data['setExternalReferenceNumber'] ?? null);

        foreach ($data['authenticationQuestions'] as $qInfo) {
            $question = new ExamQuestion($qInfo['questionKeyName'], $qInfo['questionDisplayName'], $qInfo['type']);

            foreach ($qInfo['choices'] as $choice) {
                $answer = new ExamQuestionAnswer($choice['choiceKeyName'], $choice['choiceDisplayName']);

                $question->addChoice($answer);
            }

            $exam->addQuestion($question);
        }

        return $exam;
    }
}
