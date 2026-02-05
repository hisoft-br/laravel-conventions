<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::deleteDirectory(base_path('.ai'));
});

it('publishes api files with shared conventions', function (): void {
    Artisan::call('vendor:publish', [
        '--tag' => 'hisoft-api',
    ]);

    // Shared files
    expect(base_path('.ai/upstream/conventions.md'))->toBeFile();
    expect(base_path('.ai/upstream/exceptions.md'))->toBeFile();
    expect(base_path('.ai/upstream/testing.md'))->toBeFile();
    expect(base_path('.ai/upstream/phpdocs.md'))->toBeFile();
    expect(base_path('.ai/upstream/static-analysis.md'))->toBeFile();

    // API specific files
    expect(base_path('.ai/upstream/crud.md'))->toBeFile();
    expect(base_path('.ai/upstream/resources.md'))->toBeFile();

    // Local files
    expect(base_path('.ai/local/overrides.md'))->toBeFile();

    // Should NOT include Inertia files
    expect(base_path('.ai/upstream/pages.md'))->not->toBeFile();
    expect(base_path('.ai/upstream/forms.md'))->not->toBeFile();
    expect(base_path('.ai/upstream/props.md'))->not->toBeFile();
    expect(base_path('.ai/upstream/components.md'))->not->toBeFile();
});

it('publishes inertia files with shared conventions', function (): void {
    Artisan::call('vendor:publish', [
        '--tag' => 'hisoft-inertia',
    ]);

    // Shared files
    expect(base_path('.ai/upstream/conventions.md'))->toBeFile();
    expect(base_path('.ai/upstream/exceptions.md'))->toBeFile();
    expect(base_path('.ai/upstream/testing.md'))->toBeFile();
    expect(base_path('.ai/upstream/phpdocs.md'))->toBeFile();
    expect(base_path('.ai/upstream/static-analysis.md'))->toBeFile();

    // Inertia specific files
    expect(base_path('.ai/upstream/pages.md'))->toBeFile();
    expect(base_path('.ai/upstream/forms.md'))->toBeFile();
    expect(base_path('.ai/upstream/props.md'))->toBeFile();
    expect(base_path('.ai/upstream/components.md'))->toBeFile();

    // Local files
    expect(base_path('.ai/local/overrides.md'))->toBeFile();

    // Should NOT include API files
    expect(base_path('.ai/upstream/crud.md'))->not->toBeFile();
    expect(base_path('.ai/upstream/resources.md'))->not->toBeFile();
});

it('does not overwrite local files when publishing again', function (): void {
    // First publish
    Artisan::call('vendor:publish', [
        '--tag' => 'hisoft-api',
    ]);

    // Modify local file
    $localPath = base_path('.ai/local/overrides.md');
    $customContent = "# Custom Overrides\n\nThis was modified.";
    File::put($localPath, $customContent);

    // Publish again without --force
    Artisan::call('vendor:publish', [
        '--tag' => 'hisoft-api',
    ]);

    // Local file should remain unchanged
    expect(File::get($localPath))->toBe($customContent);
});
