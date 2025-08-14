<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Entities\EquipoReto;
use App\Interfaces\RepositoryInterface;
use PDO;

class EquipoRetoRepository implements RepositoryInterface
{
    private PDO $db;
    public function __construct(){
        $this->db = Database::getConnection();
    }

    // CREATE ASIGNACIÓN EQUIPO-RETO
    public function create(object $entity): bool {
        if(!$entity instanceof EquipoReto){
            throw new \InvalidArgumentException('EquipoReto Expected');
        }

        $stmt = $this->db->prepare("CALL sp_create_equipo_reto(
            :equipo_id,
            :reto_id
        )");

        $ok = $stmt->execute([
            ':equipo_id' => $entity->getEquipoId(),
            ':reto_id' => $entity->getRetoId()
        ]);

        $stmt->closeCursor();
        return $ok;
    }

    // FIND BY ID (no aplica para clave compuesta)
    public function findById(int $id): ?object {
        return null;
    }

    // UPDATE ASIGNACIÓN EQUIPO-RETO
    public function update(object $entity): bool {
        if(
            !$entity instanceof EquipoReto ||
            !property_exists($entity, 'old_equipo_id') ||
            !property_exists($entity, 'old_reto_id')
        ){
            return false;
        }

        $stmt = $this->db->prepare("CALL sp_update_equipo_reto(
            :old_equipo_id,
            :old_reto_id,
            :new_equipo_id,
            :new_reto_id
        )");

        $ok = $stmt->execute([
            ':old_equipo_id' => $entity->old_equipo_id,
            ':old_reto_id' => $entity->old_reto_id,
            ':new_equipo_id' => $entity->getEquipoId(),
            ':new_reto_id' => $entity->getRetoId()
        ]);

        $stmt->closeCursor();
        return $ok;
    }

    // DELETE ASIGNACIÓN EQUIPO-RETO (no aplica para clave compuesta)
    public function delete(int $id): bool {
        return false;
    }

    // ELIMINAR ASIGNACIÓN POR CLAVE COMPUESTA
    public function deleteByEquipoReto(int $equipo_id, int $reto_id): bool {
        $stmt = $this->db->prepare("CALL sp_delete_equipo_reto(:equipo_id, :reto_id)");
        $ok = $stmt->execute([
            ':equipo_id' => $equipo_id,
            ':reto_id' => $reto_id
        ]);
        $stmt->closeCursor();
        return $ok;
    }

    // FIND ALL
    public function findAll(): array {
        $stmt = $this->db->query("CALL sp_equipo_reto_list();");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $out = [];
        foreach($rows as $row){
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    // Buscar asignación específica por equipo y reto
    public function findByEquipoReto(int $equipo_id, int $reto_id): ?EquipoReto {
        $stmt = $this->db->prepare("CALL sp_equipo_reto_find(:equipo_id, :reto_id)");
        $stmt->execute([
            ':equipo_id' => $equipo_id,
            ':reto_id' => $reto_id
        ]);
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $row): EquipoReto {
        return new EquipoReto(
            (int)$row['equipo_id'],
            (int)$row['reto_id']
        );
    }
}