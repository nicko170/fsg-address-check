<?php

namespace FSGAddressCheck\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class Location extends DataTransferObject
{
	public ?string $id;
	public ?string $roadNumber1;
	public ?string $lotNumber;
	public ?string $formattedAddress;
	public ?string $roadName;
	public ?string $roadTypeCode;
	public ?string $postcode;
	public ?string $localityName;
	public ?string $stateTerritoryCode;
	public ?string $unitTypeCode;
	public ?string $unitNumber;
	public ?string $geographicDatum;
	public ?string $latitude;
	public ?string $longitude;
	public ?string $href;
}
