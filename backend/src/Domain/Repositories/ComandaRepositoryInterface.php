<?php

namespace App\Back\Domain\Repositories;

interface ComandaRepositoryInterface
{
    public function getAllByOperacao($operacao_id);
    public function syncAll($operacaoId, $commands);
    public function create(array $data);
    public function updateStatus($id, $status);
    public function assignAssembler($operacao_id, $number, $assembler_id);
}
