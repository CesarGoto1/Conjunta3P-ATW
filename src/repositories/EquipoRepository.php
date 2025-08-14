<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Entities\Equipo;
use App\Interfaces\RepositoryInterface;
use PDO;

class EquipoRepository implements RepositoryInterface
{
    private PDO $db;
    public function __construct(){
        $this->db = Database::getConnection();
    }

    // CREATE EQUIPO
    public function create(object $entity): bool {
        if(!$entity instanceof Equipo){
            throw new \InvalidArgumentException('Equipo Expected');
        }

        $stmt = $this->db->prepare("CALL sp_create_equipo(
            :nombre,
            :hackathon
        )");

        $ok = $stmt->execute([
            ':nombre' => $entity->getNombre(),
            ':hackathon' => $entity->getHackathon()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND BY ID
    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("CALL sp_equipo_find_id(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    // UPDATE EQUIPO
    public function update(object $entity): bool {
        if(!$entity instanceof Equipo){
            throw new \InvalidArgumentException('Equipo Expected');
        }

        $stmt = $this->db->prepare("CALL sp_update_equipo(
            :id,
            :nombre,
            :hackathon
        )");

        $ok = $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':hackathon' => $entity->getHackathon()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // DELETE EQUIPO
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("CALL sp_delete_equipo(:id);");
        $ok = $stmt->execute([':id' => $id]);
        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND ALL
    public function findAll(): array {
        $stmt = $this->db->query("CALL sp_equipo_list();");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $out = [];
        foreach($rows as $row){
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    private function hydrate(array $row): Equipo {
        return new Equipo(
            (int)$row['id'],
            $row['nombre'],
            $row['hackathon']
        );
    }
}