<?php

declare(strict_types=1);

namespace App\Model;

final class Project
{
    public function __construct(
        public int $id,
        public bool $active,
        public bool $client,
        public array|string $icon,
        public string $url,
        public string $title,
        public string $description,
        public array $tags,
    )
    {
        # php 8.0 takes care of the rest
    }

    public static function fromArray(array $project): static
    {
        return new static(
            $project['id'],
            $project['active'],
            $project['client'] ?? false,
            $project['icon'],
            $project['url'],
            $project['title'],
            $project['description'],
            $project['tags'] ?? [],
        );
    }
}
