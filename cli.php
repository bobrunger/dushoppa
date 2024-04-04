<?php

require __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;
use PDO, PDOException, PDOStatement;


class Database
{
    private PDO $connection;
    private PDOStatement $stmt;
    public function __construct(string $driver, array $config, string $username, string $password)
    {


        $config = http_build_query(data: $config, arg_separator: ";");

        $dsn = "{$driver}:{$config}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("unable to connect to database");
        }
    }

    public function query(string $query, array $params = []): Database
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($params);

        return $this;
    }
}


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load(); // env variables accessible to app, $_ENV will be populated


$db = new Database($_ENV['DB_DRIVER'], [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME']
], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$sqlFile = file_get_contents("database.sql");

$db->query($sqlFile);
