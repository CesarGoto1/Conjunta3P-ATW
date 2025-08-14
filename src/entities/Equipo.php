<?php
declare(strict_types=1);
namespace App\Entities;

class Equipo
{
    private ?int $id;
    private string $nombre;
    private string $hackathon;

    public function __construct(
        ?int $id,
        string $nombre,
        string $hackathon
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->hackathon = $hackathon;
    }

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getHackathon(): string { return $this->hackathon; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setHackathon(string $hackathon): void { $this->hackathon = $hackathon; }
}