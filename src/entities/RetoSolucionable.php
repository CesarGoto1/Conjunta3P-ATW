<?php
declare(strict_types=1);
namespace App\Entities;

abstract class RetoSolucionable
{
    protected ?int $id;
    protected ?string $descripcion;

    public function __construct(?int $id = null, ?string $descripcion = null)
    {
        $this->id = $id;
        $this->descripcion = $descripcion;
    }

    public function getId(): ?int{return $this->id;}

    public function getDescripcion(): ?string{return $this->descripcion;}

    public function setId(int $id): void{$this->id = $id;}

    public function setDescripcion(string $descripcion): void    {$this->descripcion = $descripcion;}


}