<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CollatzConjectureController extends AbstractController
{
    /**
     * @Route("/api/collatz/{number}", methods={"GET"})
     *
     * @param int $number
     * @return JsonResponse
     */
    public function getCollatzConjecture(int $number): JsonResponse
    {
        if ($number > 1_000_000) {
            return new JsonResponse(['success' => false, 'msg' => 'Number is too high']);
        }
        $n = $number;
        $steps = [];
        $step = 0;
        while ($n !== 1) {
            $n = $n % 2 === 0 ? ($n / 2) : (3 * $n + 1);
            $steps[] = ['y' => $n, 'x' => $step++];
        }
        return new JsonResponse(['success' => true, 'steps' => $steps]);
    }
}
