<?php

namespace FSGAddressCheck\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class AwsCognitoTokenDTO extends DataTransferObject
{
	public string $access_token;
	public string $id_token;
	public string $refresh_token;
	public int $expires_in;
	public string $token_type;
}

