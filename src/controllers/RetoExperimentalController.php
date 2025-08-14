<?php
declare(strict_types=1);
namespace App\controllers;

use App\Repositories\RetoExperimentalRepository;
use App\Entities\RetoExperimental;

class RetoExperimentalController
{
    private RetoExperimentalRepository $repository;

    public function __construct()
    {
        $this->repository = new RetoExperimentalRepository();
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $retoExperimental = $this->repository->findById((int)$_GET['id']);
                echo json_encode($retoExperimental ? $this->retoToArray($retoExperimental) : null);
            } else {
                $list = array_map([$this, 'retoToArray'], $this->repository->findAll());
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if ($method === 'POST') {
            $retoExperimental = new RetoExperimental(
                null,
                $payload['descripcion'] ?? null,
                $payload['enfoque_pedagogico'] ?? null
            );
            echo json_encode(['success' => $this->repository->create($retoExperimental)]);
            return;
        }
        if ($method === 'PUT') {
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID is required for update']);
                return;
            }
            $retoExperimental = new RetoExperimental(
                (int)$_GET['id'],
                $payload['descripcion'] ?? null,
                $payload['enfoque_pedagogico'] ?? null
            );
            echo json_encode(['success' => $this->repository->update($retoExperimental)]);
            return;
        }
        if ($method === 'DELETE') {
            echo json_encode(['success' => $this->repository->delete((int)$_GET['id'])]);
            return;
        }
    }

    public function retoToArray(RetoExperimental $reto): array
    {
        return [
            'id' => $reto->getId(),
            'descripcion' => $reto->getDescripcion(),
            'enfoque_pedagogico' => $reto->getEnfoquePedagogico()
        ];
    }
}