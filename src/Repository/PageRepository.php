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

    public function getPage(string $route): ?Page
    {
        if (empty($this->elements)) {
            $this->loadFromStorage();
        }
        $page = $this->elements[$route] ?? null;
        if ($page) {
            $page = new Page(
                $page['id'] ?? throw new \Exception('A page has to have an id'),
                $page['title'] ?? '',
                $route,
                $page['file'],
                $page['contentType'] ?? self::CONTENT_TYPE_MARKDOWN,
                $page['metadata'] ?? [],
                $page,
            );
            $page->getContent();
            Renderer::setContext($page);
            if ($page->contentType === self::CONTENT_TYPE_MARKDOWN) {
                $content = $this->renderer->parseMarkdown();
            }
            if ($page->contentType === self::CONTENT_TYPE_TWIG) {
                $content = $page->file;
            }
            $page->setContent($content);
            return $page;
        }
        return null;
    }
}
