<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SupportingTechnology extends DataTransferObject
{
	public ?string $primaryAccessTechnology;
	public ?string $serviceabilityClass;
	public ?string $alternativeTechnology;
	public ?bool $businessFibre;
}
