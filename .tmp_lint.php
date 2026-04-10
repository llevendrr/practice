<?php
$lines = explode("\n", trim((string) shell_exec('git status --short')));
$files = [];
foreach ($lines as $line) {
    if ($line === '') continue;
    $path = trim(substr($line, 3));
    if (str_ends_with($path, '.php')) {
        $files[] = $path;
    }
}
$php = 'g:\\OSPanel\\modules\\PHP-8.4\\PHP\\php.exe';
foreach ($files as $file) {
    $cmd = '"' . $php . '" -l "' . $file . '"';
    echo shell_exec($cmd);
}
