<?php

declare(strict_types=1);

namespace App\Repository;

use App\Markdown\Renderer;
use App\Model\Page;

final class PageRepository extends ElementRepository
{
    public const CONTENT_TYPE_MARKDOWN = 'markdown';
    public const CONTENT_TYPE_TWIG = 'twig';

    protected string $storageFile = 'pages';

    public function __construct(private Renderer $renderer)
    {
    }

    public function getPage(string $route, string $locale = 'en'): ?Page
    {
        if (empty($this->elements)) {
            $this->loadFromStorage();
        }
        $page = $this->elements[$route] ?? null;
        if ($page) {
            $page = new Page(
                id: $page['id'] ?? throw new \Exception('A page has to have an id'),
                title: $page['title'] ?? '',
                slug: $route,
                file: $page['file'],
                contentType: $page['contentType'] ?? self::CONTENT_TYPE_MARKDOWN,
                metadata: $page['metadata'] ?? [],
                seo: $page['seo'] ?? [],
                data: $page,
                language: $locale,
            );
            $page->getContent();
            Renderer::setContext($page);
            $content = match($page->contentType) {
                self::CONTENT_TYPE_MARKDOWN => $this->renderer->parseMarkdown(),
                self::CONTENT_TYPE_TWIG => $page->file,
            };
            $page->setContent($content);
            return $page;
        }
        return null;
    }
}
