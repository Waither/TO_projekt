<?php

namespace Monitoring;

use PDO;
use PDOException;

class Database {
    private PDO $connection;

    public function __construct(string $host = 'mysql', string $dbname = 'to_projekt', string $user = 'root', string $password = 'rootpassword') {
        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: {$e->getMessage()}");
        }
    }

    public function getAllDevices(): array {
        $query = "SELECT * FROM tb_device WHERE tracked = 1";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDeviceById(int $id): ?array {
        $query = "SELECT * FROM tb_device WHERE ID = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function addDevice(string $ip): ?int {
        $query = "SELECT ID FROM tb_device WHERE IP = :ip";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':ip', $ip, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id = $result['ID'];
            $updateQuery = "UPDATE tb_device SET tracked = 1 WHERE ID = :id";
            $updateStatement = $this->connection->prepare($updateQuery);
            $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);
            $updateStatement->execute();
            return $id;
        }

        return null;
    }

    public function deleteDevice(int $id): bool {
        $query = "UPDATE tb_device SET tracked = 0 WHERE ID = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        return $statement->execute();
    }
}