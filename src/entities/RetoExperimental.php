<?php
declare(strict_types=1);
namespace App\Entities;

class RetoExperimental extends RetoSolucionable
{
	private const ENFOQUES_VALIDOS = ['STEM', 'STEAM', 'ABP'];
	private string $enfoque_pedagogico;

	public function __construct(?int $reto_id = null, ?string $descripcion = null, string $enfoque_pedagogico)
	{
		parent::__construct($reto_id, $descripcion);
		$this->enfoque_pedagogico = $enfoque_pedagogico;
	}

	public function getEnfoquePedagogico(): ?string{return $this->enfoque_pedagogico;}

	public function setEnfoquePedagogico(string $enfoque_pedagogico): void{$this->enfoque_pedagogico = $enfoque_pedagogico;}
}