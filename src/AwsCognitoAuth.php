<?php

namespace FSGAddressCheck;


use Exception;
use FSGAddressCheck\DTO\AwsCognitoTokenDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * A fluent USER_PASSWORD_AUTH flow for AWS Cognito, because Fuck Amazon.
 */
class AwsCognitoAuth
{

	public Client $client;
	public array $headers;
	public string $region;

	private string $clientId;
	private string $username;
	private string $password;

	public function __construct()
	{
		$this->client = new Client();
	}

	public function setHeaders(array $headers): self
	{
		$this->headers = $headers;
		return $this;
	}

	public function region(string $region): self
	{
		$this->region = $region;
		return $this;
	}

	public function clientId(string $clientId): self
	{
		$this->clientId = $clientId;
		return $this;
	}

	public function username(string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function password(string $password): self
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * Takes a username and password, returning the tokens.
	 *
	 * @throws GuzzleException
	 * @throws Exception
	 */
	public function getTokens(): AwsCognitoTokenDTO
	{
		if (!isset($this->clientId)) {
			throw new Exception('Client ID is required');
		}
		if (!isset($this->username)) {
			throw new Exception('Username is required');
		}
		if (!isset($this->password)) {
			throw new Exception('Password is required');
		}
		if (!isset($this->region)) {
			throw new Exception('Region is required');
		}

		$this->setHeaders([
			'Content-Type' => 'application/x-amz-json-1.1',
			'X-Amz-Target' => 'AWSCognitoIdentityProviderService.InitiateAuth',
		]);

		$this->client = new Client([
			'headers' => $this->headers
		]);

		$response = $this->client->post("https://cognito-idp.$this->region.amazonaws.com", [
			RequestOptions::JSON => [
				'AuthParameters' => [
					'USERNAME' => $this->username,
					'PASSWORD' => $this->password,
				],
				'AuthFlow' => 'USER_PASSWORD_AUTH',
				'ClientId' => $this->clientId,
			],
			'headers' => $this->headers
		]);

		$decoded = json_decode($response->getBody()->getContents(), true);

		return new AwsCognitoTokenDTO([
			'access_token' => $decoded['AuthenticationResult']['AccessToken'],
			'id_token' => $decoded['AuthenticationResult']['IdToken'],
			'refresh_token' => $decoded['AuthenticationResult']['RefreshToken'],
			'token_type' => $decoded['AuthenticationResult']['TokenType'],
			'expires_in' => $decoded['AuthenticationResult']['ExpiresIn']
		]);
	}

}
