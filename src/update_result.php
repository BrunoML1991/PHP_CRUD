<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 05/12/2018
 * Time: 18:55
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
    if ($argc !== 3) {
        $fich = basename(__FILE__);
        echo <<< MARCA_FIN

    Usage: $fich <ResultId> <Result>
    Example: $fich 1 10

MARCA_FIN;
        exit(0);
    }
    $result = new Result();
    $result = $entityManager->getRepository(Result::class)->find($argv[1]);
    if ($result === null) {
        echo 'No result exists with this ResultId';
        exit(0);
    }
    $result->setResult((int)$argv[2]);
    try {
        $entityManager->persist($result);
        $entityManager->flush();
        echo 'Updated User with ID #' . $result->getId() . PHP_EOL;
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
            <form action="update_result.php" method="post">
                <label for="resultId">Id del resultado</label>
                <input type="text" name="resultId" id="resultId">
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD') && isset($_POST['resultId'])) {
        /** @var Result $result */
        $result = $entityManager->getRepository(Result::class)->find($_POST['resultId']);
        if ($result === null) {
            echo 'Resultado no encontrado con ID: ' . $_POST['resultId'] . '<br><br><a href="update_result.php">Volver a intentarlo</a>';
            exit(0);
        }
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="update_result.php" method="post">
                <label for="id">Id del resultado</label>
                <input type="text" name="id" id="id" value="{$result->getId()}" readonly>
                <br>
                <label for="result">Valor del resultado</label>
                <input type="text" name="result" id="result" required value="{$result->getResult()}">
                <br>
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        /** @var Result $result */
        $result = $entityManager->getRepository(Result::class)->find($_POST['id']);
        $result->setResult($_POST['result']);
        $result->setTime(new DateTime('now'));
        try {
            $entityManager->persist($result);
            $entityManager->flush();
            echo 'Resultado actualizado con ID #' . $result->getId() . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}