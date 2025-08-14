<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\EquipoRetoRepository;
use App\Entities\EquipoReto;

class EquipoRetoController
{
    private EquipoRetoRepository $repository;

    public function __construct(){
        $this->repository = new EquipoRetoRepository();
    }

    public function equipoRetoToArray(EquipoReto $er): array {
        return [
            'equipo_id' => $er->getEquipoId(),
            'reto_id'   => $er->getRetoId()
        ];
    }

    public function handle(): void {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['equipo_id']) && isset($_GET['reto_id'])) {
                $er = $this->repository->findByEquipoReto((int)$_GET['equipo_id'], (int)$_GET['reto_id']);
                echo json_encode($er ? $this->equipoRetoToArray($er) : null);
            } else {
                $list = array_map([$this, 'equipoRetoToArray'], $this->repository->findAll());
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        if ($method === 'POST') {
            try {
                $er = new EquipoReto(
                    (int)$payload['equipo_id'],
                    (int)$payload['reto_id']
                );
                echo json_encode(['success' => $this->repository->create($er)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            if (!isset($payload['old_equipo_id'], $payload['old_reto_id'], $payload['equipo_id'], $payload['reto_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'old_equipo_id, old_reto_id, equipo_id y reto_id son requeridos']);
                return;
            }
            $er = new EquipoReto(
                (int)$payload['equipo_id'],
                (int)$payload['reto_id']
            );
            $er->old_equipo_id = (int)$payload['old_equipo_id'];
            $er->old_reto_id = (int)$payload['old_reto_id'];
            echo json_encode(['success' => $this->repository->update($er)]);
            return;
        }

        if ($method === 'DELETE') {
            if (!isset($payload['equipo_id'], $payload['reto_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'equipo_id y reto_id son requeridos']);
                return;
            }
            echo json_encode([
                'success' => $this->repository->deleteByEquipoReto(
                    (int)$payload['equipo_id'],
                    (int)$payload['reto_id']
                )
            ]);
            return;
        }
    }
}