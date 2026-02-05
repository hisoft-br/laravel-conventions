<?php

namespace Hisoft\Conventions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para publicação de convenções de engenharia.
 *
 * Este provider registra arquivos de convenções que são publicados
 * em `.ai/upstream` (arquivos do pacote, não editáveis) e
 * `.ai/local` (sobrescritas específicas do projeto).
 *
 * Suporta dois tipos de projeto:
 * - API: `php artisan vendor:publish --tag=hisoft-api`
 * - Inertia: `php artisan vendor:publish --tag=hisoft-inertia`
 *
 * @package Hisoft\Conventions
 */
class ConventionsServiceProvider extends ServiceProvider
{
    /**
     * Publica os arquivos de convenções para o projeto.
     *
     * Arquivos são separados em:
     * - shared/: convenções compartilhadas (ambos os tipos)
     * - api/: convenções exclusivas para projetos API
     * - inertia/: convenções exclusivas para projetos Inertia
     * - local/: sobrescritas específicas do projeto (editáveis)
     */
    public function boot(): void
    {
        $basePath = __DIR__ . '/../resources/ai';
        $sharedPath = $basePath . '/shared';
        $apiPath = $basePath . '/api';
        $inertiaPath = $basePath . '/inertia';
        $localPath = $basePath . '/local';

        $upstreamTarget = base_path('.ai/upstream');
        $localTarget = base_path('.ai/local');

        // Tag para projetos API (shared + api + local)
        $this->publishes(
            $this->buildPublishArray($sharedPath, $apiPath, $localPath, $upstreamTarget, $localTarget),
            'hisoft-api'
        );

        // Tag para projetos Inertia (shared + inertia + local)
        $this->publishes(
            $this->buildPublishArray($sharedPath, $inertiaPath, $localPath, $upstreamTarget, $localTarget),
            'hisoft-inertia'
        );
    }

    /**
     * Constrói o array de publicação para uma combinação de pastas.
     *
     * @param string $sharedPath Caminho para convenções compartilhadas
     * @param string $typePath Caminho para convenções específicas do tipo (api ou inertia)
     * @param string $localPath Caminho para sobrescritas locais
     * @param string $upstreamTarget Destino para arquivos upstream
     * @param string $localTarget Destino para arquivos locais
     * @return array<string, string> Mapeamento de origem para destino
     */
    private function buildPublishArray(
        string $sharedPath,
        string $typePath,
        string $localPath,
        string $upstreamTarget,
        string $localTarget
    ): array {
        $publishes = [];

        // Adiciona arquivos compartilhados
        $this->addFilesToPublish($publishes, $sharedPath, $upstreamTarget);

        // Adiciona arquivos específicos do tipo (api ou inertia)
        if (File::isDirectory($typePath)) {
            $this->addFilesToPublish($publishes, $typePath, $upstreamTarget);
        }

        // Adiciona arquivos locais
        $this->addFilesToPublish($publishes, $localPath, $localTarget);

        return $publishes;
    }

    /**
     * Adiciona arquivos de um diretório ao array de publicação.
     *
     * @param array<string, string> $publishes Array de publicação (por referência)
     * @param string $sourcePath Caminho de origem
     * @param string $targetPath Caminho de destino
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
