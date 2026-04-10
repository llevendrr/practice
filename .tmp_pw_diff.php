<?php
$uk = include 'resources/lang/uk/passwords.php';
$en = include 'resources/lang/en/passwords.php';
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
$ukKeys = $flatten($uk);
$enKeys = $flatten($en);
print_r(array_values(array_diff($ukKeys, $enKeys)));
