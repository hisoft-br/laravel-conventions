<?php

namespace Hisoft\Conventions;

use Illuminate\Support\ServiceProvider;

class ConventionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/ai' => base_path('.ai/upstream'),
        ], 'hisoft-ai');
    }
}
