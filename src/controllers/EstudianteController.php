<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\EstudianteRepository;
use App\Entities\Estudiante;

class EstudianteController
{
    private EstudianteRepository $estudianteRepository;

    public function __construct(){
        $this->estudianteRepository = new EstudianteRepository();
    }

    public function estudianteToArray(Estudiante $estudiante): array{
        return [
            'id'                        => $estudiante->getId(),
            'nombre'                    => $estudiante->getNombre(),
            'email'                     => $estudiante->getEmail(),
            'grado'                     => $estudiante->getGrado(),
            'institucion'               => $estudiante->getInstitucion(),
            'tiempo_disponible_semanal' => $estudiante->getTiempoDisponibleSemanal()
        ];
    }

    public function handle(): void {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'GET'){
            if(isset($_GET['id'])){
                $estudiante = $this->estudianteRepository->findById((int)$_GET['id']);
                echo json_encode($estudiante ? $this->estudianteToArray($estudiante) : null);
            }else{
                $list = array_map([$this, 'estudianteToArray'], $this->estudianteRepository->findAll());
                echo json_encode($list);
            }
            return;
        }
        $payload = json_decode(file_get_contents('php://input'), true);

        if($method === 'POST'){
            try {
                $estudiante = new Estudiante(
                    null,
                    $payload['nombre'],
                    '', // tipo_participante no se usa
                    $payload['email'],
                    $payload['grado'],
                    $payload['institucion'],
                    (int)$payload['tiempo_disponible_semanal']
                );
                echo json_encode(['success' => $this->estudianteRepository->create($estudiante)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'PUT'){
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->estudianteRepository->findById($id);
            if(!$existing){
                http_response_code(404);
                echo json_encode(['error' => 'Estudiante not found']);
                return;
            }
            try {
                if(isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
                if(isset($payload['email'])) $existing->setEmail($payload['email']);
                if(isset($payload['grado'])) $existing->setGrado($payload['grado']);
                if(isset($payload['institucion'])) $existing->setInstitucion($payload['institucion']);
                if(isset($payload['tiempo_disponible_semanal'])) $existing->setTiempoDisponibleSemanal((int)$payload['tiempo_disponible_semanal']);

                echo json_encode(['success' => $this->estudianteRepository->update($existing)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'DELETE'){
            echo json_encode(['success' => $this->estudianteRepository->delete((int)($payload['id'] ?? 0))]);
            return;
        }
    }
}