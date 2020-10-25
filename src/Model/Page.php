<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\PageRepository;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

final class Page
{
    private ?string $content;

    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $file,
        #[ExpectedValues([PageRepository::CONTENT_TYPE_MARKDOWN, PageRepository::CONTENT_TYPE_TWIG])] public string $contentType,
        public array $metadata,
        public ?array $seo,
        public array $data,
        #[ExpectedValues(['de', 'en'])] public string $language,
    )
    {
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
                $file = sprintf(
                    '%s/config/data/markdown/%s/%s.md',
                    PROJECT_ROOT,
                    $this->language,
                    $this->file
                );
                if (!file_exists($file)) {
                    throw new FileNotFoundException('File not found.');
                }
                $content = file_get_contents($file);
            }
            $this->content = $content;
        }
        return $this->content;
    }

    public function getTemplateFile(): string
    {
        return match($this->contentType){
            PageRepository::CONTENT_TYPE_TWIG => $this->file,
            default => 'page.html.twig',
        };
    }

    #[Pure]
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
