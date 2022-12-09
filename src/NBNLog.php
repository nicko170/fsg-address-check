<?php

namespace FSGAddressCheck;

use WebDevStudios\OopsWP\Structure\Service;

class NBNLog extends Service
{
	public function register_hooks(): void
	{
		add_action('init', [$this, 'register_post_type']);
		add_filter('post_updated_messages', [$this, 'nbn_log_updated_messages']);
		add_filter('bulk_post_updated_messages', [$this, 'nbn_log_bulk_updated_messages'], 10, 2);

		add_filter('manage_nbn-log_posts_columns', [$this, 'manage_nbn_log_posts_columns']);
		add_action('manage_posts_custom_column', [$this, 'manage_posts_custom_column'], 10, 2);
	}

	public function register_post_type()
	{
		register_post_type(
			'nbn-log',
			[
				'labels' => [
					'name' => __('NBN Logs', 'fsg-address-check'),
					'singular_name' => __('NBN Log', 'fsg-address-check'),
					'all_items' => __('All NBN Logs', 'fsg-address-check'),
					'archives' => __('NBN Log Archives', 'fsg-address-check'),
					'attributes' => __('NBN Log Attributes', 'fsg-address-check'),
					'insert_into_item' => __('Insert into NBN Log', 'fsg-address-check'),
					'uploaded_to_this_item' => __('Uploaded to this NBN Log', 'fsg-address-check'),
					'featured_image' => _x('Featured Image', 'nbn-log', 'fsg-address-check'),
					'set_featured_image' => _x('Set featured image', 'nbn-log', 'fsg-address-check'),
					'remove_featured_image' => _x('Remove featured image', 'nbn-log', 'fsg-address-check'),
					'use_featured_image' => _x('Use as featured image', 'nbn-log', 'fsg-address-check'),
					'filter_items_list' => __('Filter NBN Logs list', 'fsg-address-check'),
					'items_list_navigation' => __('NBN Logs list navigation', 'fsg-address-check'),
					'items_list' => __('NBN Logs list', 'fsg-address-check'),
					'new_item' => __('New NBN Log', 'fsg-address-check'),
					'add_new' => __('Add New', 'fsg-address-check'),
					'add_new_item' => __('Add New NBN Log', 'fsg-address-check'),
					'edit_item' => __('Edit NBN Log', 'fsg-address-check'),
					'view_item' => __('View NBN Log', 'fsg-address-check'),
					'view_items' => __('View NBN Logs', 'fsg-address-check'),
					'search_items' => __('Search NBN Logs', 'fsg-address-check'),
					'not_found' => __('No NBN Logs found', 'fsg-address-check'),
					'not_found_in_trash' => __('No NBN Logs found in trash', 'fsg-address-check'),
					'parent_item_colon' => __('Parent NBN Log:', 'fsg-address-check'),
					'menu_name' => __('NBN Logs', 'fsg-address-check'),
				],
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'supports' => ['title', 'editor'],
				'has_archive' => true,
				'rewrite' => true,
				'query_var' => true,
				'menu_position' => null,
				'menu_icon' => 'dashicons-book-alt',
				'show_in_rest' => true,
				'rest_base' => 'nbn-log',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
			]
		);

	}

	public function nbn_log_updated_messages($messages)
	{
		global $post;

		$permalink = get_permalink($post);

		$messages['nbn-log'] = [
			0 => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1 => sprintf(__('NBN Log updated. <a target="_blank" href="%s">View NBN Log</a>', 'fsg-address-check'), esc_url($permalink)),
			2 => __('Custom field updated.', 'fsg-address-check'),
			3 => __('Custom field deleted.', 'fsg-address-check'),
			4 => __('NBN Log updated.', 'fsg-address-check'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf(__('NBN Log restored to revision from %s', 'fsg-address-check'), wp_post_revision_title((int)$_GET['revision'], false)) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/* translators: %s: post permalink */
			6 => sprintf(__('NBN Log published. <a href="%s">View NBN Log</a>', 'fsg-address-check'), esc_url($permalink)),
			7 => __('NBN Log saved.', 'fsg-address-check'),
			/* translators: %s: post permalink */
			8 => sprintf(__('NBN Log submitted. <a target="_blank" href="%s">Preview NBN Log</a>', 'fsg-address-check'), esc_url(add_query_arg('preview', 'true', $permalink))),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9 => sprintf(__('NBN Log scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview NBN Log</a>', 'fsg-address-check'), date_i18n(__('M j, Y @ G:i', 'fsg-address-check'), strtotime($post->post_date)), esc_url($permalink)),
			/* translators: %s: post permalink */
			10 => sprintf(__('NBN Log draft updated. <a target="_blank" href="%s">Preview NBN Log</a>', 'fsg-address-check'), esc_url(add_query_arg('preview', 'true', $permalink))),
		];

		return $messages;
	}

	public function nbn_log_bulk_updated_messages($bulk_messages, $bulk_counts)
	{
		global $post;

		$bulk_messages['nbn-log'] = [
			/* translators: %s: Number of NBN Logs. */
			'updated' => _n('%s NBN Log updated.', '%s NBN Logs updated.', $bulk_counts['updated'], 'fsg-address-check'),
			'locked' => (1 === $bulk_counts['locked']) ? __('1 NBN Log not updated, somebody is editing it.', 'fsg-address-check') :
				/* translators: %s: Number of NBN Logs. */
				_n('%s NBN Log not updated, somebody is editing it.', '%s NBN Logs not updated, somebody is editing them.', $bulk_counts['locked'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Logs. */
			'deleted' => _n('%s NBN Log permanently deleted.', '%s NBN Logs permanently deleted.', $bulk_counts['deleted'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Logs. */
			'trashed' => _n('%s NBN Log moved to the Trash.', '%s NBN Logs moved to the Trash.', $bulk_counts['trashed'], 'fsg-address-check'),
			/* translators: %s: Number of NBN Logs. */
			'untrashed' => _n('%s NBN Log restored from the Trash.', '%s NBN Logs restored from the Trash.', $bulk_counts['untrashed'], 'fsg-address-check'),
		];

		return $bulk_messages;
	}

	public function manage_nbn_log_posts_columns($columns): array
	{
		return [
			'title' => 'LOC ID',
			'ip_address' => 'IP Address',
			'date' => 'Date',
		];
	}

	public function manage_posts_custom_column($column_id, $post_id): void
	{
		echo get_post_meta($post_id, $column_id, true);
	}
}











