<?php
declare(strict_types=1);
namespace App\Entities;
use App\Entities\Participante;

class Estudiante extends Participante
{
    private string $grado;
    private string $institucion;
    private int $tiempo_disponible_semanal;

    public function __construct(
        ?int $id,
        string $nombre,
        string $email,
        string $grado,
        string $institucion,
        int $tiempo_disponible_semanal
    ) {
        parent::__construct($id, $nombre, $email);
        $this->grado = $grado;
        $this->institucion = $institucion;
        $this->tiempo_disponible_semanal = $tiempo_disponible_semanal;
    }

    public function getGrado(): string { return $this->grado; }
    public function getInstitucion(): string { return $this->institucion; }
    public function getTiempoDisponibleSemanal(): int { return $this->tiempo_disponible_semanal; }

    public function setGrado(string $grado): void { $this->grado = $grado; }
    public function setInstitucion(string $institucion): void { $this->institucion = $institucion; }
    public function setTiempoDisponibleSemanal(int $tiempo): void { $this->tiempo_disponible_semanal = $tiempo; }
}