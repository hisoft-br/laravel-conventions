<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('publishes files to upstream and local', function (): void {
    File::deleteDirectory(base_path('.ai'));

    Artisan::call('vendor:publish', [
        '--tag' => 'hisoft-ai',
    ]);

    expect(base_path('.ai/upstream/conventions.md'))->toBeFile();
    expect(base_path('.ai/local/overrides.md'))->toBeFile();
});
