<?php declare(strict_types=1);

/**
 * @author Pihe Edmond <pihedy@gmail.com>
 */

try {
    /**
     * Autoload files using Composer autoload
     */
    require_once __DIR__ . '/src/vendor/autoload.php';

    if (!file_exists(__DIR__ . '/data/homework_input.php')) {
        throw new \Exception('Please create homework_input.php file in data folder');
    }

    /**
     * Include homework_input.php file.
     */
    include __DIR__ . '/data/homework_input.php';

    /**
     * @var array Required configuration data.
     */
    $requirements = include __DIR__ . '/config/requirements.php' ?? [];

    /**
     * Loads the words configuration to the `$words` property of the `\App\Utils\Support` static class.
     */
    \App\Utils\Support::$words = include __DIR__ . '/config/words.php' ?? [];

    /**
     * Calculates the data provided requirements.
     */
    $data = (new \App\Calculator\PointCalculator($requirements))->calculate();

    /**
     * Prints the calculated data in a formatted manner.
     */
    foreach ($data as $key => $value) {
        echo "[\033[1;32m$key\033[0m] => \033[1;34m$value\033[0m\n";
    }
} catch (\Exception $Exception) {
    echo $Exception->getMessage();
}
