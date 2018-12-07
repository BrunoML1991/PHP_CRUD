<?php
/**
 * PHP version 7.2
 * src/list_results.php
 *
 * @category Scripts
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
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
$results = $resultsRepository->findAll();

if (isset($argv)) {
    if ($argc === 1) {
        echo PHP_EOL
            . sprintf('%3s - %3s - %22s - %s', 'Id', 'res', 'username', 'time')
            . PHP_EOL;
        $items = 0;
        /* @var Result $result */
        foreach ($results as $result) {
            echo $result . PHP_EOL;
            $items++;
        }
        echo PHP_EOL . "Total: $items results.";
    } elseif (in_array('--json', $argv, true)) {
        echo json_encode($results, JSON_PRETTY_PRINT);
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
            <td>Resultado</td>
            <td>Nombre de usuario</td>
            <td>Fecha de publicación</td>
        </tr>

MARCA_TABLA;
    /* @var Result $result */
    foreach ($results as $result) {
        $table_row = '<tr>'.
            '<td>'.$result->getId().'</td>'.
            '<td>'.$result->getResult().'</td>'.
            '<td>'.$result->getUser().'</td>'.
            '<td>'.$result->getTime()->format('Y-m-d H:i:s').'</td>'.'</tr>';
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
