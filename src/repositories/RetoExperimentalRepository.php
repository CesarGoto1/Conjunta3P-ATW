<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Interfaces\RepositoryInterface;
use App\Entities\RetoExperimental;
use PDO;
use PDOException;

class RetoExperimentalRepository implements RepositoryInterface {
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_reto_experimental_list()");
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reto = new RetoExperimental(
                    $row['id'], 
                    $row['descripcion'], 
                    $row['enfoque_pedagogico']
                );
                $resultados[] = $reto;
            }
            return $resultados;
        } catch (PDOException $e) {
            // Log el error si es necesario
            return [];
        }
    }

    public function findById(int $id): ?RetoExperimental
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_reto_experimental_find_id(:id)");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }

            return new RetoExperimental(
                $row['id'],
                $row['descripcion'],
                $row['enfoque_pedagogico']
            );
        } catch (PDOException $e) {
            // Log el error si es necesario
            return null;
        }
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof RetoExperimental) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare("CALL sp_create_reto_experimental(:descripcion, :enfoque_pedagogico)");
            $descripcion = $entity->getDescripcion();
            $enfoquePedagogico = $entity->getEnfoquePedagogico();
            
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':enfoque_pedagogico', $enfoquePedagogico, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['reto_experimental_id'])) {
                $entity->setId($result['reto_experimental_id']);
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            // Log el error si es necesario
            return false;
        }
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof RetoExperimental) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare("CALL sp_update_reto_experimental(:id, :descripcion, :enfoque_pedagogico)");
            
            $id = $entity->getId();
            $descripcion = $entity->getDescripcion();
            $enfoquePedagogico = $entity->getEnfoquePedagogico();
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':enfoque_pedagogico', $enfoquePedagogico, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result && isset($result['id_updated_reto_experimental']);
        } catch (PDOException $e) {
            // Log el error si es necesario
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_delete_reto_experimental(:id)");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && isset($result['OK']) && $result['OK'] === 1;
        } catch (PDOException $e) {
            // Log el error si es necesario
            return false;
        }
    }
}