<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Entities\Estudiante;
use App\Interfaces\RepositoryInterface;
use PDO;

class EstudianteRepository implements RepositoryInterface
{
    private PDO $db;
    public function __construct(){
        $this->db = Database::getConnection();
    }

    // CREATE ESTUDIANTE
    public function create(object $entity): bool {
        if(!$entity instanceof Estudiante){
            throw new \InvalidArgumentException('Estudiante Expected');
        }

        $stmt = $this->db->prepare("CALL sp_create_estudiante(
            :nombre,
            :email,
            :grado,
            :institucion,
            :tiempo_disponible_semanal,
            :equipo_id
        )");

        $ok = $stmt->execute([
            ':nombre' => $entity->getNombre(),
            ':email' => $entity->getEmail(),
            ':grado' => $entity->getGrado(),
            ':institucion' => $entity->getInstitucion(),
            ':tiempo_disponible_semanal' => $entity->getTiempoDisponibleSemanal(),
            ':equipo_id' => $entity->getEquipoId()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND BY ID
    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("CALL sp_estudiante_find_id(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    // UPDATE ESTUDIANTE
    public function update(object $entity): bool {
        if(!$entity instanceof Estudiante){
            throw new \InvalidArgumentException('Estudiante Expected');
        }

        $stmt = $this->db->prepare("CALL sp_update_estudiante(
            :id,
            :nombre,
            :email,
            :grado,
            :institucion,
            :tiempo_disponible_semanal,
            :equipo_id
        )");

        $ok = $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':email' => $entity->getEmail(),
            ':grado' => $entity->getGrado(),
            ':institucion' => $entity->getInstitucion(),
            ':tiempo_disponible_semanal' => $entity->getTiempoDisponibleSemanal(),
            ':equipo_id' => $entity->getEquipoId()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // DELETE ESTUDIANTE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("CALL sp_delete_estudiante(:id);");
        $ok = $stmt->execute([':id' => $id]);
        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND ALL
    public function findAll(): array {
        $stmt = $this->db->query("CALL sp_estudiante_list();");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $out = [];
        foreach($rows as $row){
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    private function hydrate(array $row): Estudiante {
        return new Estudiante(
            (int)$row['id'],
            $row['nombre'],
            $row['email'],
            $row['grado'],
            $row['institucion'],
            (int)$row['tiempo_disponible_semanal'],
            isset($row['equipo_id']) ? (int)$row['equipo_id'] : null
        );
    }
}