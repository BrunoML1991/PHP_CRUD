<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 04/12/2018
 * Time: 18:04
 */

use MiW\Results\Entity\User;
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

    Usage: $fich <UserId>

MARCA_FIN;
    exit(0);
}
$user = new User();
$user = $entityManager->getRepository(User::class)->find((int)$argv[1]);
try {
    $entityManager->remove($user);
    $entityManager->flush();
    echo 'Deleted User with ID #' . (int)$argv[1] . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}