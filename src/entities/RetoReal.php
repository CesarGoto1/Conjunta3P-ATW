<?php
declare(strict_types=1);
namespace App\Entities;

class RetoReal extends RetoSolucionable
{
    private string $entidad_colaboradora;

    public function __construct(?int $id = null, ?string $descripcion = null, ?string $entidad_colaboradora = null)
    {
        parent::__construct($id, $descripcion);
        $this->entidad_colaboradora = $entidad_colaboradora;
    }

    public function getEntidadColaboradora(): ?string{return $this->entidad_colaboradora;}

    public function setEntidadColaboradora(string $entidad_colaboradora): void{$this->entidad_colaboradora = $entidad_colaboradora;}

}