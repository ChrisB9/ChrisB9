<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class PolicyHeaderListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->add([
            'Permissions-Policy' => 'fullscreen=(self)'
        ]);
    }
}
