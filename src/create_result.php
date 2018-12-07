<?php
/**
 * PHP version 7.2
 * src\create_result.php
 *
 * @category Utils
 * @package  MiW\Results
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\Result;
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
    if ($argc < 3 || $argc > 4) {
        $fich = basename(__FILE__);
        echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
        exit(0);
    }

    $newResult = (int)$argv[1];
    $userId = (int)$argv[2];
    $newTimestamp = $argv[3] ?? new DateTime('now');

    /** @var User $user */
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['id' => $userId]);
    if (null === $user) {
        echo "Usuario $userId no encontrado" . PHP_EOL;
        exit(0);
    }

    $result = new Result($newResult, $user, $newTimestamp);
    try {
        $entityManager->persist($result);
        $entityManager->flush();
        echo 'Created Result with ID ' . $result->getId()
            . ' USER ' . $user->getUsername() . PHP_EOL;
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
}
if (isset($_SERVER['REQUEST_METHOD'])) {
    if ('GET' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        echo <<<MARCA_FORM
            <!doctype html>
            <html lang="sp">
            <body>
            <form action="create_result.php" method="post">
                <label for="result">Resultado</label>
                <input type="number" name="result" id="result" required>
                <br>
                <label for="userId">Id del usuario al que se aplica este resultado</label>
                <input type="number" name="userId" id="userId" required>
                <br>
                <input type="submit">
            </form>
            <br>
            <a href="index.php">Índice</a>
            </body>
            </html>
MARCA_FORM;
    } elseif ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
        $newResult = (int)$_POST['result'];
        $userId = (int)$_POST['userId'];
        $newTimestamp = new DateTime('now');
        /** @var User $user */
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['id' => $userId]);
        if (null === $user) {
            echo 'Usuario con ID ' . $userId .' no encontrado'. '<br><br><a href="create_result.php">Volver a intentar</a>';
            exit(0);
        }
        $result = new Result($newResult, $user, $newTimestamp);
        try {
            $entityManager->persist($result);
            $entityManager->flush();
            echo 'Resultado creado con ID ' . $result->getId()
                . ' para el usuario ' . $user->getUsername() . '<br><br><a href="index.php">Índice</a>';
        } catch (Exception $exception) {
            echo $exception->getMessage() . '<br><br><a href="index.php">Índice</a>';
        }
    }
}
