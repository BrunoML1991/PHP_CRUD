<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 04/12/2018
 * Time: 18:34
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
if (isset($argv)) {
    if ($argc == 1 || $argc > 2) {
        $fich = basename(__FILE__);
        echo <<< MARCA_FIN

    Usage: $fich <ResultId>

MARCA_FIN;
        exit(0);
    }
    $result = new Result();
    $result = $entityManager->getRepository(Result::class)->find((int)$argv[1]);
    try {
        $entityManager->remove($result);
        $entityManager->flush();
        echo 'Deleted Result with ID #' . (int)$argv[1] . PHP_EOL;
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
            <form action="delete_result.php" method="post">
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
            echo 'Resultado no encontrado' . '<br><br><a href="delete_result.php">Volver a intentarlo</a>';
            exit(0);
        }
        $result = new Result();
        $result = $entityManager->getRepository(Result::class)->find($_POST['resultId']);
        try {
            $entityManager->remove($result);
            $entityManager->flush();
            echo 'Resultado borrado con ID #' . $_POST['resultId'] . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}