<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PageController extends AbstractController
{
    /**
     * @param Request $request
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function pageAction(Request $request, PageRepository $pageRepository): Response
    {
        $route = ltrim($request->getPathInfo(), '/');
        $page = $pageRepository->getPage($route);
        return $this->generate($page);
    }

    /**
     * @param Request $request
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function renderAction(Request $request, PageRepository $pageRepository): Response
    {
        $page = $this->getPage($request, $pageRepository);
        return $this->generate($page, $page->file);
    }

    private function getPage(Request $request, PageRepository $pageRepository)
    {
        $route = ltrim($request->getPathInfo(), '/');
        return $pageRepository->getPage($route);
    }

    private function generate(Page $page, string $file = 'page.html.twig'): Response
    {
        $host = $this->container->get('router')->getContext()->getHost();
        $image = sprintf('https://%s%s', $host, '/image/background.jpg');
        $seo = array_merge(
            [
                'robots' => 'follow, index',
                'twitter:title' => $page->title,
                'twitter:image' => $image,
            ],
            $page->seo
        );
        $seo['twitter:description'] = $page->seo['description'] ?? '';
        $metaTags = [
            'name' => $seo,
            'property' => [
                'og:title' => $page->title,
                'og:image' => $image,
                'og:description' => $page->seo['description'] ?? '',
            ],
        ];
        return $this->render($file, array_merge([
            'title' => $page->title,
            'content' => $page,
            'seo' => $metaTags,
        ], $page->metadata));
    }
}
