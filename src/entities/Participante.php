<?php
declare(strict_types=1);
namespace App\Entities;

abstract class Participante
{
    protected ?int $id;
    protected string $nombre;
    protected string $email;
    protected ?int $equipo_id;

    public function __construct(?int $id, string $nombre, string $email, ?int $equipo_id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->equipo_id = $equipo_id;
    }

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEmail(): string { return $this->email; }
    public function getEquipoId(): ?int { return $this->equipo_id; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setEquipoId(?int $equipo_id): void { $this->equipo_id = $equipo_id; }
}