<?php

namespace App\AssetMapper\Path;

use Symfony\Component\AssetMapper\Path\PublicAssetsFilesystemInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class PublicAssetsFilesystem implements PublicAssetsFilesystemInterface
{
	public function __construct(
		private readonly string $projectDir,
		private readonly Filesystem $filesystem,
	) {}
	
    public function write(string $path, string $contents): void {
		$dir = Path::getDirectory($path);
		if (!\is_dir($dir)) {
			\mkdir($dir);
		}
		\dump(__FUNCTION__, $dir, $path);
		\file_put_contents($path, $contents);
	}

    public function copy(string $originPath, string $path): void {
		\dump(__FUNCTION__, $originPath, $path);
		$this->filesystem->copy($originPath, $path);
	}

    public function getDestinationPath(): string {
		$destination = \sprintf('%s/%s', $this->projectDir, 'public/assets/grin');
		\dump(__FUNCTION__, $destination);
		return $destination;
	}
}