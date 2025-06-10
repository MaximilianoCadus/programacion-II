<?php

class Database {
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

     public function getPdo() {
        return $this->pdo;
    }

    public function createUser($UUID, $firstName, $lastName) {
        try {
            $query = "INSERT INTO users (UUID, firstName, lastName) VALUES (:UUID, :firstName, :lastName)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function getUserByUUID($UUID) {
        try {
            $query = "SELECT * FROM users WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function updateUserName($UUID, $firstName) {
        try {
            $query = "UPDATE users SET firstName = :firstName WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al actualizar el nombre del usuario: " . $e->getMessage();
            return false;
        }
    }

    public function updateUserLastName($UUID, $lastName) {
        try {
            $query = "UPDATE users SET lastName = :lastName WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al actualizar el apellido del usuario: " . $e->getMessage();
            return false;
        }
    }

    public function deleteUser($UUID) {
        try {
            $query = "DELETE FROM users WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al eliminar el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT * FROM users";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener todos los usuarios: " . $e->getMessage();
            return false;
        }
    }
}