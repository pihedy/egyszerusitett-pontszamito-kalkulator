<?php declare(strict_types=1);

/**
 * @author Pihe Edmond <pihedy@gmail.com>
 */

return [
    'majors' => [
        'computer_science' => [
            'mandatory' => 'matematika',
            'elective' => ['biológia', 'fizika', 'informatika', 'kémia']
        ],
        'anglistics' => [
            'mandatory' => 'angol',
            'elective' => ['francia', 'német', 'olasz', 'oros', 'spanyol', 'történelem']
        ]
    ],
    'mandatory_subjects' => ['magyar nyelv és irodalom', 'történelem', 'matematika'],
    'language_exam_points' => [
        'b2' => 28,
        'c1' => 40
    ],
    'advanced_level_points' => 50,
    'max_bonus_points' => 100,
    'failed_threshold' => 20
];
