<?php declare(strict_types=1);

namespace App\Services;

use \App\Domain\Major;

/**
 * Represents an applicant with associated data, including their major, missing subjects, and lower subjects.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Applicant
{
    /**
     * The applicant's major, initialized to null.
     */
    protected null|Major $Major = null;

    /**
     * An array to store the missing subjects for the applicant.
     */
    private array $missingSubjects = [];

    /**
     * An array to store the lower subjects for the applicant.
     */
    private array $lowerSubjects = [];

    /**
     * Constructs an Applicant instance with the provided data.
     *
     * @param array $data The data associated with the applicant.
     */
    public function __construct(protected readonly array $data)
    {
        /* Do nothing. */
    }

    /**
     * Gets the applicant's major, initializing it if it hasn't been set yet.
     *
     * @return Major The applicant's major.
     */
    public function getMajor(): Major
    {
        if ($this->Major === null) {
            $this->Major = new Major($this);
        }

        return $this->Major;
    }

    /**
     * Gets the array of missing subjects for the applicant.
     *
     * @param bool $formatted Whether to format the output.
     *
     * @return array The array of missing subjects.
     */
    public function getMissingSubjects(bool $formatted = false): array
    {
        return $this->missingSubjects;
    }

    /**
     * Gets the array of lower subjects for the applicant.
     *
     * @param bool $formatted Whether to format the output.
     *
     * @return array The array of lower subjects.
     */
    public function getLowerSubjects(bool $formatted = false): array
    {
        return $this->lowerSubjects;
    }

    /**
     * Sets the array of missing subjects for the applicant.
     *
     * @param array $subjects The array of missing subjects.
     */
    public function setMissingSubjects(array $subjects): void
    {
        $this->missingSubjects = $subjects;
    }

    /**
     * Sets the array of lower subjects for the applicant.
     *
     * @param array $subjects The array of lower subjects.
     */
    public function setLowerSubjects(array $subjects): void
    {
        $this->lowerSubjects = $subjects;
    }

    /**
     * Adds a missing subject for the applicant.
     *
     * @param string $subject The missing subject to add.
     */
    public function addMissingSubjects(string $subject): void
    {
        $this->missingSubjects[] = $subject;
    }

    /**
     * Adds a lower subject for the applicant.
     *
     * @param string $subject The lower subject to add.
     */
    public function addLowerSubjects(string $subject): void
    {
        $this->lowerSubjects[] = $subject;
    }

    /**
     * Checks if the applicant has any missing subjects.
     *
     * @return bool True if the applicant has missing subjects, false otherwise.
     */
    public function hasMissingSubjects(): bool
    {
        return !empty($this->missingSubjects);
    }

    /**
     * Checks if the applicant has any lower subjects.
     *
     * @return bool True if the applicant has lower subjects, false otherwise.
     */
    public function hasLowerSubjects(): bool
    {
        return !empty($this->lowerSubjects);
    }

    /**
     * Gets the value of the specified key from the data array, or returns the default value if the key does not exist.
     *
     * @param string $key The key to retrieve the value for.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return mixed The value of the specified key, or the default value if the key does not exist.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}
