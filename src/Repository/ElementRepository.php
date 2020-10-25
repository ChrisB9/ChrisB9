<?php

declare(strict_types=1);

namespace App\Repository;

abstract class ElementRepository
{
    protected const STORAGE_PATH = PROJECT_ROOT . '/config/data/';

    /** @var array<mixed> */
    protected array $elements = [];
    protected string $storageFile;

    public function loadFromStorage(): array
    {
        if (!isset($this->storageFile)) {
            throw new \Exception('The repository must define the storage file');
        }
        $storageFile = $this->storageFile;
        if (!str_ends_with($storageFile, '.php')) {
            $storageFile .= '.php';
        }
        $storageFile = self::STORAGE_PATH . $storageFile;
        if (!file_exists($storageFile)) {
            return [];
        }
        $this->elements = require $storageFile;
        // todo: currently every request resolved
        foreach ($this->elements as &$element) {
            foreach ($element as $key => $value) {
                if (is_callable($value)) {
                    $element[$key] = $value();
                }
            }
        }
        return $this->elements;
    }
}
