<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Project;
use Exception;
use function App\container;

final class ProjectRepository extends ElementRepository
{
    protected string $storageFile = 'projects';

    /**
     * @param bool|null $loadActive
     * @param bool|null $loadClients
     * @return Project[]
     * @throws Exception
     */
    public function getProjects(?bool $loadActive = null, ?bool $loadClients = null): array
    {
        if (empty($this->elements)) {
            $this->loadFromStorage();
        }
        $projects = [];
        foreach ($this->elements as $item) {
            if ($loadActive !== null && $item['active'] !== $loadActive) {
                continue;
            }
            if ($loadClients !== null && $item['client'] !== $loadClients) {
                continue;
            }
            $projects[] = Project::fromArray($item);
        }
        return $projects;
    }

    public static function loadActive(): array
    {
        return container(self::class)->getProjects(true, false);
    }

    public static function loadInactive(): array
    {
        return container(self::class)->getProjects(false, false);
    }

    public static function loadActiveClient(): array
    {
        return container(self::class)->getProjects(true, true);
    }

    public static function loadInactiveClient(): array
    {
        return container(self::class)->getProjects(false, true);
    }
}
