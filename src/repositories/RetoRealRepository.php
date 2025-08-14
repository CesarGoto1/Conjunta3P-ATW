<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Interfaces\RepositoryInterface;
use App\Entities\RetoReal;
use PDO;
use PDOException;

class RetoRealRepository implements RepositoryInterface {
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_reto_real_list()");
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reto = new RetoReal(
                    $row['id'], 
                    $row['descripcion'], 
                    $row['entidad_colaboradora']
                );
                $resultados[] = $reto;
            }
            return $resultados;
        } catch (PDOException $e) {
            // Log el error si es necesario
            return [];
        }
    }

    public function findById(int $id): ?RetoReal
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_reto_real_find_id(:id)");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }

            return new RetoReal(
                $row['id'],
                $row['descripcion'],
                $row['entidad_colaboradora']
            );
        } catch (PDOException $e) {
            // Log el error si es necesario
            return null;
        }
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof RetoReal) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare("CALL sp_create_reto_real(:descripcion, :entidad_colaboradora)");
            $descripcion = $entity->getDescripcion();
            $entidadColaboradora = $entity->getEntidadColaboradora();
            
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':entidad_colaboradora', $entidadColaboradora, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['reto_real_id'])) {
                $entity->setId($result['reto_real_id']);
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
        if (!$entity instanceof RetoReal) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare("CALL sp_update_reto_real(:id, :descripcion, :entidad_colaboradora)");
            
            $id = $entity->getId();
            $descripcion = $entity->getDescripcion();
            $entidadColaboradora = $entity->getEntidadColaboradora();
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':entidad_colaboradora', $entidadColaboradora, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result && isset($result['id_updated_reto_real']);
        } catch (PDOException $e) {
            // Log el error si es necesario
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare("CALL sp_delete_reto_real(:id)");
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
