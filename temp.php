<?php
$d = json_decode(file_get_contents('storage/state.json'), true);
if (isset($d['operations'])) {
    foreach ($d['operations'] as $op) {
        if (isset($op['mass'])) {
            echo "Op ID: " . $op['id'] . "\n";
            print_r($op['mass']);
        }
    }
}
