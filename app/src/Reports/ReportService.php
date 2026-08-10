<?php
namespace App\Reports;

final class ReportService
{
    public function stats(array $commands): array
    {
        $equivalent = 0; $errors = 0; $dispatch = 0;
        foreach ($commands as $command) {
            $equivalent += (int) $command['pizzas'] + (int) $command['volcano_qty'] + ((int) $command['esfiha_qty'] / 25) + (int) $command['sweet_qty'];
            $errors += $command['error_type'] ? 1 : 0;
            $dispatch += $command['status'] === 'despacho' ? 1 : 0;
        }
        return ['commands' => count($commands), 'equivalent' => $equivalent, 'errors' => $errors, 'dispatch' => $dispatch];
    }
}
