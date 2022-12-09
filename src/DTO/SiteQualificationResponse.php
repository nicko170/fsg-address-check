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

	public SupportingTechnology $supportingTechnology;
	public SupportingRelatedLocationFeatures $supportingRelatedLocationFeatures;

	/** @var SupportingResources[] */
	#[CastWith(ArrayCaster::class, itemType: SupportingResources::class)]
	public array $supportingResources;

	/** @var SupportingProductFeatures[] */
	#[CastWith(ArrayCaster::class, itemType: SupportingProductFeatures::class)]
	public array $supportingProductFeatures;

	/** @var Product[] */
	#[CastWith(ArrayCaster::class, itemType: Product::class)]
	public array $applicableProducts;
}
