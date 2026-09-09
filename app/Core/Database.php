<?php
declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host    = $_ENV['DB_HOST']    ?? 'localhost';
            $name    = $_ENV['DB_NAME']    ?? 'highqhomes';
            $user    = $_ENV['DB_USER']    ?? 'root';
            $pass    = $_ENV['DB_PASS']    ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ]);
            } catch (\PDOException $e) {
                $hint = 'Cannot connect to MySQL database "' . $name . '" as user "' . $user . '". Check DB_HOST, DB_NAME, DB_USER, and DB_PASS in .env. ' . $e->getMessage();
                if (class_exists('Production')) {
                    Production::log($hint);
                }
                throw new \PDOException($hint, (int)$e->getCode(), $e);
            }
        }

        return self::$instance;
    }
}
