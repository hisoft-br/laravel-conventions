<?php

namespace Hisoft\Conventions;

use Hisoft\Conventions\Commands\CursorSetupCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for publishing engineering conventions.
 *
 * This provider registers convention files that are published
 * to `.ai/upstream` (package files, do not edit) and
 * `.ai/local` (project-specific overrides).
 *
 * Supports two types of projects:
 * - API: `php artisan vendor:publish --tag=hisoft-api`
 * - Inertia: `php artisan vendor:publish --tag=hisoft-inertia`
 *
 * Optionally, for Cursor IDE integration:
 * - `php artisan hisoft:cursor`
 *
 * @package Hisoft\Conventions
 */
class ConventionsServiceProvider extends ServiceProvider
{
    /**
     * Publishes convention files to the project.
     *
     * Files are separated into:
     * - shared/: shared conventions (both types)
     * - api/: API-specific conventions
     * - inertia/: Inertia-specific conventions
     * - local/: project-specific overrides (editable)
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CursorSetupCommand::class,
            ]);
        }
        $basePath = __DIR__ . '/../resources/ai';
        $sharedPath = $basePath . '/shared';
        $apiPath = $basePath . '/api';
        $inertiaPath = $basePath . '/inertia';
        $localPath = $basePath . '/local';
        $agentsPath = __DIR__ . '/../AGENTS.md';

        $upstreamTarget = base_path('.ai/upstream');
        $localTarget = base_path('.ai/local');

        // Tag for API projects (shared + api + local)
        $apiPublishes = $this->buildPublishArray(
            $sharedPath,
            $apiPath,
            $localPath,
            $upstreamTarget,
            $localTarget
        );
        if (File::exists($agentsPath)) {
            $apiPublishes[$agentsPath] = base_path('AGENTS.md');
        }
        $this->publishes($apiPublishes, 'hisoft-api');

        // Tag for Inertia projects (shared + inertia + local)
        $inertiaPublishes = $this->buildPublishArray(
            $sharedPath,
            $inertiaPath,
            $localPath,
            $upstreamTarget,
            $localTarget
        );
        if (File::exists($agentsPath)) {
            $inertiaPublishes[$agentsPath] = base_path('AGENTS.md');
        }
        $this->publishes($inertiaPublishes, 'hisoft-inertia');
    }

    /**
     * Builds the publish array for a combination of folders.
     *
     * @param string $sharedPath Path to shared conventions
     * @param string $typePath Path to type-specific conventions (api or inertia)
     * @param string $localPath Path to local overrides
     * @param string $upstreamTarget Target for upstream files
     * @param string $localTarget Target for local files
     * @return array<string, string> Source to destination mapping
     */
    private function buildPublishArray(
        string $sharedPath,
        string $typePath,
        string $localPath,
        string $upstreamTarget,
        string $localTarget
    ): array {
        $publishes = [];

        // Add shared files
        $this->addFilesToPublish($publishes, $sharedPath, $upstreamTarget);

        // Add type-specific files (api or inertia)
        if (File::isDirectory($typePath)) {
            $this->addFilesToPublish($publishes, $typePath, $upstreamTarget);
        }

        // Add local files
        $this->addFilesToPublish($publishes, $localPath, $localTarget);

        return $publishes;
    }

    /**
     * Adds files from a directory to the publish array.
     *
     * @param array<string, string> $publishes Publish array (by reference)
     * @param string $sourcePath Source path
     * @param string $targetPath Target path
     */
    private function addFilesToPublish(array &$publishes, string $sourcePath, string $targetPath): void
    {
        if (! File::isDirectory($sourcePath)) {
            return;
        }

        foreach (File::allFiles($sourcePath) as $file) {
            $relativePath = $file->getRelativePathname();
            $publishes[$file->getPathname()] = $targetPath . DIRECTORY_SEPARATOR . $relativePath;
        }
    }
}
