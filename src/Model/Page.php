<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\PageRepository;
use LazyProperty\LazyPropertiesTrait;

final class Page
{
    use LazyPropertiesTrait;

    private ?string $content;

    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $file,
        public string $contentType,
        public array $metadata,
        public ?array $seo,
        public array $data,
    )
    {
        $this->initLazyProperties(['content']);
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        if (!isset($this->content)) {
            $content = '';
            if ($this->contentType === PageRepository::CONTENT_TYPE_MARKDOWN) {
                $content = file_get_contents(PROJECT_ROOT . '/config/data/markdown/' . $this->file . '.md');
            }
            $this->content = $content;
        }
        return $this->content;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
