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
if (isset($argv)) {
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
}
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ('GET' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="update_user.php" method="post">
                <label for="userId">Id del usuario</label>
                <input type="text" name="userId" id="userId">
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD') && isset($_POST['userId'])) {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($_POST['userId']);
        if ($user === null) {
            echo 'Usuario no encontrado con ID: ' . $_POST['userId'] . '<br><br><a href="update_user.php">Volver a intentarlo</a>';
            exit(0);
        }
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="update_user.php" method="post">
                <label for="id">Id del usuario</label>
                <input type="text" name="id" id="id" value="{$user->getId()}" readonly>
                <br>
                <label for="userName">Nombre de usuario</label>
                <input type="text" name="userName" id="userName" required value="{$user->getUsername()}">
                <br>
                <label for="email">Dirección de correo electrónico</label>
                <input type="text" name="email" id="email" required value="{$user->getEmail()}">
                <br>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required value="{$user->getPassword()}">
                <br>
                <label>¿Será administrador?</label>
                <input type="text" name="isAdmin" id="isAdmin" value="{$user->isAdmin()}">
                <br>
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($_POST['id']);
        $user->setUsername($_POST['userName']);
        $user->setEmail($_POST['email']);
        if ($user->getPassword()!==$_POST['password']){
            $user->setPassword($_POST['password']);
        }
        $user->setIsAdmin($_POST['isAdmin']);
        try {
            $entityManager->persist($user);
            $entityManager->flush();
            echo 'Usuario actualizado con ID #' . $user->getId() . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}