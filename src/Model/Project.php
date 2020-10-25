<?php

declare(strict_types=1);

namespace App\Model;

use JetBrains\PhpStorm\Pure;

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
    }

    public function getLink(): string
    {
        if ($this->isInternal()) {
            return str_replace('route:/', '', $this->url);
        }
        return $this->url;
    }

    public function isInternal(): bool
    {
        return str_starts_with(haystack: $this->url, needle: 'route:');
    }

    #[Pure]
    public static function fromArray(array $project): Project
    {
        return new Project(
            $project['id'],
            $project['active'],
            $project['client'] ?? false,
            $project['icon'],
            $project['url'],
            $project['title'] ?? $project['label'] . '.title',
            $project['description'] ?? $project['label'] . '.description',
            $project['tags'] ?? [],
        );
    }
}
