<?php

namespace Hisoft\Conventions\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Command to install Cursor rules.
 *
 * Copies the rules file from the package to `.cursor/rules/`,
 * allowing Cursor IDE to automatically load the project conventions.
 *
 * @package Hisoft\Conventions\Commands
 */
class CursorSetupCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'hisoft:cursor 
                            {--api : Install rules for API projects (PHP only)}
                            {--inertia : Install rules for Inertia projects (PHP + Vue/React)}
                            {--force : Overwrite existing file without confirmation}';

    /**
     * @var string
     */
    protected $description = 'Install Cursor rules for Hisoft conventions';

    /**
     * Execute the command.
     */
    public function handle(): int
    {
        $type = $this->resolveType();

        if ($type === null) {
            return self::FAILURE;
        }

        $source = __DIR__ . "/../../resources/cursor/hisoft-{$type}.mdc";
        $target = base_path('.cursor/rules/hisoft.mdc');

        if (! File::exists($source)) {
            $this->error('Rules file not found in package.');

            return self::FAILURE;
        }

        if (File::exists($target) && ! $this->option('force')) {
            if (! $this->confirm('File .cursor/rules/hisoft.mdc already exists. Overwrite?')) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        }

        File::ensureDirectoryExists(dirname($target));
        File::copy($source, $target);

        $this->info("✓ Cursor rules ({$type}) installed at .cursor/rules/hisoft.mdc");

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
