<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 05/12/2018
 * Time: 18:55
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

if ($argc !== 3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <ResultId> <Result>
    Example: $fich 1 10

MARCA_FIN;
    exit(0);
}
$result = new Result();
$result = $entityManager->getRepository(Result::class)->find($argv[1]);
if ($result === null) {
    echo 'No result exists with this ResultId';
    exit(0);
}
$result->setResult((int)$argv[2]);
try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo 'Updated User with ID #' . $result->getId() . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}