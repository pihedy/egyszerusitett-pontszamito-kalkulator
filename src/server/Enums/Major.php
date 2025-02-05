<?php declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the major field of study for a student.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
enum Major: string
{
    case COMPUTER_SCIENCE = 'elte_ik_programtervezo_informatikus';

    case ANGLISTICS = 'ppke_btk_anglisztika';
}
