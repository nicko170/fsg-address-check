<?php

namespace FSGAddressCheck;

use JetBrains\PhpStorm\NoReturn;
use WebDevStudios\OopsWP\Structure\Service;
use WP_Query;

class AddressSearch extends Service
{
	public function register_hooks()
	{
		add_shortcode('fsg_address_check', [$this, 'fsg_address_check']);
		add_action('wp_ajax_nbn_address_search', [$this, 'nbn_address_search']);
		add_action('wp_ajax_nbn_product_search', [$this, 'nbn_product_search']);
	}

	public function fsg_address_check(): string
	{
		// Load the address search form.
		ob_start();
		require 'templates/address-search-form.php';
		return ob_get_clean();
	}

	public function nbn_address_search(): void
	{
		$address = $_REQUEST['address'];
		$config = get_option('fsg_nbn_api_options');
		$api = FSGApiClient::make($config['username'], $config['password'], $config['client_id']);

		$response = $api->addressSearch($address);

		echo json_encode($response->locations);
		wp_die();
	}

	#[NoReturn]
	public function nbn_product_search(): void
	{
		$locationId = $_REQUEST['location_id'];
		$config = get_option('fsg_nbn_api_options');

		$api = FSGApiClient::make($config['username'], $config['password'], $config['client_id']);
		$response = $api->siteQualification($locationId);

		$availableProducts = collect($response->applicableProducts)->pluck('product')->toArray();

		include 'templates/address-search-tech-results.php';

		// find posts of type nbn-plans that have a meta key of fsg_product_id and a value of one of the available products
		$posts = new WP_Query([
			'post_type' => 'nbn-plans',
			'meta_query' => [
				[
					'key' => 'fsg_product_id',
					'value' => $availableProducts,
					'compare' => 'IN',
				],
			],
		]);

		if (!$posts->have_posts()) {
			get_template_part('content', 'none');
		} else {
			while ($posts->have_posts()) {
				$posts->the_post();
				echo get_the_content();
			}
		}

		wp_die();
	}

}






