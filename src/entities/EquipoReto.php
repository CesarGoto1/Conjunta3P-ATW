<?php
declare(strict_types=1);
namespace App\Entities;

class EquipoReto
{
    private int $equipo_id;
    private int $reto_id;

    public function __construct(
        int $equipo_id,
        int $reto_id
    ) {
        $this->equipo_id = $equipo_id;
        $this->reto_id = $reto_id;
    }

    public function getEquipoId(): int { return $this->equipo_id; }
    public function getRetoId(): int { return $this->reto_id; }

    public function setEquipoId(int $equipo_id): void { $this->equipo_id = $equipo_id; }
    public function setRetoId(int $reto_id): void { $this->reto_id = $reto_id; }
}