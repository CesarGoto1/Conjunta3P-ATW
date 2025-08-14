<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\MentorTecnicoRepository;
use App\Entities\MentorTecnico;

class MentorTecnicoController
{
    private MentorTecnicoRepository $mentorTecnicoRepository;

    public function __construct(){
        $this->mentorTecnicoRepository = new MentorTecnicoRepository();
    }

    public function mentorTecnicoToArray(MentorTecnico $mentor): array{
        return [
            'id'                    => $mentor->getId(),
            'nombre'                => $mentor->getNombre(),
            'email'                 => $mentor->getEmail(),
            'especialidad'          => $mentor->getEspecialidad(),
            'experiencia'           => $mentor->getExperiencia(),
            'disponibilidad_horaria'=> $mentor->getDisponibilidadHoraria()
        ];
    }

    public function handle(): void {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'GET'){
            if(isset($_GET['id'])){
                $mentor = $this->mentorTecnicoRepository->findById((int)$_GET['id']);
                echo json_encode($mentor ? $this->mentorTecnicoToArray($mentor) : null);
            }else{
                $list = array_map([$this, 'mentorTecnicoToArray'], $this->mentorTecnicoRepository->findAll());
                echo json_encode($list);
            }
            return;
        }
        $payload = json_decode(file_get_contents('php://input'), true);

        if($method === 'POST'){
            try {
                $mentor = new MentorTecnico(
                    null,
                    $payload['nombre'],
                    $payload['email'],
                    $payload['especialidad'],
                    (int)$payload['experiencia'],
                    (int)$payload['disponibilidad_horaria']
                );
                echo json_encode(['success' => $this->mentorTecnicoRepository->create($mentor)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'PUT'){
            $id = (int)($payload['id'] ?? 0);
            $existing = $this->mentorTecnicoRepository->findById($id);
            if(!$existing){
                http_response_code(404);
                echo json_encode(['error' => 'MentorTecnico not found']);
                return;
            }
            try {
                if(isset($payload['nombre'])) $existing->setNombre($payload['nombre']);
                if(isset($payload['email'])) $existing->setEmail($payload['email']);
                if(isset($payload['especialidad'])) $existing->setEspecialidad($payload['especialidad']);
                if(isset($payload['experiencia'])) $existing->setExperiencia((int)$payload['experiencia']);
                if(isset($payload['disponibilidad_horaria'])) $existing->setDisponibilidadHoraria((int)$payload['disponibilidad_horaria']);

                echo json_encode(['success' => $this->mentorTecnicoRepository->update($existing)]);
            } catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if($method === 'DELETE'){
            echo json_encode(['success' => $this->mentorTecnicoRepository->delete((int)($payload['id'] ?? 0))]);
            return;
        }
    }
}