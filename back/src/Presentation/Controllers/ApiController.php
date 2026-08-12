<?php

namespace App\Back\Presentation\Controllers;

class ApiController
{
    private function jsonResponse($data, $status = 200)
    {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    private function getJsonInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getInitData()
    {
        // TODO: Call GetInitDataUseCase
        $this->jsonResponse(['people' => [], 'operations' => []]);
    }

    public function syncState()
    {
        // TODO: Call SyncStateUseCase
        $this->jsonResponse(['success' => true]);
    }

    public function createComanda()
    {
        // TODO: Call CreateComandaUseCase
        $data = $this->getJsonInput();
        $this->jsonResponse(['success' => true, 'comanda' => $data]);
    }

    public function updateComandaStatus()
    {
        // TODO: Call UpdateComandaStatusUseCase
        $this->jsonResponse(['success' => true]);
    }

    public function syncEquipe()
    {
        // TODO: Call SyncEquipeUseCase
        $this->jsonResponse(['success' => true]);
    }
}
