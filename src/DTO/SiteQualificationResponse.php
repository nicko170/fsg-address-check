<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class SiteQualificationResponse extends DataTransferObject
{
	public string $id;
	public string $csa;
	public string $serviceabilityStatus;

	/** @var Product[] */
	#[CastWith(ArrayCaster::class, itemType: Product::class)]
	public array $applicableProducts;

}
