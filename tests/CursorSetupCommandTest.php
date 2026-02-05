<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::deleteDirectory(base_path('.cursor'));
    File::delete(base_path('AGENTS.md'));
});

it('publishes agents file for api projects', function (): void {
    Artisan::call('vendor:publish', ['--tag' => 'hisoft-api']);

    expect(base_path('AGENTS.md'))->toBeFile();

    $content = File::get(base_path('AGENTS.md'));
    expect($content)->toContain('Hisoft Conventions - Agent Rules');
    expect($content)->toContain('Priority');
});

it('publishes agents file for inertia projects', function (): void {
    Artisan::call('vendor:publish', ['--tag' => 'hisoft-inertia']);

    expect(base_path('AGENTS.md'))->toBeFile();

    $content = File::get(base_path('AGENTS.md'));
    expect($content)->toContain('Hisoft Conventions - Agent Rules');
    expect($content)->toContain('Priority');
});

it('installs cursor rules file for api projects', function (): void {
    Artisan::call('hisoft:cursor', ['--api' => true]);

    expect(base_path('.cursor/rules/hisoft.mdc'))->toBeFile();

    $content = File::get(base_path('.cursor/rules/hisoft.mdc'));
    expect($content)->toContain('Hisoft Conventions');
    expect($content)->toContain('globs: **/*.php');
    expect($content)->toContain('.ai/upstream/conventions.md');
    expect($content)->toContain('.ai/upstream/crud.md');
    expect($content)->toContain('.ai/upstream/resources.md');
    expect($content)->toContain('.ai/local/overrides.md');
});

it('installs cursor rules file for inertia projects', function (): void {
    Artisan::call('hisoft:cursor', ['--inertia' => true]);

    expect(base_path('.cursor/rules/hisoft.mdc'))->toBeFile();

    $content = File::get(base_path('.cursor/rules/hisoft.mdc'));
    expect($content)->toContain('Hisoft Conventions');
    expect($content)->toContain('globs: **/*.{php,vue,ts,tsx,js,jsx}');
    expect($content)->toContain('.ai/upstream/conventions.md');
    expect($content)->toContain('.ai/upstream/pages.md');
    expect($content)->toContain('.ai/upstream/forms.md');
    expect($content)->toContain('.ai/upstream/props.md');
    expect($content)->toContain('.ai/upstream/components.md');
    expect($content)->toContain('.ai/local/overrides.md');
});

it('prompts for project type when no option is provided', function (): void {
    $this->artisan('hisoft:cursor')
        ->expectsChoice('What type of project is this?', 'api', ['api' => 'API (PHP only)', 'inertia' => 'Inertia (PHP + Vue/React)'])
        ->assertSuccessful();

    expect(base_path('.cursor/rules/hisoft.mdc'))->toBeFile();

    $content = File::get(base_path('.cursor/rules/hisoft.mdc'));
    expect($content)->toContain('globs: **/*.php');
});

it('fails when both api and inertia options are provided', function (): void {
    $this->artisan('hisoft:cursor', ['--api' => true, '--inertia' => true])
        ->assertFailed();
});

it('does not overwrite existing file without force flag', function (): void {
    // First install
    Artisan::call('hisoft:cursor', ['--api' => true]);

    // Modify the file
    $targetPath = base_path('.cursor/rules/hisoft.mdc');
    $customContent = "# Custom Rules\n\nThis was modified.";
    File::put($targetPath, $customContent);

    // Run again without --force, simulating "no" response to confirmation
    $this->artisan('hisoft:cursor', ['--api' => true])
        ->expectsConfirmation('File .cursor/rules/hisoft.mdc already exists. Overwrite?', 'no')
        ->assertSuccessful();

    // File should remain unchanged
    expect(File::get($targetPath))->toBe($customContent);
});

it('overwrites existing file with force flag', function (): void {
    // First install
    Artisan::call('hisoft:cursor', ['--api' => true]);

    // Modify the file
    $targetPath = base_path('.cursor/rules/hisoft.mdc');
    $customContent = "# Custom Rules\n\nThis was modified.";
    File::put($targetPath, $customContent);

    // Run again with --force
    Artisan::call('hisoft:cursor', ['--api' => true, '--force' => true]);

    // File should be overwritten with original content
    $content = File::get($targetPath);
    expect($content)->toContain('Hisoft Conventions');
    expect($content)->not->toBe($customContent);
});

it('creates cursor rules directory if not exists', function (): void {
    expect(base_path('.cursor/rules'))->not->toBeDirectory();

    Artisan::call('hisoft:cursor', ['--api' => true]);

    expect(base_path('.cursor/rules'))->toBeDirectory();
    expect(base_path('.cursor/rules/hisoft.mdc'))->toBeFile();
});
