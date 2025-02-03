<?php declare(strict_types=1);

/**
 * @author Pihe Edmond <pihedy@gmail.com>
 */

return [
    'majors' => [
        'computer_science' => [
            'mandatory' => 'mathematics',
            'elective' => ['biology', 'physics', 'informatics', 'chemistry']
        ],
        'anglistics' => [
            'mandatory' => 'english',
            'elective' => ['french', 'german', 'italian', 'russian', 'spanish', 'history']
        ]
    ],
    'mandatory_subjects' => ['hungarian_language_and_literature', 'history', 'mathematics'],
    'language_exam_points' => [
        'b2' => 28,
        'c1' => 40
    ],
    'advanced_level_points' => 50,
    'max_bonus_points' => 100,
    'failed_threshold' => 20
];
