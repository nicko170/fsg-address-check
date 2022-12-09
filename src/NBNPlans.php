<?php

namespace FSGAddressCheck;

class NBNPlans
{
	public function __construct()
	{
		add_action('init', [$this, 'register_post_type']);
		add_filter('manage_nbn-plans_posts_columns', [$this, 'manage_nbn_plans_posts_columns']);
		add_action('manage_posts_custom_column', [$this, 'manage_posts_custom_column'], 10, 2);
		add_filter('post_updated_messages', [$this, 'post_updated_messages']);
		add_filter('bulk_post_updated_messages', [$this, 'bulk_post_updated_messages'], 10, 2);
	}

	public function register_post_type(): void
	{
		register_post_type(
			'nbn-plans',
			[
				'labels' => [
					'name' => __('NBN Plans', 'fsg-address-check'),
					'singular_name' => __('NBN Plan', 'fsg-address-check'),
					'all_items' => __('All NBN Plans', 'fsg-address-check'),
					'archives' => __('NBN Plan Archives', 'fsg-address-check'),
					'attributes' => __('NBN Plan Attributes', 'fsg-address-check'),
					'insert_into_item' => __('Insert into NBN Plan', 'fsg-address-check'),
					'uploaded_to_this_item' => __('Uploaded to this NBN Plan', 'fsg-address-check'),
					'featured_image' => _x('Featured Image', 'nbn-plans', 'fsg-address-check'),
					'set_featured_image' => _x('Set featured image', 'nbn-plans', 'fsg-address-check'),
					'remove_featured_image' => _x('Remove featured image', 'nbn-plans', 'fsg-address-check'),
					'use_featured_image' => _x('Use as featured image', 'nbn-plans', 'fsg-address-check'),
					'filter_items_list' => __('Filter NBN Plans list', 'fsg-address-check'),
					'items_list_navigation' => __('NBN Plans list navigation', 'fsg-address-check'),
					'items_list' => __('NBN Plans list', 'fsg-address-check'),
					'new_item' => __('New NBN Plan', 'fsg-address-check'),
					'add_new' => __('Add New', 'fsg-address-check'),
					'add_new_item' => __('Add New NBN Plan', 'fsg-address-check'),
					'edit_item' => __('Edit NBN Plan', 'fsg-address-check'),
					'view_item' => __('View NBN Plan', 'fsg-address-check'),
					'view_items' => __('View NBN Plans', 'fsg-address-check'),
					'search_items' => __('Search NBN Plans', 'fsg-address-check'),
					'not_found' => __('No NBN Plans found', 'fsg-address-check'),
					'not_found_in_trash' => __('No NBN Plans found in trash', 'fsg-address-check'),
					'parent_item_colon' => __('Parent NBN Plan:', 'fsg-address-check'),
					'menu_name' => __('NBN Plans', 'fsg-address-check'),
				],
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'supports' => ['title', 'editor', 'excerpt', 'custom-fields', 'revisions', 'post-formats'],
				'has_archive' => true,
				'rewrite' => true,
				'query_var' => true,
				'menu_position' => null,
				'menu_icon' => 'dashicons-book-alt',
				'show_in_rest' => true,
				'rest_base' => 'nbn-plans',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
			]
		);
	}

	public function manage_nbn_plans_posts_columns($columns): array
	{
		return [
			'title' => 'Plan Name',
			'fsg_product_id' => 'Product ID',
			'fsg_product_type' => 'Product Type',
			'fsg_product_rate' => 'Cost Price',
			'fsg_product_down' => 'Mbps Down',
			'fsg_product_up' => 'Mbps Up',
			'date' => 'Date',
		];
	}

	public function manage_posts_custom_column($column_id, $post_id): void
	{
		echo get_post_meta($post_id, $column_id, true);
	}

	public function post_updated_messages($messages): array
	{
		global $post;

		$permalink = get_permalink($post);

		$messages['nbn-plans'] = [
			0 => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1 => sprintf(__('NBN Plan updated. <a target="_blank" href="%s">View NBN Plan</a>', 'fsg-address-check'), esc_url($permalink)),
			2 => __('Custom field updated.', 'fsg-address-check'),
			3 => __('Custom field deleted.', 'fsg-address-check'),
			4 => __('NBN Plan updated.', 'fsg-address-check'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf(__('NBN Plan restored to revision from %s', 'fsg-address-check'), wp_post_revision_title((int)$_GET['revision'], false)) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/* translators: %s: post permalink */
			6 => sprintf(__('NBN Plan published. <a href="%s">View NBN Plan</a>', 'fsg-address-check'), esc_url($permalink)),
			7 => __('NBN Plan saved.', 'fsg-address-check'),
			/* translators: %s: post permalink */
			8 => sprintf(__('NBN Plan submitted. <a target="_blank" href="%s">Preview NBN Plan</a>', 'fsg-address-check'), esc_url(add_query_arg('preview', 'true', $permalink))),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9 => sprintf(__('NBN Plan scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview NBN Plan</a>', 'fsg-address-check'), date_i18n(__('M j, Y @ G:i', 'fsg-address-check'), strtotime($post->post_date)), esc_url($permalink)),
			/* translators: %s: post permalink */
			10 => sprintf(__('NBN Plan draft updated. <a target="_blank" href="%s">Preview NBN Plan</a>', 'fsg-address-check'), esc_url(add_query_arg('preview', 'true', $permalink))),
		];

		return $messages;
	}

	public function bulk_post_updated_messages($bulk_messages, $bulk_counts): array
	{
		global $post;

		$bulk_messages['nbn-plans'] = [
			/* translators: %s: Number of NBN Plans. */
			'updated' => _n('%s NBN Plan updated.', '%s NBN Plans updated.', $bulk_counts['updated'], 'fsg-address-check'),
			'locked' => (1 === $bulk_counts['locked']) ? __('1 NBN Plan not updated, somebody is editing it.', 'fsg-address-check') :
				/* translators: %s: Number of NBN Plans. */
				_n('%s NBN Plan not updated, somebody is editing it.', '%s NBN Plans not updated, somebody is editing them.', $bulk_counts['locked'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Plans. */
			'deleted' => _n('%s NBN Plan permanently deleted.', '%s NBN Plans permanently deleted.', $bulk_counts['deleted'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Plans. */
			'trashed' => _n('%s NBN Plan moved to the Trash.', '%s NBN Plans moved to the Trash.', $bulk_counts['trashed'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Plans. */
			'untrashed' => _n('%s NBN Plan restored from the Trash.', '%s NBN Plans restored from the Trash.', $bulk_counts['untrashed'], 'fsg-address-check'),
		];

		return $bulk_messages;
	}

}


