<?php

declare(strict_types=1);

namespace App\Markdown;

use App\Model\Page;
use Symfony\Component\Asset\Packages;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

final class Renderer
{
    private static Page $context;
    private Environment $twig;
    private CustomParsedownExtension $parsedown;
    private CacheInterface $cache;

    public function __construct(Environment $twig, CustomParsedownExtension $parsedown, CacheInterface $cache, Packages $packages)
    {
        $parsedown->setSafeMode(false);
        $parsedown->setBreaksEnabled(true);
        $parsedown->twig = $twig;
        $parsedown->packages = $packages;
        $parsedown->chartJsDir = PROJECT_ROOT . '/config/data/chartsJson';
        $this->parsedown = $parsedown;
        $this->twig = $twig;
        $this->cache = $cache;
    }

    public function parseMarkdown(?string $markdown = null): string
    {
        if (self::$context && isset(self::$context->id)) {
            if ($this->cache->getItem($this->getCacheKey())->isHit()) {
                $currentMd5 = md5(serialize(self::$context));
                $md5 = $this->cache->get(
                    $this->getCacheKey(md5((string)self::$context->id)),
                    fn (ItemInterface $item) => md5(serialize(self::$context))
                );
                if ($currentMd5 !== $md5) {
                    $this->cache->delete($this->getCacheKey());
                    $this->cache->delete(md5((string)self::$context->id));
                }
            }
            return $this->cache->get(
                $this->getCacheKey(),
                function (ItemInterface $item) use ($markdown) {
                    $markdown ??= self::$context->getContent();
                    $this->parsedown->context = self::$context->toArray();
                    return $this->parsedown->text($markdown);
                }
            );
        }
        $markdown ??= self::$context->getContent();
        $this->parsedown->context = self::$context->toArray();
        return $this->parsedown->text($markdown);
    }

    private function getCacheKey(null|int|string $id = null): string
    {
        return str_replace(['\\', '/', '@'], '_', (self::$context::class . (self::$context->id ?? $id)));
    }

    public static function setContext(Page $context): void
    {
        self::$context = $context;
    }
}
