<?php

declare(strict_types=1);

namespace App\Controller;

use cbenco\TimestampGenerator\TimeStampGenerator;
use DateTime;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TimestampGeneratorController extends AbstractController
{
    /**
     * @Route("/api/generate-timestamps", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function generate(Request $request): Response
    {
        $body = json_decode($request->getContent());
        $list = (new TimeStampGenerator(
            DateTimeImmutable::createFromMutable((new DateTime($body->start))),
            DateTimeImmutable::createFromMutable((new DateTime($body->end))),
        ))->generateRange((int)$body->limit);
        return $this->json($list);
    }
}
