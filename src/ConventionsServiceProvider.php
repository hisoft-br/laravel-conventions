<?php

namespace Hisoft\Conventions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ConventionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $aiPath = __DIR__ . '/../resources/ai';
        $localPath = $aiPath . '/local';
        $upstreamTarget = base_path('.ai/upstream');
        $localTarget = base_path('.ai/local');

        $publishes = [
            $localPath => $localTarget,
        ];

        foreach (File::allFiles($aiPath) as $file) {
            if (Str::startsWith($file->getPathname(), $localPath . DIRECTORY_SEPARATOR)) {
                continue;
            }

            $relativePath = Str::replaceFirst($aiPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $publishes[$file->getPathname()] = $upstreamTarget . DIRECTORY_SEPARATOR . $relativePath;
        }

        $this->publishes($publishes, 'hisoft-ai');
    }
}
