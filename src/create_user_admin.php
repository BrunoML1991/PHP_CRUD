<?php
/**
 * PHP version 7.2
 * src\create_user_admin.php
 *
 * @category Utils
 * @package  MiW\Results
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
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
    if ($argc < 4 || $argc > 5) {
        $fich = basename(__FILE__);
        echo <<< MARCA_FIN

    Usage: $fich <Username> <Email> <Password> [<IsAdmin>]

MARCA_FIN;
        exit(0);
    }

    $user = new User();
    $user->setUsername((string)$argv[1]);
    $user->setEmail((string)$argv[2]);
    $user->setPassword((string)$argv[3]);
    $user->setEnabled(true);
    $user->setIsAdmin($argv[4] ?? true);

    try {
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'Created User with ID #' . $user->getId() . PHP_EOL;
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
            <form action="create_user_admin.php" method="post">
                <label for="userName">Nombre de usuario</label>
                <input type="text" name="userName" id="userName" required>
                <br>
                <label for="email">Dirección de correo electrónico</label>
                <input type="text" name="email" id="email" required>
                <br>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
                <br>
                <label>¿Será administrador?</label>
                <input type="checkbox" name="isAdmin" id="isAdmin">
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
        $user = new User();
        $user->setUsername((string)$_POST['userName']);
        $user->setEmail((string)$_POST['email']);
        $user->setPassword((string)$_POST['password']);
        $user->setEnabled(true);
        $user->setIsAdmin($_POST['isAdmin'] ?? false);

        try {
            $entityManager->persist($user);
            $entityManager->flush();
            echo 'Usuario creado con ID #' . $user->getId() . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}
