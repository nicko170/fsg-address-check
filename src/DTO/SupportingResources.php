<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SupportingResources extends DataTransferObject
{
	public ?string $id;
	public ?string $type;
	public ?string $version;
	public ?bool $networkCoexistence;
	public ?string $serviceabilityClass;
	public ?string $subsequentInstallationChargeApplies;
	public ?string $NBNServiceStatus;
}
