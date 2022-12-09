<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class AddressSearchResponse extends DataTransferObject
{
	/** @var Location[] */
	#[CastWith(ArrayCaster::class, itemType: Location::class)]
	public array $locations;
}
