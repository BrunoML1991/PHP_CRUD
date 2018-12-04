<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 04/12/2018
 * Time: 18:34
 */

use MiW\Results\Entity\Result;
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
$dotenv = new \Dotenv\Dotenv(
    __DIR__ . '/..',
    Utils::getEnvFileName(__DIR__ . '/..')
);
$dotenv->load();

$entityManager = Utils::getEntityManager();
if ($argc == 1 || $argc > 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <ResultId>

MARCA_FIN;
    exit(0);
}
$result = new Result();
$result = $entityManager->getRepository(Result::class)->find((int)$argv[1]);
try {
    $entityManager->remove($result);
    $entityManager->flush();
    echo 'Deleted Result with ID #' . (int)$argv[1] . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}