<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SupportingRelatedLocationFeatures extends DataTransferObject
{
	public ?string $networkBoundaryPoint;
	public ?bool $newDevelopmentsChargeApplies;
	public ?string $nonPremiseLocation;
}
