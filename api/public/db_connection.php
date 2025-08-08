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

    public function createUser($UUID, $firstName, $lastName, $email) {
        try {
            $query = "INSERT INTO usuarios (UUID, firstName, lastName, email) VALUES (:UUID, :firstName, :lastName, :email)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function getUserByUUID($UUID) {
        try {
            $query = "SELECT * FROM usuarios WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function updateUserName($UUID, $firstName) {
        try {
            $query = "UPDATE usuarios SET firstName = :firstName WHERE UUID = :UUID";
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
            $query = "UPDATE usuarios SET lastName = :lastName WHERE UUID = :UUID";
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

    public function updateUserEmail($UUID, $email) {
        try {
            $query = "UPDATE usuarios SET email = :email WHERE UUID = :UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':UUID', $UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error al actualizar el email del usuario: " . $e->getMessage();
            return false;
        }
    }

    public function deleteUser($UUID) {
        try {
            $query = "DELETE FROM usuarios WHERE UUID = :UUID";
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
            $query = "SELECT * FROM usuarios";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener todos los usuarios: " . $e->getMessage();
            return false;
        }
    }

    public function createResource($nombre, $descripcion, $capacidad) {
        try {
            $query = "INSERT INTO recursos (nombre, descripcion, capacidad) VALUES (:nombre, :descripcion, :capacidad)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear recurso: " . $e->getMessage());
            return false;
        }
    }

    public function getResource($id) {
        try {
            $query = "SELECT * FROM recursos WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener recurso: " . $e->getMessage());
            return false;
        }
    }

    public function createReservation($recurso_id, $usuario_UUID, $fecha_inicio, $fecha_fin) {
        try {
            $query = "INSERT INTO reservas (recurso_id, usuario_UUID, fecha_inicio, fecha_fin) 
                      VALUES (:recurso_id, :usuario_UUID, :fecha_inicio, :fecha_fin)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_UUID', $usuario_UUID, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear reserva: " . $e->getMessage());
            return false;
        }
    }

    public function getReservationsByUser($usuario_UUID) {
        try {
            $query = "SELECT * FROM reservas WHERE usuario_UUID = :usuario_UUID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':usuario_UUID', $usuario_UUID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener reservas: " . $e->getMessage());
            return false;
        }
    }

    public function checkResourceAvailability($recurso_id, $fecha_inicio, $fecha_fin) {
        try {
            $query = "SELECT COUNT(*) FROM reservas 
                      WHERE recurso_id = :recurso_id 
                      AND (
                          (:fecha_inicio BETWEEN fecha_inicio AND fecha_fin)
                          OR (:fecha_fin BETWEEN fecha_inicio AND fecha_fin)
                          OR (fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin)
                      )";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            return $stmt->fetchColumn() == 0;
        } catch (PDOException $e) {
            error_log("Error en disponibilidad: " . $e->getMessage());
            return false;
        }
    }

    public function confirmReservation($reserva_id) {
        try {
            $query = "UPDATE reservas SET estado = 'confirmada' WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $reserva_id, PDO::PARAM_INT);
            $stmt->execute();
            $this->simulateNotification($reserva_id);
            return true;
        } catch (PDOException $e) {
            error_log("Error al confirmar reserva: " . $e->getMessage());
            return false;
        }
    }

    private function simulateNotification($reserva_id) {
        error_log("Notificación enviada para reserva ID: $reserva_id");
        return true;
    }

    public function getAllResources() {
        try {
            $query = "SELECT * FROM recursos";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener recursos: " . $e->getMessage());
            return false;
        }
    }

    public function getAvailableResources($fechaInicio, $fechaFin) {
        try {
            $query = "SELECT r.* FROM recursos r
                    WHERE NOT EXISTS (
                        SELECT 1 FROM reservas res
                        WHERE res.recurso_id = r.id
                        AND res.estado IN ('pendiente', 'confirmada')
                        AND (
                        (res.fecha_inicio <= :fecha_fin AND res.fecha_fin >= :fecha_inicio)
                        )
                    )";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':fecha_inicio' => $fechaInicio,
                ':fecha_fin' => $fechaFin
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en disponibilidad: " . $e->getMessage());
            return false;
        }
    }

    public function updateResourceName($id, $nombre) {
        try {
            $query = "UPDATE recursos SET nombre = :nombre WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar nombre de recurso: " . $e->getMessage());
            return false;
        }
    }

    public function updateResourceDescription($id, $descripcion) {
        try {
            $query = "UPDATE recursos SET descripcion = :descripcion WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar descripción de recurso: " . $e->getMessage());
            return false;
        }
    }

    public function updateResourceCapacity($id, $capacidad) {
        try {
            $query = "UPDATE recursos SET capacidad = :capacidad WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar capacidad de recurso: " . $e->getMessage());
            return false;
        }
    }

    public function hasActiveReservations($recurso_id) {
        try {
            $query = "SELECT COUNT(*) FROM reservas 
                    WHERE recurso_id = :recurso_id 
                    AND estado IN ('pendiente', 'confirmada')";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar reservas activas: " . $e->getMessage());
            return true; 
        }
    }

    public function deleteResource($id) {
        try {
            $query = "DELETE FROM recursos WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar recurso: " . $e->getMessage());
            return false;
        }
    }

    public function getReservation($id) {
        try {
            $query = "SELECT * FROM reservas WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener reserva: " . $e->getMessage());
            return false;
        }
    }

    public function checkResourceAvailabilityExcluding($recurso_id, $fecha_inicio, $fecha_fin, $exclude_id) {
        try {
            $query = "SELECT COUNT(*) FROM reservas 
                    WHERE recurso_id = :recurso_id 
                    AND id != :exclude_id
                    AND estado IN ('pendiente', 'confirmada')
                    AND (
                        (:fecha_inicio BETWEEN fecha_inicio AND fecha_fin)
                        OR (:fecha_fin BETWEEN fecha_inicio AND fecha_fin)
                        OR (fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin)
                    )";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id, PDO::PARAM_INT);
            $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            return $stmt->fetchColumn() == 0;
        } catch (PDOException $e) {
            error_log("Error en disponibilidad (excluyendo): " . $e->getMessage());
            return false;
        }
    }

    public function updateReservationResource($id, $recurso_id) {
        try {
            $query = "UPDATE reservas SET recurso_id = :recurso_id WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar recurso de reserva: " . $e->getMessage());
            return false;
        }
    }

    public function updateReservationStart($id, $fecha_inicio) {
        try {
            $query = "UPDATE reservas SET fecha_inicio = :fecha_inicio WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar fecha inicio de reserva: " . $e->getMessage());
            return false;
        }
    }

    public function updateReservationEnd($id, $fecha_fin) {
        try {
            $query = "UPDATE reservas SET fecha_fin = :fecha_fin WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar fecha fin de reserva: " . $e->getMessage());
            return false;
        }
    }

    public function deleteReservation($id) {
        try {
            $query = "DELETE FROM reservas WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar reserva: " . $e->getMessage());
            return false;
        }
    }

}