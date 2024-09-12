<?php

namespace App\RateLimit\Storage;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\RateLimiter\Storage\StorageInterface;
use Symfony\Component\RateLimiter\LimiterStateInterface;

//TODO: DefaultRateLimiterStorage (кэш всегда валидный, неважно debug true|false (ConfigCache предоставляет возможность сделать кэш невалидным по дате изменения meta файлов только при debug, но мы этого тут не используем :) этот компонент подходит и предоставляет даже избыток функциональности)
class DefaultRateLimiterStorage implements StorageInterface
{
    private readonly string $nonResolvedAbsPath;

    public function __construct(
        #[Autowire('%kernel.cache_dir%')]
        private readonly string $cacheDir,
        #[Autowire('@slugger')]
        private readonly SluggerInterface $slugger,
    ) {
        $this->nonResolvedAbsPath = \rtrim($cacheDir, '/\\') . '/rate_limit/storage/%s.yaml';
    }

    public function save(LimiterStateInterface $limiterState): void
    {
        $id = $limiterState->getId();
        $resolvedPath = $this->getResolvedCacheAbsPath($id);

        $configCache = new ConfigCache($resolvedPath, true);

        $data = [
            'id' => $id,
            'limiter' => \serialize($limiterState),
        ];
        $yamlData = Yaml::dump($data);
        $configCache->write($yamlData, [

        ]/* true === debug Дата модификации этих файлов будет учитываться при определении свежести кеша */);
    }

    public function fetch(string $limiterStateId): ?LimiterStateInterface
    {
        $resolvedPath = $this->getResolvedCacheAbsPath($limiterStateId);
        $configCache = new ConfigCache($resolvedPath, true);
        if (!$configCache->isFresh()) {
            return null;
        }

        $data = Yaml::parseFile($resolvedPath);
        $id = $data['id'];
        $limiter = \unserialize($data['limiter']);
        return $limiter;
    }

    public function delete(string $limiterStateId): void
    {
        $resolvedPath = $this->getResolvedCacheAbsPath($limiterStateId);
        if (\is_file($resolvedPath)) {
            \unlink($resolvedPath);
        }
    }

    private function getResolvedCacheAbsPath(string $id): string
    {
        return \sprintf($this->nonResolvedAbsPath, $this->slugger->slug($id));
    }
}
