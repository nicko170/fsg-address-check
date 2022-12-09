<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class ProductsResponse extends DataTransferObject
{
	/** @var Product[] */
	#[CastWith(ArrayCaster::class, itemType: Product::class)]
	public array $products;
}
