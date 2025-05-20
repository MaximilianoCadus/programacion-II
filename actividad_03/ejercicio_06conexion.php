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

    public function createUser($email, $estado) {
        try {
            $query = "INSERT INTO usuarios (email, estado) VALUES (:email, :estado)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function updateUserState($id, $estado) {
        try {
            $query = "UPDATE usuarios SET estado = :estado WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al actualizar el estado del usuario: " . $e->getMessage();
            return false;
        }
    }
}