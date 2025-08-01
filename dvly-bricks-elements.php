<?php
/**
 * Plugin Name: DVLY Bricks Elements
 * Description: Custom Bricks Builder elements by DVLY for WooCommerce and more.
 * Version: 1.0.2
 * Author: DVLY
 * 
 * Update URI: https://github.com/devvly/dvly-bricks-elements
 */

if (!defined('ABSPATH')) exit;

/**
 * Configuration
 */
$dvly_config = [
    'slug'         => 'dvly-bricks-elements',
    'user'         => 'devvly',
    'repo'         => 'dvly-bricks-elements',
    'plugin_file'  => plugin_basename(__FILE__),
    'element_slugs' => [
        'hero',
        'icon-features',
        'featured-product-categories',
        'featured-products',
        'image-text-block',
        'call-to-action',
        'logo-grid'
    ]
];

// Register Bricks elements
add_action('init', function () use ($dvly_config) {
    foreach ($dvly_config['element_slugs'] as $slug) {
        $file = plugin_dir_path(__FILE__) . "elements/{$slug}.php";
        if (file_exists($file)) {
            \Bricks\Elements::register_element($file);
        }
    }
}, 11);

// Enqueue element styles
add_action('wp_enqueue_scripts', function () use ($dvly_config) {
    $base_dir = plugin_dir_path(__FILE__) . 'elements/';
    $base_uri = plugin_dir_url(__FILE__) . 'elements/';

    foreach ($dvly_config['element_slugs'] as $slug) {
        $css_file = "{$base_dir}{$slug}.css";
        if (file_exists($css_file)) {
            wp_enqueue_style(
                'brxe-dvly-' . $slug,
                $base_uri . "{$slug}.css",
                [],
                filemtime($css_file)
            );
        }
    }
});

/**
 * GitHub-based update check
 */
add_filter('site_transient_update_plugins', function ($transient) use ($dvly_config) {
    if (empty($transient->checked)) return $transient;

    $response = wp_remote_get("https://api.github.com/repos/{$dvly_config['user']}/{$dvly_config['repo']}/releases/latest", [
        'headers' => ['Accept' => 'application/vnd.github.v3+json']
    ]);

    if (is_wp_error($response)) return $transient;

    $release = json_decode(wp_remote_retrieve_body($response));
    file_put_contents(__DIR__ . '/update-log.txt', print_r($release, true));
    
    if (!isset($release->tag_name)) return $transient;

    $new_version = ltrim($release->tag_name, 'v');
    $current_version = get_plugin_data(__FILE__)['Version'];

    if (version_compare($new_version, $current_version, '>')) {
        $transient->response[$dvly_config['plugin_file']] = (object) [
            'slug'        => $dvly_config['slug'],
            'plugin'      => $dvly_config['plugin_file'],
            'new_version' => $new_version,
            'url'         => $release->html_url,
            'package'     => $release->assets[0]->browser_download_url ?? '',
        ];
    }

    return $transient;
});

/**
 * Plugin info popup (View details)
 */
add_filter('plugins_api', function ($result, $action, $args) use ($dvly_config) {
    if ($action !== 'plugin_information' || $args->slug !== $dvly_config['slug']) {
        return $result;
    }

    $response = wp_remote_get("https://api.github.com/repos/{$dvly_config['user']}/{$dvly_config['repo']}/releases/latest", [
        'headers' => ['Accept' => 'application/vnd.github.v3+json']
    ]);

    if (is_wp_error($response)) return $result;

    $release = json_decode(wp_remote_retrieve_body($response));
    $version = ltrim($release->tag_name, 'v');

    return (object) [
        'name'         => 'DVLY Bricks Elements',
        'slug'         => $dvly_config['slug'],
        'version'      => $version,
        'author'       => '<a href="https://github.com/' . $dvly_config['user'] . '">DVLY</a>',
        'homepage'     => $release->html_url,
        'download_link' => $release->assets[0]->browser_download_url ?? '',
        'sections'     => [
            'description' => $release->body ?? 'Custom Bricks Builder elements for WooCommerce and more.',
        ],
    ];
}, 10, 3);
