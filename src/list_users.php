<?php
/**
 * PHP version 7.2
 * src/list_users.php
 *
 * @category Scripts
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

$userRepository = $entityManager->getRepository(User::class);
$users = $userRepository->findAll();

if (isset($argv)) {
    if (in_array('--json', $argv, true)) {
        echo json_encode($users, JSON_PRETTY_PRINT);
    } else {
        $items = 0;
        echo PHP_EOL . sprintf(
                '  %2s: %20s %30s %7s' . PHP_EOL,
                'Id', 'Username:', 'Email:', 'Enabled:'
            );
        /** @var User $user */
        foreach ($users as $user) {
            echo sprintf(
                '- %2d: %20s %30s %7s',
                $user->getId(),
                $user->getUsername(),
                $user->getEmail(),
                ($user->isEnabled()) ? 'true' : 'false'
            ),
            PHP_EOL;
            $items++;
        }

        echo "\nTotal: $items users.\n\n";
    }
}
if (isset($_SERVER['REQUEST_METHOD'])) {
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
    /** @var User $user */
    foreach ($users as $user) {
        $table_row = '<tr>'.
            '<td>'.$user->getId().'</td>'.
            '<td>'.$user->getUsername().'</td>'.
            '<td>'.$user->getEmail().'</td>'.
            '<td>'.$user->isEnabled().'</td>'.'</tr>';
        $tabla .= $table_row;
    }
    $fin_tabla = <<< MARCA_TABLA_FIN
</table>
<br>
<a href="index.php">Índice</a>
</body>
</html>
MARCA_TABLA_FIN;

    echo $tabla . $fin_tabla;
}