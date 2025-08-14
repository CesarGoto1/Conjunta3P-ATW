<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\RetoRealRepository;
use App\Entities\RetoReal;

class RetoRealController
{
    private RetoRealRepository $repository;

    public function __construct()
    {
        $this->repository = new RetoRealRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $retoReal = $this->repository->findById((int)$_GET['id']);
                echo json_encode($retoReal ? $this->retoToArray($retoReal) : null);
            } else {
                $list = array_map([$this, 'retoToArray'], $this->repository->findAll());
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if ($method === 'POST') {
            $retoReal = new RetoReal(
                null,
                $payload['descripcion'] ?? null,
                $payload['entidad_colaboradora'] ?? null
            );
            echo json_encode(['success' => $this->repository->create($retoReal)]);
            return;
        }

        if ($method === 'PUT') {
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID is required for update']);
                return;
            }
            $retoReal = new RetoReal(
                (int)$_GET['id'],
                $payload['descripcion'] ?? null,
                $payload['entidad_colaboradora'] ?? null
            );
            echo json_encode(['success' => $this->repository->update($retoReal)]);
            return;
        }

        if ($method === 'DELETE') {
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID is required for delete']);
                return;
            }
            echo json_encode(['success' => $this->repository->delete((int)$_GET['id'])]);
            return;
        }
    }

    private function retoToArray(RetoReal $reto): array
    {
        return [
            'id' => $reto->getId(),
            'descripcion' => $reto->getDescripcion(),
            'entidad_colaboradora' => $reto->getEntidadColaboradora()
        ];
    }
}
