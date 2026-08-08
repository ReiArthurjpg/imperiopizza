<?php
function e(string|int|float|null $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function money(float $value): string { return number_format($value, 2, ',', '.'); }
