<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 04/12/2018
 * Time: 19:00
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

if ($argc < 3 || $argc > 6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <UserId> [<u:Username>] [<e:Email>] [<p:Password>] [<a:1|0>]
    Example: $fich 1 u:galo e:example@example.com p:example a:0

MARCA_FIN;
    exit(0);
}
if ($argv[1] < 1) {
    echo 'First argument has to be UserId';
    exit(0);
}
$user = new User();
$user = $entityManager->getRepository(User::class)->find($argv[1]);
if ($user === null) {
    echo 'No user exists with this UserId';
    exit(0);
}
for ($i = 2; $i < $argc; $i++) {
    $change = explode(':', (string)$argv[$i]);
    if ($change[0] === 'u') {
        $user->setUsername($change[1]);
    } elseif ($change[0] === 'e') {
        $user->setEmail($change[1]);
    } elseif ($change[0] === 'p') {
        $user->setPassword((string)$change[1]);
    } elseif ($change[0] === 'a') {
        $user->setIsAdmin((boolean)$change[1]);
    }
}

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Updated User with ID #' . $user->getId() . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}