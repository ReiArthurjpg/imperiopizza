<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) require __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
});
require __DIR__ . '/../src/Shared/helpers.php';

use App\Commands\CommandRepository;
use App\Operations\OperationRepository;
use App\Reports\ReportService;
use App\Team\PeopleRepository;

$operations = new OperationRepository();
$peopleRepo = new PeopleRepository();
$commandsRepo = new CommandRepository();
$operation = $operations->today();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'person') $peopleRepo->create(trim($_POST['name']), $_POST['role']);
    if ($action === 'team') $peopleRepo->syncTeam((int) $operation['id'], $_POST['people'] ?? []);
    if ($action === 'start') $operations->start((int) $operation['id']);
    if ($action === 'close_kitchen') $operations->closeKitchen((int) $operation['id']);
    if ($action === 'finish') $operations->finish((int) $operation['id']);
    if ($action === 'command') $commandsRepo->create([
        'operation_id' => $operation['id'], 'command_number' => (int) $_POST['command_number'], 'assembler_id' => (int) $_POST['assembler_id'],
        'pizzas' => (int) $_POST['pizzas'], 'volcano_qty' => (int) ($_POST['volcano_qty'] ?? 0), 'esfiha_qty' => (int) ($_POST['esfiha_qty'] ?? 0),
        'sweet_qty' => (int) ($_POST['sweet_qty'] ?? 0), 'note' => trim($_POST['note'] ?? ''), 'status' => $_POST['initial_oven'] ?? 'cozinha',
    ]);
    if ($action === 'move') $commandsRepo->move((int) $_POST['id'], $_POST['status']);
    header('Location: /?date=' . urlencode($operation['operation_date'])); exit;
}

$operation = $operations->today();
$people = $peopleRepo->all();
$team = $peopleRepo->team((int) $operation['id']);
$commands = $commandsRepo->byOperation((int) $operation['id']);
$stats = (new ReportService())->stats($commands);
include __DIR__ . '/../views/layout.php';
