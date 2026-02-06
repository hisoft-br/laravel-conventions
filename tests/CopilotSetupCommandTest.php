<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::deleteDirectory(base_path('.github'));
});

it('installs copilot instructions file for api projects', function (): void {
    Artisan::call('hisoft:copilot', ['--api' => true]);

    expect(base_path('.github/copilot-instructions.md'))->toBeFile();

    $content = File::get(base_path('.github/copilot-instructions.md'));
    expect($content)->toContain('Hisoft Laravel Conventions');
    expect($content)->toContain('.ai/upstream/conventions.md');
    expect($content)->toContain('.ai/upstream/crud.md');
    expect($content)->toContain('.ai/upstream/resources.md');
    expect($content)->toContain('.ai/local/overrides.md');
});

it('installs copilot instructions file for inertia projects', function (): void {
    Artisan::call('hisoft:copilot', ['--inertia' => true]);

    expect(base_path('.github/copilot-instructions.md'))->toBeFile();

    $content = File::get(base_path('.github/copilot-instructions.md'));
    expect($content)->toContain('Hisoft Laravel Conventions');
    expect($content)->toContain('.ai/upstream/conventions.md');
    expect($content)->toContain('.ai/upstream/pages.md');
    expect($content)->toContain('.ai/upstream/forms.md');
    expect($content)->toContain('.ai/upstream/props.md');
    expect($content)->toContain('.ai/upstream/components.md');
    expect($content)->toContain('.ai/local/overrides.md');
});

it('prompts for project type when no option is provided for copilot', function (): void {
    $this->artisan('hisoft:copilot')
        ->expectsChoice('What type of project is this?', 'api', ['api' => 'API (PHP only)', 'inertia' => 'Inertia (PHP + Vue/React)'])
        ->assertSuccessful();

    expect(base_path('.github/copilot-instructions.md'))->toBeFile();

    $content = File::get(base_path('.github/copilot-instructions.md'));
    expect($content)->toContain('.ai/upstream/conventions.md');
});

it('fails when both api and inertia options are provided for copilot', function (): void {
    $this->artisan('hisoft:copilot', ['--api' => true, '--inertia' => true])
        ->assertFailed();
});

it('does not overwrite existing copilot file without force flag', function (): void {
    // First install
    Artisan::call('hisoft:copilot', ['--api' => true]);

    // Modify the file
    $targetPath = base_path('.github/copilot-instructions.md');
    $customContent = "# Custom Instructions\n\nThis was modified.";
    File::put($targetPath, $customContent);

    // Run again without --force, simulating "no" response to confirmation
    $this->artisan('hisoft:copilot', ['--api' => true])
        ->expectsConfirmation('File .github/copilot-instructions.md already exists. Overwrite?', 'no')
        ->assertSuccessful();

    // File should remain unchanged
    expect(File::get($targetPath))->toBe($customContent);
});

it('overwrites existing copilot file with force flag', function (): void {
    // First install
    Artisan::call('hisoft:copilot', ['--api' => true]);

    // Modify the file
    $targetPath = base_path('.github/copilot-instructions.md');
    $customContent = "# Custom Instructions\n\nThis was modified.";
    File::put($targetPath, $customContent);

    // Run again with --force
    Artisan::call('hisoft:copilot', ['--api' => true, '--force' => true]);

    // File should be overwritten with original content
    $content = File::get($targetPath);
    expect($content)->toContain('Hisoft Laravel Conventions');
    expect($content)->not->toBe($customContent);
});

it('creates github directory if not exists for copilot', function (): void {
    expect(base_path('.github'))->not->toBeDirectory();

    Artisan::call('hisoft:copilot', ['--api' => true]);

    expect(base_path('.github'))->toBeDirectory();
    expect(base_path('.github/copilot-instructions.md'))->toBeFile();
});
