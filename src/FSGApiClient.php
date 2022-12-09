<?php

namespace FSGAddressCheck;

use FSGAddressCheck\DTO\AddressSearchResponse;
use FSGAddressCheck\DTO\AwsCognitoTokenDTO;
use FSGAddressCheck\DTO\Location;
use FSGAddressCheck\DTO\ProductsResponse;
use FSGAddressCheck\DTO\SiteQualificationResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FSGApiClient
{
	public Client $client;

	/**
	 * @throws GuzzleException
	 */
	public static function make(string $username, string $password, string $clientId): self
	{
		// Todo - make tokens cacheable.
		$tokens = (new AwsCognitoAuth())
			->clientId($clientId)
			->region('ap-southeast-2')
			->username($username)
			->password($password)
			->getTokens();

		return new FSGApiClient($tokens);
	}

	public function __construct(protected AwsCognitoTokenDTO $token)
	{
		$this->client = new Client([
			'headers' => [
				'Authorization' => $token->id_token,
				'Content-Type' => 'application/json',
				'accpet' => 'application/json',
			],
		]);
	}

	/**
	 * @param string $searchTerm
	 * @return AddressSearchResponse
	 * @throws GuzzleException
	 * @throws UnknownProperties
	 */
	public function addressSearch(string $searchTerm): AddressSearchResponse
	{
		$response = $this->client->post('https://api.fsgutils.com/beta/nbn/address/search', [
			'json' => [
				'fullText' => $searchTerm,
			],
		]);

		return new AddressSearchResponse(['locations' => json_decode($response->getBody()->getContents(), true)]);
	}

	/**
	 * @throws UnknownProperties
	 * @throws GuzzleException
	 */
	public function locSearch(string $id): Location
	{
		$response = $this->client->get("https://api.fsgutils.com/beta/nbn/address/$id");
		return new Location(json_decode($response->getBody()->getContents(), true));
	}

	/**
	 * @throws UnknownProperties
	 * @throws GuzzleException
	 */
	public function products(): ProductsResponse
	{
		$response = $this->client->get('https://api.fsgutils.com/beta/manage/products');
		return new ProductsResponse(['products' => json_decode($response->getBody()->getContents(), true)['result']]);
	}

	/**
	 * @throws UnknownProperties
	 * @throws GuzzleException
	 */
	public function siteQualification(string $locationId): SiteQualificationResponse
	{
		$response = $this->client->get('https://api.fsgutils.com/beta/manage/siteQualifications/' . $locationId);
		return new SiteQualificationResponse(json_decode($response->getBody()->getContents(), true)['result']);
	}
}
