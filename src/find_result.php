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
if (isset($argv)) {
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
}
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ('GET' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="find_result.php" method="post">
                <label for="resultId">Id del resultado</label>
                <input type="text" name="resultId" id="resultId">
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;

    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        /** @var Result $user */
        $result = $entityManager->getRepository(Result::class)->find($_POST['resultId']);
        if ($result === null) {
            echo 'Resultado no encontrado' . '<br><br><a href="index.php">Índice</a>';
            exit(0);
        }
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
        $table_row = '<tr>' .
            '<td>'.$result->getId().'</td>'.
            '<td>'.$result->getResult().'</td>'.
            '<td>'.$result->getUser().'</td>'.
            '<td>'.$result->getTime()->format('Y-m-d H:i:s').'</td>'.'</tr>';
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