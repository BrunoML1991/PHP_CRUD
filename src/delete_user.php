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
if (isset($argv)) {
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
}
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ('GET' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="delete_user.php" method="post">
                <label for="userId">Id del usuario</label>
                <input type="text" name="userId" id="userId">
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($_POST['userId']);
        if ($user === null) {
            echo 'Usuario no encontrado' . '<br><br><a href="delete_user.php">Volver a intentar</a>';
            exit(0);
        }
        try {
            $entityManager->remove($user);
            $entityManager->flush();
            echo 'Usuario borrado con ID #' . $_POST['userId'] . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}