<?php
declare(strict_types=1);
namespace App\Entities;

abstract class Participante
{
    protected ?int $id;
    protected string $nombre;
    protected string $email;

    public function __construct(?int $id, string $nombre, string $email)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
    }

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEmail(): string { return $this->email; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setEmail(string $email): void { $this->email = $email; }
}