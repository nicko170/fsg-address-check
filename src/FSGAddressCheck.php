<?php

namespace FSGAddressCheck;

use Auryn\Injector;
use WebDevStudios\OopsWP\Structure\Plugin\Plugin;
use WebDevStudios\OopsWP\Structure\Service;
use WebDevStudios\OopsWP\Utility\FilePathDependent;

class FSGAddressCheck extends Plugin
{

	protected $services = [
		Settings::class,
		NBNPlans::class,
		AddressSearch::class
	];

	public function __construct(public $file_path, private readonly Injector $injector)
	{
	}

	protected function register_services()
	{
		$objects = array_map(
			function ($object_classname) {
				return [
					'namespace' => $object_classname,
					'object' => $this->injector->make($object_classname),
				];
			},
			$this->services
		);

		$this->services = array_column($objects, 'object', 'namespace');

		array_walk($this->services, [$this, 'register_service']);
	}

	protected function register_service(Service $service)
	{
		if (in_array(FilePathDependent::class, class_uses($service), true)) {
			/* @var $service FilePathDependent */
			$service->set_file_path($this->file_path);
		}

		$service->run();
	}
}
