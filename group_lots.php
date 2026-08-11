<?php
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Models/Comanda.php';

$db = App\Core\Database::getInstance()->getConnection();
$operations = $db->query("SELECT id FROM operacoes")->fetchAll(PDO::FETCH_COLUMN);

foreach ($operations as $op_id) {
    echo "Processing operation: $op_id\n";
    $lotes = $db->prepare("SELECT id, assembler_id, comanda_inicio as number FROM comandas_lotes WHERE operacao_id = ? ORDER BY comanda_inicio");
    $lotes->execute([$op_id]);
    $commands = $lotes->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert to the format syncAll expects for generating lotes
    $validCommands = [];
    foreach ($commands as $c) {
        $validCommands[] = ['number' => (int)$c['number'], 'assemblerId' => $c['assembler_id']];
    }
    
    // Group them
    usort($validCommands, function($a, $b) { return $a['number'] <=> $b['number']; });
    $lotes_grouped = [];
    $currentLote = null;
    
    foreach ($validCommands as $c) {
        if ($currentLote === null) {
            $currentLote = ['assemblerId' => $c['assemblerId'], 'inicio' => $c['number'], 'fim' => $c['number']];
        } else {
            if ($currentLote['assemblerId'] === $c['assemblerId'] && $c['number'] === $currentLote['fim'] + 1) {
                $currentLote['fim'] = $c['number'];
            } else if ($currentLote['assemblerId'] === $c['assemblerId'] && $c['number'] === $currentLote['fim']) {
                // Same number, ignore
            } else {
                $lotes_grouped[] = $currentLote;
                $currentLote = ['assemblerId' => $c['assemblerId'], 'inicio' => $c['number'], 'fim' => $c['number']];
            }
        }
    }
    if ($currentLote !== null) {
        $lotes_grouped[] = $currentLote;
    }
    
    if (!empty($lotes_grouped)) {
        $db->prepare("DELETE FROM comandas_lotes WHERE operacao_id = ?")->execute([$op_id]);
        $stmtLote = $db->prepare("INSERT INTO comandas_lotes (operacao_id, assembler_id, comanda_inicio, comanda_fim) VALUES (?, ?, ?, ?)");
        foreach ($lotes_grouped as $l) {
            $stmtLote->execute([$op_id, $l['assemblerId'], $l['inicio'], $l['fim']]);
        }
    }
}
echo "Done grouping lots.\n";
