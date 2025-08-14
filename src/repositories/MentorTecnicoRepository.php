<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Config\Database;
use App\Entities\MentorTecnico;
use App\Interfaces\RepositoryInterface;
use PDO;

class MentorTecnicoRepository implements RepositoryInterface
{
    private PDO $db;
    public function __construct(){
        $this->db = Database::getConnection();
    }

    // CREATE MENTOR TECNICO
    public function create(object $entity): bool {
        if(!$entity instanceof MentorTecnico){
            throw new \InvalidArgumentException('MentorTecnico Expected');
        }

        $stmt = $this->db->prepare("CALL sp_create_mentor_tecnico(
            :nombre,
            :email,
            :especialidad,
            :experiencia,
            :disponibilidad_horaria
        )");

        $ok = $stmt->execute([
            ':nombre' => $entity->getNombre(),
            ':email' => $entity->getEmail(),
            ':especialidad' => $entity->getEspecialidad(),
            ':experiencia' => $entity->getExperiencia(),
            ':disponibilidad_horaria' => $entity->getDisponibilidadHoraria()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND BY ID
    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("CALL sp_mentor_tecnico_find_id(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    // UPDATE MENTOR TECNICO
    public function update(object $entity): bool {
        if(!$entity instanceof MentorTecnico){
            throw new \InvalidArgumentException('MentorTecnico Expected');
        }

        $stmt = $this->db->prepare("CALL sp_update_mentor_tecnico(
            :id,
            :nombre,
            :email,
            :especialidad,
            :experiencia,
            :disponibilidad_horaria
        )");

        $ok = $stmt->execute([
            ':id' => $entity->getId(),
            ':nombre' => $entity->getNombre(),
            ':email' => $entity->getEmail(),
            ':especialidad' => $entity->getEspecialidad(),
            ':experiencia' => $entity->getExperiencia(),
            ':disponibilidad_horaria' => $entity->getDisponibilidadHoraria()
        ]);

        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // DELETE MENTOR TECNICO
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("CALL sp_delete_mentor_tecnico(:id);");
        $ok = $stmt->execute([':id' => $id]);
        if($ok){
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    // FIND ALL
    public function findAll(): array {
        $stmt = $this->db->query("CALL sp_mentor_tecnico_list();");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $out = [];
        foreach($rows as $row){
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    private function hydrate(array $row): MentorTecnico {
        return new MentorTecnico(
            (int)$row['id'],
            $row['nombre'],
            $row['email'],
            $row['especialidad'],
            (int)$row['experiencia'],
            (int)$row['disponibilidad_horaria']
        );
    }
}