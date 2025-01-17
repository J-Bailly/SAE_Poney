<?php

class Autoloader {
    public static function register(): void {
        spl_autoload_register([self::class, 'autoload']);
    }

    private static function autoload(string $class): void {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        $fullPath = __DIR__ . '/../' . $path; // Ajuster selon votre structure
        if (file_exists($fullPath)) {
            require $fullPath;
        }
    }
}

Autoloader::register();
