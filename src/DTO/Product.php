<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class Product extends DataTransferObject
{
	public string $name;
	public string $product;
	public ?string $type;
	public string $rate;
	public ?string $up;
	public ?string $down;
}
