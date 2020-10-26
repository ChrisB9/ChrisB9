<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PageController extends AbstractController
{
    public function __construct(private Packages $packages, private TranslatorInterface $translator)
    {
    }

    public function pageAction(Request $request, PageRepository $pageRepository): Response
    {
        try {
            return $this->generate(page: $pageRepository->getPage(
                $request->attributes->get('_route'),
                $request->getLocale()
            ));
        } catch (FileNotFoundException) {
            throw $this->createNotFoundException();
        }
    }

    #[Route(path: '/offline.html', methods: ['GET'])]
    public function offlinePageAction(): Response
    {
        return $this->render('offline.html.twig', ['title' => 'You are offline']);
    }

    private function generate(Page $page): Response
    {
        $image = $this->packages->getUrl(path: 'build/images/share.jpg');
        $title = $this->translator->trans($page->title, domain: 'page');
        $description = $this->translator->trans($page->title . '.description', domain: 'seo') ?: $page->seo['description'] ?? '';
        $page->seo['description'] = $description;
        $seo = array_merge(
            [
                'twitter:title' => $title,
                'twitter:image' => $image,
                'twitter:image:alt' => '',
                'twitter:card' => 'summary',
                'twitter:site' => '@Chris_Ben9',
                'twitter:description' => $description,
            ],
            $page->seo
        );
        $seo['twitter:description'] = $description;
        $metaTags = [
            'name' => $seo,
            'property' => [
                'og:title' => $title,
                'og:image' => $image,
                'og:type' => 'website',
                'og:description' => $description,
            ],
        ];
        $oppositeTranslatedPage = $page->language === 'de' ? 'en' : 'de';
        return $this->render(view: $page->getTemplateFile(), parameters: array_merge([
            'title' => $page->title,
            'content' => $page,
            'seo' => $metaTags,
            'canonical' => $this->generateUrl($page->slug, ['_locale' => 'en'], UrlGeneratorInterface::ABSOLUTE_URL),
            'link' => [
                'href' => $this->generateUrl($page->slug, ['_locale' => $oppositeTranslatedPage]),
                'alternate' => $this->generateUrl($page->slug, ['_locale' => $oppositeTranslatedPage], UrlGeneratorInterface::ABSOLUTE_URL),
                'title' => strtoupper($oppositeTranslatedPage),
                'lang' => $oppositeTranslatedPage,
                'slug' => $page->slug,
            ]
        ], $page->metadata));
    }
}
