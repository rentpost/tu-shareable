<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$answer = new Rentpost\TUShareable\Model\ExamAnswer();

$answer->addQuestionAnswer(
    new Rentpost\TUShareable\Model\ExamQuestion(
        'ACX_RCR_REL_CITY_DYNAMIC',
        '',
        '0'
    ),
    new Rentpost\TUShareable\Model\ExamQuestionAnswer(
        'Joe Lawrence',
        ''
    )
);

$answer->addQuestionAnswer(
    new Rentpost\TUShareable\Model\ExamQuestion(
        'ACX_ID2_ASSOC_ST_NAME',
        '',
        '0'
    ),
    new Rentpost\TUShareable\Model\ExamQuestionAnswer(
        '123rd',
        ''
    )
);

$answer->addQuestionAnswer(
    new Rentpost\TUShareable\Model\ExamQuestion(
        'ACX_RCR_HUNT_LIC_TYPE',
        '',
        '0'
    ),
    new Rentpost\TUShareable\Model\ExamQuestionAnswer(
        'Saltwater Snook',
        ''
    )
);

$exam = $client->answerExam(130400, 524720, $answer);

print_r($exam);
