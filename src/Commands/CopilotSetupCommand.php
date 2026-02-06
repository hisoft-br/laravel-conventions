<?php

namespace Hisoft\Conventions\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Command to install Copilot instructions.
 *
 * Copies the instructions file from the package to `.github/`,
 * allowing GitHub Copilot to automatically load the project conventions.
 *
 * @package Hisoft\Conventions\Commands
 */
class CopilotSetupCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'hisoft:copilot 
                            {--api : Install instructions for API projects (PHP only)}
                            {--inertia : Install instructions for Inertia projects (PHP + Vue/React)}
                            {--force : Overwrite existing file without confirmation}';

    /**
     * @var string
     */
    protected $description = 'Install Copilot instructions for Hisoft conventions';

    /**
     * Execute the command.
     */
    public function handle(): int
    {
        $type = $this->resolveType();

        if ($type === null) {
            return self::FAILURE;
        }

        $source = __DIR__ . "/../../resources/copilot/hisoft-{$type}.md";
        $target = base_path('.github/copilot-instructions.md');

        if (! File::exists($source)) {
            $this->error('Instructions file not found in package.');

            return self::FAILURE;
        }

        if (File::exists($target) && ! $this->option('force')) {
            if (! $this->confirm('File .github/copilot-instructions.md already exists. Overwrite?')) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        }

        File::ensureDirectoryExists(dirname($target));
        File::copy($source, $target);

        $this->info("✓ Copilot instructions ({$type}) installed at .github/copilot-instructions.md");

        return self::SUCCESS;
    }

    /**
     * Resolve the project type from options or prompt.
     */
    private function resolveType(): ?string
    {
        if ($this->option('api') && $this->option('inertia')) {
            $this->error('Cannot use both --api and --inertia options.');

            return null;
        }

        if ($this->option('api')) {
            return 'api';
        }

        if ($this->option('inertia')) {
            return 'inertia';
        }

        $choice = $this->choice(
            'What type of project is this?',
            ['api' => 'API (PHP only)', 'inertia' => 'Inertia (PHP + Vue/React)'],
            'api'
        );

        return $choice;
    }
}
