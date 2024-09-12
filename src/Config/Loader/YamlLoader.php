<?php

namespace App\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends FileLoader
{
    public function load(mixed $resource, ?string $type = null): mixed
    {
        if (\is_array($resource)) {
            $res = [];
            foreach ($resource as $resource) {
                $res[] = $this->load($resource, $type);
            }
            return $res;
        }

        return Yaml::parseFile($resource);
    }

    public function supports($resource, $type = null): bool
    {
        return \is_string($resource) && 'yaml' === \pathinfo(
            $resource,
            \PATHINFO_EXTENSION
        );
    }
}
