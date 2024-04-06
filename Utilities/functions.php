<?php

function writeToLog($message)
{
    $file = __DIR__ . "/../../../../log.txt"; // Path to your log.txt file
    $current = file_get_contents($file);
    $current .= date('Y-m-d H:i:s') . ": " . $message . "\n";
    file_put_contents($file, $current);
}

function dd(mixed $value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value);
}
