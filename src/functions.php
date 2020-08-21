<?php

declare(strict_types=1);

namespace App;

if (!function_exists('container')) {
    function container(?string $id = null): ?object
    {
        $container = Kernel::getKernel()->getContainer();
        if ($id === null) {
            return $container;
        }
        return $container->get($id);
    }
}
