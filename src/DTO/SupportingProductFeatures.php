<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SupportingProductFeatures extends DataTransferObject
{
	public ?string $type;
	public ?string $version;
	public ?bool $multicast;
}
