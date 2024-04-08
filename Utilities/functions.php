<?php

function writeToLog($message)
{
    $file = $_SERVER['DOCUMENT_ROOT'] . "/../log.txt"; // Path to your log.txt file
    $current = file_get_contents($file);

    // Check if message is an array
    if (is_array($message)) {
        // Convert array to string
        $message = print_r($message, true);
    }

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
