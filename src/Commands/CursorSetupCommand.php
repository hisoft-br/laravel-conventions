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
        $source = __DIR__ . '/../../resources/cursor/hisoft.mdc';
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

        $this->info('✓ Cursor rules installed at .cursor/rules/hisoft.mdc');

        return self::SUCCESS;
    }
}
