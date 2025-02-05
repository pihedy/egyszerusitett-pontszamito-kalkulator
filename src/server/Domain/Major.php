<?php declare(strict_types=1);

namespace App\Domain;

use \App\Services\Applicant;

use \App\Utils\Support;

use \App\Enums\Major as MajorEnum;

/**
 * Represents a major field of study, with mandatory and optional subjects.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class Major
{
    /**
     * A list of mandatory subjects for the major.
     */
    public const MANDATORY_SUBJECTS = ['magyar_nyelv_es_irodalom', 'tortenelem', 'matematika'];

    /**
     * An associative array that maps major field of study values to their mandatory subjects.
     */
    public array $majorMandatorySubjects = [
        MajorEnum::COMPUTER_SCIENCE->value => ['type' => true, 'subject' => 'matematika'],
        MajorEnum::ANGLISTICS->value => ['type' => 'emelt', 'subject' => 'angol'],
    ];

    /**
     * An associative array that maps major field of study values to their optional subjects.
     */
    public array $majorOptionalSubjects = [
        MajorEnum::COMPUTER_SCIENCE->value => ['biologia', 'fizika', 'informatika', 'kemia'],
        MajorEnum::ANGLISTICS->value => ['francia', 'nemet', 'olasz', 'oros', 'spanyol', 'tortenelem'],
    ];

    /**
     * Constructs a new instance of the `Major` class with the given `Applicant` object.
     *
     * @param Applicant $Applicant The `Applicant` object associated with this `Major` instance.
     */
    public function __construct(protected Applicant $Applicant)
    {
        /* Do nothing. */
    }

    /**
     * Gets the key for the current major.
     *
     * @return string The key for the current major.
     */
    public function getKey(): string
    {
        return Support::slugify(implode('_', $this->Applicant->get('valasztott-szak', [])));
    }

    /**
     * Gets the mandatory subject for the current major.
     *
     * @return string The mandatory subject for the current major.
     */
    public function getMandatorySubject(): string
    {
        return $this->majorMandatorySubjects[$this->getKey()]['subject'] ?? '';
    }

    /**
     * Gets the optional subjects for the current major.
     *
     * @return array The optional subjects for the current major.
     */
    public function getOptionalSubjects(): array
    {
        return $this->majorOptionalSubjects[$this->getKey()] ?? [];
    }

    /**
     * Examination of basic maturity criteria.
     */
    public function validateMandatorySubjects(): void
    {
        $subjects = array_map(
            [Support::class, 'slugify'],
            array_column($this->Applicant->get('erettsegi-eredmenyek', []), 'nev')
        );

        foreach (self::MANDATORY_SUBJECTS as $subject) {
            $subjectIndex = array_search($subject, $subjects);

            if ($subjectIndex !== false) {
                continue;
            }

            $this->Applicant->addMissingSubjects($subject);
        }
    }

    /**
     * Validates the mandatory subjects for the current major.
     */
    public function validateMajorMandatorySubjects(): void
    {
        $subjects = $this->Applicant->get('erettsegi-eredmenyek', []);
        $majorSubject = $this->majorMandatorySubjects[$this->getKey()] ?? [];

        if (empty($majorSubject)) {
            throw new \InvalidArgumentException('Invalid major.');
        }

        $criteria = $majorSubject['type'] === true ? ['emelt', 'kozep'] : [$majorSubject['type']];

        foreach ($subjects as $subject) {
            if (Support::slugify($subject['nev']) !== $majorSubject['subject']) {
                continue;
            }

            if (in_array(Support::slugify($subject['tipus']), $criteria)) {
                continue;
            }

            $this->Applicant->addMissingSubjects($majorSubject['subject']);

            break;
        }
    }

    /**
     * Examination of optional graduation requirements.
     */
    public function validateMajorOptionalSubjects(): void
    {
        $subjects = array_map(
            [Support::class, 'slugify'],
            array_column($this->Applicant->get('erettsegi-eredmenyek', []), 'nev')
        );

        $majorSubject = $this->majorOptionalSubjects[$this->getKey()] ?? [];

        if (empty($majorSubject)) {
            throw new \InvalidArgumentException('Invalid major.');
        }

        if (!empty(array_intersect($subjects, $majorSubject))) {
            return;
        }

        foreach ($majorSubject as $subject) {
            $this->Applicant->addMissingSubjects($subject);
        }
    }

    /**
     * Examination of the lowest percentage threshold.
     */
    public function validateLowerSubjects(): void
    {
        $subjects = $this->Applicant->get('erettsegi-eredmenyek', []);

        foreach ($subjects as $subject) {
            $result = (int) substr($subject['eredmeny'], 0, -1);

            if ($result >= 20) {
                continue;
            }

            $this->Applicant->addLowerSubjects($subject['nev']);
        }
    }
}
