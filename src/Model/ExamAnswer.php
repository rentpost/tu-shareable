<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a set of question-answer pairs for an exam.
 */
class ExamAnswer
{

    protected array $answers = [];


    public function addQuestionAnswer(ExamQuestion $question, ExamQuestionAnswer $answer): self
    {
        $this->answers[] = [
            $question,
            $answer
        ];

        return $this;
    }


    public function toArray(): array
    {
        $results = [];

        foreach ($this->answers as $qa) {
            $results[] = [
                'questionKeyName' => $qa[0]->getQuestionKeyName(),
                'selectedChoiceKeyName' => $qa[1]->getChoiceKeyName()
            ];
        }

        return [
            'answers' => $results
        ];
    }
}
