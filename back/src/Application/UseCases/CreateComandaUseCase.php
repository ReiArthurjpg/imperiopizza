<?php

namespace App\Back\Application\UseCases;

use App\Back\Domain\Repositories\ComandaRepositoryInterface;

class CreateComandaUseCase
{
    private $comandaRepository;

    public function __construct(ComandaRepositoryInterface $comandaRepository)
    {
        $this->comandaRepository = $comandaRepository;
    }

    public function execute(array $data)
    {
        // Regras de negócio podem ser validadas aqui
        if (!isset($data['id'])) {
            $data['id'] = uniqid();
        }
        
        $this->comandaRepository->create($data);
        
        return $data;
    }
}
