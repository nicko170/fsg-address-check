<?php

namespace FSGAddressCheck;

use WP_Query;

class AddressSearch
{
	public static function setup(): void
	{
		// Run up a short code to display the address search form.
		add_shortcode('fsg-address-check', function () {
			// Load the address search form.
			ob_start();
			require 'templates/address-search-form.php';
			return ob_get_clean();
		});

		// This is a two step process.
		// - We need to get the address from the user, the search result could have multiple addresses.
		// - The user will then pick one, and we will show products for the selected address.
		add_action('wp_ajax_nbn_address_search', function () {
			$address = $_REQUEST['address'];
			$config = get_option('fsg_nbn_api_options');
			$api = FSGApiClient::make($config['username'], $config['password'], $config['client_id']);

			$response = $api->addressSearch($address);

			echo json_encode($response->locations);
			wp_die();
		});

		// Load the products for the selected location
		add_action('wp_ajax_nbn_product_search', function () {
			$locationId = $_REQUEST['location_id'];
			$config = get_option('fsg_nbn_api_options');
			$api = FSGApiClient::make($config['username'], $config['password'], $config['client_id']);

			$response = $api->siteQualification($locationId);

			$avaliableProducts = collect($response->applicableProducts)->pluck('product')->toArray();

			// find posts of type nbn-plans that have a meta key of fsg_product_id and a value of one of the available products
			$posts = new WP_Query([
				'post_type' => 'nbn-plans',
				'meta_query' => [
					[
						'key' => 'fsg_product_id',
						'value' => $avaliableProducts,
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
		});
	}
}






