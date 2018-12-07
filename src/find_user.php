<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 06/12/2018
 * Time: 18:07
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

$userRepository = $entityManager->getRepository(User::class);
$entityManager = Utils::getEntityManager();
if (isset($argv)) {
    if ($argc == 1 || $argc > 3) {
        $fich = basename(__FILE__);
        echo <<< MARCA_FIN

    Usage: $fich <UserId>

MARCA_FIN;
        exit(0);
    }
    $user = $entityManager->getRepository(User::class)->find((int)$argv[1]);

    if (in_array('--json', $argv, true)) {
        echo json_encode($user, JSON_PRETTY_PRINT);
    } else {
        echo PHP_EOL . sprintf(
                '  %2s: %20s %30s %7s' . PHP_EOL,
                'Id', 'Username:', 'Email:', 'Enabled:'
            );
        /** @var User $user */

        echo sprintf(
            '- %2d: %20s %30s %7s',
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            ($user->isEnabled()) ? 'true' : 'false'
        ),
        PHP_EOL;
    }
}
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ('GET' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="find_user.php" method="post">
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
            echo 'Usuario no encontrado' . '<br><br><a href="index.php">Índice</a>';
            exit(0);
        }
        $tabla = <<< MARCA_TABLA
            <!doctype html>
            <html lang="sp">
            <body>
            <table style="text-align: center">
                <tr>
                    <td>Id</td>
                    <td>Nombre de usuario</td>
                    <td>E-mail</td>
                    <td>Habilitado</td>
                </tr>
MARCA_TABLA;
        $table_row = '<tr>' .
            '<td>' . $user->getId() . '</td>' .
            '<td>' . $user->getUsername() . '</td>' .
            '<td>' . $user->getEmail() . '</td>' .
            '<td>' . $user->isEnabled() . '</td>' . '</tr>';
        $tabla .= $table_row;

        $fin_tabla = <<< MARCA_TABLA_FIN
            </table>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_TABLA_FIN;

        echo $tabla . $fin_tabla;
    }
}