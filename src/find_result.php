<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 06/12/2018
 * Time: 18:16
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

$resultsRepository = $entityManager->getRepository(Result::class);
if ($argc == 1 || $argc > 3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <ResultId>

MARCA_FIN;
    exit(0);
}
$result = $resultsRepository->find((int)$argv[1]);

if ($argc === 2) {
    echo PHP_EOL
        . sprintf(' %3s - %3s - %22s - %s', 'Id', 'res', 'username', 'time')
        . PHP_EOL;
    /* @var Result $result */
    echo sprintf('-%3s - %3s - %22s - %s',
        $result->getId(),
        $result->getResult(),
        $result->getUser(),
        $result->getTime()->format('Y-m-d H:i:s')
    );
} elseif (in_array('--json', $argv, true)) {
    echo json_encode($result, JSON_PRETTY_PRINT);
}