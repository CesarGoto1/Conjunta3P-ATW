<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\EquipoRetoRepository;
use App\Repositories\EquipoRepository;
use App\Repositories\RetoRealRepository;
use App\Repositories\RetoExperimentalRepository;
use App\Entities\EquipoReto;

class EquipoRetoController
{
    private EquipoRetoRepository $repository;
    private EquipoRepository $equipoRepository;
    private RetoRealRepository $retoRealRepository;
    private RetoExperimentalRepository $retoExperimentalRepository;

    public function __construct(){
        $this->repository = new EquipoRetoRepository();
        $this->equipoRepository = new EquipoRepository();
        $this->retoRealRepository = new RetoRealRepository();
        $this->retoExperimentalRepository = new RetoExperimentalRepository();
    }

    private function validarEquipoYReto(int $equipo_id, int $reto_id): ?string {
        if (!$this->equipoRepository->findById($equipo_id)) {
            return "El equipo con ID $equipo_id no existe.";
        }
        // Buscar en ambas tablas de retos
        $reto = $this->retoRealRepository->findById($reto_id) ?? $this->retoExperimentalRepository->findById($reto_id);
        if (!$reto) {
            return "El reto con ID $reto_id no existe.";
        }
        return null;
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
                $equipo_id = (int)$payload['equipo_id'];
                $reto_id = (int)$payload['reto_id'];
                $error = $this->validarEquipoYReto($equipo_id, $reto_id);
                if ($error) {
                    http_response_code(400);
                    echo json_encode(['error' => $error]);
                    return;
                }
                $er = new EquipoReto($equipo_id, $reto_id);
                echo json_encode(['success' => $this->repository->create($er)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            if (
                !isset($payload['old_equipo_id'], $payload['old_reto_id'], $payload['equipo_id'], $payload['reto_id'])
            ) {
                http_response_code(400);
                echo json_encode(['error' => 'old_equipo_id, old_reto_id, equipo_id y reto_id son requeridos']);
                return;
            }

            $old_equipo_id = (int)$payload['old_equipo_id'];
            $old_reto_id = (int)$payload['old_reto_id'];
            $equipo_id = (int)$payload['equipo_id'];
            $reto_id = (int)$payload['reto_id'];

            $errorOld = $this->validarEquipoYReto($old_equipo_id, $old_reto_id);
            $errorNew = $this->validarEquipoYReto($equipo_id, $reto_id);

            if ($errorOld && $errorNew) {
                http_response_code(400);
                echo json_encode(['error' => 'Ningún par de IDs es válido: ' . $errorOld . ' | ' . $errorNew]);
                return;
            }
            if ($errorOld) {
                http_response_code(400);
                echo json_encode(['error' => 'IDs originales inválidos: ' . $errorOld]);
                return;
            }
            if ($errorNew) {
                http_response_code(400);
                echo json_encode(['error' => 'IDs nuevos inválidos: ' . $errorNew]);
                return;
            }

            $er = new EquipoReto($equipo_id, $reto_id);
            $er->old_equipo_id = $old_equipo_id;
            $er->old_reto_id = $old_reto_id;
            echo json_encode(['success' => $this->repository->update($er)]);
            return;
        }

        if ($method === 'DELETE') {
            if (!isset($payload['equipo_id'], $payload['reto_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'equipo_id y reto_id son requeridos']);
                return;
            }
            $equipo_id = (int)$payload['equipo_id'];
            $reto_id = (int)$payload['reto_id'];
            $error = $this->validarEquipoYReto($equipo_id, $reto_id);
            if ($error) {
                http_response_code(400);
                echo json_encode(['error' => $error]);
                return;
            }
            echo json_encode([
                'success' => $this->repository->deleteByEquipoReto($equipo_id, $reto_id)
            ]);
            return;
        }
    }
}