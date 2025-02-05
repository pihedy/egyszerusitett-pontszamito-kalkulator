<?php declare(strict_types=1);

namespace App\Calculator;

use \App\Factories\ApplicantFactory;
use \App\Enums\Major;

/**
 * Calculates the points for a set of applicants based on their academic performance and other criteria.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class PointCalculator
{
    /**
     * Constructs a new instance of the PointCalculator class with the specified requirements.
     *
     * @param array $requirements The requirements used by the PointCalculator.
     */
    public function __construct(private array $requirements)
    {
        /* Do nothing. */
    }

    /**
     * Calculates the points for a set of applicants based on their academic performance and other criteria.
     *
     * @return array An array of applicant point information, including base points, bonus points, and total points.
     *
     * @throws \Exception If no applicants are found.
     */
    public function calculate(): array
    {
        $applicants = ApplicantFactory::createFromGlobal() ?? [];

        if (empty($applicants)) {
            throw new \Exception('No applicants found.');
        }

        $result = [];

        foreach ($applicants as $key => $Applicant) {
            $FoundedMajor = Major::tryFrom($Applicant->getMajor()->getKey());

            if ($FoundedMajor === null) {
                $result[$key] = 'There is no known major in the applicant\'s field of study.';

                continue;
            }

            $Applicant->getMajor()->validateMandatorySubjects();
            $Applicant->getMajor()->validateMajorMandatorySubjects();
            $Applicant->getMajor()->validateMajorOptionalSubjects();

            if ($Applicant->hasMissingSubjects()) {
                $result[$key] = sprintf('Missing mandatory subjects: %s', implode(', ', $Applicant->getMissingSubjects()));

                continue;
            }

            $Applicant->getMajor()->validateLowerSubjects();

            if ($Applicant->hasLowerSubjects()) {
                $result[$key] = sprintf('Too low a percentage in the final exam subject: %s', implode(', ', $Applicant->getLowerSubjects()));

                continue;
            }

            $exams = $Applicant->get('erettsegi-eredmenyek', []);

            $mandatorySubject = $Applicant->getMajor()->getMandatorySubject();
            $optionalSubjects = $Applicant->getMajor()->getOptionalSubjects();

            $subjectScores = [];

            foreach ($exams as $exam) {
                $subjectScores[$exam['nev']] = (int) substr($exam['eredmeny'], 0, -1);
            }

            if (!isset($subjectScores[$mandatorySubject])) {
                $result[$key] = 'Error: Missing mandatory subject result.';

                continue;
            }

            $mandatoryScore = $subjectScores[$mandatorySubject];
            $optionalScore = 0;

            foreach ($optionalSubjects as $subject) {
                if (!isset($subjectScores[$subject])) {
                    continue;
                }

                $optionalScore = max($optionalScore, $subjectScores[$subject]);
            }

            $basePoints = ($mandatoryScore + $optionalScore) * 2;

            $bonusPoints = 0;

            $languageExams = $Applicant->get('nyelvvizsgak', []);

            foreach ($languageExams as $exam) {
                $bonusPoints = match ($exam) {
                    'B2' => max($bonusPoints, 28),
                    'C1' => max($bonusPoints, 40),
                    default => 0
                };
            }

            foreach ($exams as $exam) {
                if ($exam['tipus'] !== 'emelt') {
                    continue;
                }

                $bonusPoints += 50;
            }

            $bonusPoints = min($bonusPoints, 100);

            $totalPoints = $basePoints + $bonusPoints;

            $result[$key] = [
                'base_points' => $basePoints,
                'bonus_points' => $bonusPoints,
                'total_points' => $totalPoints,
            ];

            $result[$key] = sprintf(
                'Base points: %d, Bonus points: %d, Total points: %d',
                $basePoints, $bonusPoints, $totalPoints
            );
        }

        return $result;
    }
}
