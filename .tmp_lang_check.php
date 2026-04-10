<?php
$ukDir = __DIR__ . '/resources/lang/uk';
$enDir = __DIR__ . '/resources/lang/en';
$ukFiles = array_map(fn($f) => basename($f, '.php'), glob($ukDir . '/*.php'));
$enFiles = array_map(fn($f) => basename($f, '.php'), glob($enDir . '/*.php'));
sort($ukFiles);
sort($enFiles);
$missingInEn = array_values(array_diff($ukFiles, $enFiles));
$missingInUk = array_values(array_diff($enFiles, $ukFiles));

echo 'missing_files_in_en=' . count($missingInEn) . PHP_EOL;
echo 'missing_files_in_uk=' . count($missingInUk) . PHP_EOL;

$flatten = function(array $items, string $prefix = '') use (&$flatten): array {
    $out = [];
    foreach ($items as $k => $v) {
        $key = $prefix === '' ? (string) $k : $prefix . '.' . $k;
        if (is_array($v)) {
            $out = array_merge($out, $flatten($v, $key));
        } else {
            $out[] = $key;
        }
    }
    return $out;
};

foreach (array_intersect($ukFiles, $enFiles) as $name) {
    $uk = include $ukDir . '/' . $name . '.php';
    $en = include $enDir . '/' . $name . '.php';
    if (!is_array($uk) || !is_array($en)) {
        continue;
    }
    $ukKeys = $flatten($uk);
    $enKeys = $flatten($en);
    $a = array_values(array_diff($ukKeys, $enKeys));
    $b = array_values(array_diff($enKeys, $ukKeys));
    if ($a || $b) {
        echo $name . ': missing_in_en=' . count($a) . ', missing_in_uk=' . count($b) . PHP_EOL;
    }
}
