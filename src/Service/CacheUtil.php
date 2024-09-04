<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Validator\Validation;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Contracts\Cache\ItemInterface;

//TODO: CacheUtil
/**
 * Usage:
 * 
 * $value = $cacheUtil->get('KEY', static function(ItemInterface $item, bool &$save) {
 *     return 'REFRESHED_VALUE_'.\random_int(0, 100);
 * });
 * 
 */
class CacheUtil
{
	public const DEFAULT_ADAPTER_KEY = 'php_files';
	
	private AdapterInterface $currentAdapter;
	
    public function __construct(
		// Lazy loading
        private readonly ContainerInterface $container,
    ) {
		$this->currentAdapter = $this->container->get(static::DEFAULT_ADAPTER_KEY);
	}
	

	//###> API ###
	
	/**
	 * Cache Getter/Refresher
	 */
	public function get(
		string $key,
		callable $refresh,
		?string $adapterKey = null,
		?float $beta = null,
		?array &$metadata = null,
	): mixed {
		$adapterKey ??= static::DEFAULT_ADAPTER_KEY;
		
		$this->throwIfAssertionsAreNotValid($adapterKey);
		
		$this->currentAdapter = $this->container->get($adapterKey);
		$result = $this->currentAdapter->get($key, $refresh, $beta, $metadata);
		return $result;
	}
	
	/**
	 * Cache Deleter
	 */
	public function delete(string $key, ?string $adapterKey = null): static {
		$adapterKey ??= static::DEFAULT_ADAPTER_KEY;
		
		$this->throwIfAssertionsAreNotValid($adapterKey);
		
		$this->currentAdapter = $this->container->get($adapterKey);
		$this->currentAdapter->delete($key);
		return $this;
	}
	
	/**
	 * @return AdapterInterface
	 */
	public function getAdapter(?string $adapterKey = null): AdapterInterface {
		if (null === $adapterKey) {
			return $this->currentAdapter;
		}
		
		$this->throwIfAssertionsAreNotValid($adapterKey);
		
		$this->currentAdapter = $this->container->get($adapterKey);
		return $this->currentAdapter;
	}
	
	/**
	 * 
	 */
	public function __call(string $name, array $arguments): mixed {
		return $this->currentAdapter->$name(...$arguments);
	}

	//###< API ###
	
	
	private function throwIfAssertionsAreNotValid(string $adapterKey): void {
		$this->throwOnAdapterKeyDoesNotExitstInContainer($adapterKey);
		$this->throwOnAdapterTypeIsNotAdapterInterface($adapterKey);
	}
	
	private function throwOnAdapterKeyDoesNotExitstInContainer(string $adapterKey): void {
		if (!$this->container->has($adapterKey)) {
			$message = \sprintf('The key: "%s" does NOT exist in the container, check services.yaml', $adapterKey);
			throw new \InvalidArgumentException($message);
		}
	}
	
	private function throwOnAdapterTypeIsNotAdapterInterface(string $adapterKey): void {
		$adapter = $this->container->get($adapterKey);
		$assertType = Validation::createIsValidCallable(
			new Constraints\Type(
				AdapterInterface::class,
			),
		);
		if (!$assertType($adapter)) {
			$message = \sprintf(
				'The configured: "%s" with key: "%s" must implement: "%s" but it does NOT, check services.yaml',
				\get_debug_type($adapter),
				$adapterKey,
				AdapterInterface::class,
			);
			throw new \InvalidArgumentException($message);
		}
	}
}
