<?php declare(strict_types=1);

namespace App\Factories;

use \App\Services\Applicant;

/**
 * Provides a factory for creating Applicant instances from various data sources.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class ApplicantFactory
{
    /**
     * Creates an array of Applicant instances from the global scope.
     *
     * @return Applicant[]
     */
    public static function createFromGlobal(): array
    {
        $data = [];

        foreach ($GLOBALS as $key => $value) {
            if (!str_starts_with($key, 'exampleData')) {
                continue;
            }

            $data[$key] = self::createFromArray($value);
        }

        return $data;
    }

    /**
     * Creates an Applicant instance from the provided array data.
     *
     * @param array $data The data to use for creating the Applicant instance.
     *
     * @return Applicant The created Applicant instance.
     */
    public static function createFromArray(array $data): Applicant
    {
        return new Applicant($data);
    }
}
