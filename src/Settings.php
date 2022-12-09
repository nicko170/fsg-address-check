<?php

namespace FSGAddressCheck;

use WebDevStudios\OopsWP\Structure\Service;

class Settings extends Service
{
	public function register_hooks()
	{
		add_action('admin_menu', [$this, 'admin_menu']);
		add_action('admin_init', [$this, 'admin_init']);

		add_action('wp_ajax_import_fsg_plans', [$this, 'import_fsg_plans']);
	}

	public function admin_menu(): void
	{
		add_options_page('FSG NBN API', 'FSG API', 'manage_options', 'fsg-nbn-api', function () {
			?>
			<h2>FSG NBN API Settings</h2>
			<form action="options.php" method="post">
				<?php
				settings_fields('fsg_nbn_api_options');
				do_settings_sections('fsg_nbn_api'); ?>
				<input name="submit" class="button button-primary" type="submit"
					   value="<?php esc_attr_e('Save'); ?>"/>
			</form>

			<h2>Import Plans</h2>
			<button id="import-plans" class="button button-primary">Import Plans</button>
			<div id="import-plans-status" style="display: none">
				<code id="response"></code>
			</div>
			<script>
				jQuery(document).ready(function ($) {
					$('#import-plans').click(function () {
						$.ajax({
							url: ajaxurl,
							data: {
								action: 'import_fsg_plans'
							},
							success: function (data) {
								console.log(data);
								$('#response').html(data);
								$('#import-plans-status').show();
							}
						});
					});
				});
			</script>
			<?php
		});
	}

	public function admin_init(): void
	{
		register_setting('fsg_nbn_api_options', 'fsg_nbn_api_options', fn($input) => $input);

		add_settings_section('api_settings', 'API Settings', fn() => print('<p>Here you can set all the options for using the API</p>'), 'fsg_nbn_api');

		add_settings_field('fsg_nbn_api_setting_client_id', 'Client ID', fn() => $this->settings_field('client_id', true), 'fsg_nbn_api', 'api_settings');
		add_settings_field('fsg_nbn_api_setting_username', 'Username', fn() => $this->settings_field('username'), 'fsg_nbn_api', 'api_settings');
		add_settings_field('fsg_nbn_api_setting_password', 'Password', fn() => $this->settings_field('password', true), 'fsg_nbn_api', 'api_settings');
	}

	public function import_fsg_plans(): void
	{
		global $wpdb;
		$config = get_option('fsg_nbn_api_options');

		$api = FSGApiClient::make($config['username'], $config['password'], $config['client_id']);
		$products = $api->products();

		// Loop through the plans and insert or update the post of type nbn_plans.
		echo "Importing plans...\n";
		foreach ($products->products as $product) {
			echo "Importing {$product->name}...\n";

			// We are going to grab the post ID from the postmeta table, based on the product ID.
			$post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'fsg_product_id' AND meta_value = %s", $product->product));
			//echo $product->name . ' - ' . $product->product . ' - ' . $product->type . ' - ' . $product->rate . ' - ' . $product->up . ' - ' . $product->down . ' - ' . $post_id . PHP_EOL;
			$post = [
				'ID' => $post_id,
				'post_title' => $product->name,
				'post_type' => 'nbn-plans',
				'post_status' => 'draft',
				'meta_input' => [
					'fsg_product_name' => $product->name,
					'fsg_product_id' => $product->product,
					'fsg_product_type' => $product->type,
					'fsg_product_rate' => $product->rate,
					'fsg_product_up' => $product->up,
					'fsg_product_down' => $product->down,
				],
			];

			if ($post_id) {
				// We trigger an update
				$post['ID'] = $post_id;
			} else {
				// We remove the title, as we don't want to update the title.
				unset($post['post_title']);
				unset($post['post_status']);
			}

			wp_insert_post($post);
		}

		echo 'Done';
		wp_die();
	}

	public static function settings_field(string $name, bool $password = false): void
	{
		$options = get_option('fsg_nbn_api_options') ?: [];
		$value = $options[$name] ?? '';
		$type = $password ? 'password' : 'text';
		echo "<input id='fsg_nbn_api_setting_$name' name='fsg_nbn_api_options[$name]' size='40' type='$type' value='$value' />";
	}

}



