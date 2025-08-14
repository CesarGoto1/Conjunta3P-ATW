<?php
declare(strict_types=1);
namespace App\Entities;
use App\Entities\Participante;

class MentorTecnico extends Participante
{
    private string $especialidad;
    private int $experiencia;
    private int $disponibilidad_horaria;

    public function __construct(
        ?int $id,
        string $nombre,
        string $email,
        string $especialidad,
        int $experiencia,
        int $disponibilidad_horaria
    ) {
        parent::__construct($id, $nombre, $email);
        $this->especialidad = $especialidad;
        $this->experiencia = $experiencia;
        $this->disponibilidad_horaria = $disponibilidad_horaria;
    }

    public function getEspecialidad(): string { return $this->especialidad; }
    public function getExperiencia(): int { return $this->experiencia; }
    public function getDisponibilidadHoraria(): int { return $this->disponibilidad_horaria; }

    public function setEspecialidad(string $especialidad): void { $this->especialidad = $especialidad; }
    public function setExperiencia(int $experiencia): void { $this->experiencia = $experiencia; }
    public function setDisponibilidadHoraria(int $disponibilidad_horaria): void { $this->disponibilidad_horaria = $disponibilidad_horaria; }
}