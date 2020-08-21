<?php

declare(strict_types=1);

namespace App\Controller;

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
        return $this->render('page.html.twig', array_merge([
            'title' => $page->title,
            'content' => $page,
        ], $page->metadata));
    }

    /**
     * @param Request $request
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function renderAction(Request $request, PageRepository $pageRepository): Response
    {
        $route = ltrim($request->getPathInfo(), '/');
        $page = $pageRepository->getPage($route);
        return $this->render($page->file, array_merge([
            'title' => $page->title,
        ], $page->metadata));
    }
}
