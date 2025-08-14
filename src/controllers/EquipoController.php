<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\EquipoRepository;
use App\Entities\Equipo;

class EquipoController
{
    private EquipoRepository $equipoRepository;

    public function __construct(){
        $this->equipoRepository = new EquipoRepository();
    }

    public function equipoToArray(Equipo $equipo): array{
        return [
            'id'        => $equipo->getId(),
            'nombre'    => $equipo->getNombre(),
            'hackathon' => $equipo->getHackathon()
        ];
    }

    public function handle(): void {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'GET'){
            if(isset($_GET['id'])){
                $equipo = $this->equipoRepository->findById((int)$_GET['id']);
                echo json_encode($equipo ? $this->equipoToArray($equipo) : null);
            }else{
                $list = array_map([$this, 'equipoToArray'], $this->equipoRepository->findAll());
                echo json_encode($list);
            }
            return;
        }
        $payload = json_decode(file_get_contents('php://input'), true);

        if($method === 'POST'){
            try {
                $equipo = new Equipo(
                    null,
                    $payload['nombre'],
                    $payload['hackathon']
                );
                echo json_encode(['success' => $this->equipoRepository->create($equipo)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'PUT'){
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->equipoRepository->findById($id);
            if(!$existing){
                http_response_code(404);
                echo json_encode(['error' => 'Equipo not found']);
                return;
            }
            try {
                if(isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
                if(isset($payload['hackathon'])) $existing->setHackathon($payload['hackathon']);

                echo json_encode(['success' => $this->equipoRepository->update($existing)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'DELETE'){
            echo json_encode(['success' => $this->equipoRepository->delete((int)($payload['id'] ?? 0))]);
            return;
        }
    }
}