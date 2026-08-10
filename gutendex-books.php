<?php
/**
 * Plugin Name: gutendex-books
 * Description: API for viewing gutenberg books
 * Version: 1.0.0
 * Author URI: /
 * Text Domain: gutendex-books
 **/

define('GUTENDEX_BOOKS__PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once GUTENDEX_BOOKS__PLUGIN_DIR . 'includes/class.gutendex-books-api.php';
require_once GUTENDEX_BOOKS__PLUGIN_DIR . 'includes/class.gutendex-books-view-list.php';
require_once GUTENDEX_BOOKS__PLUGIN_DIR . 'includes/class.gutendex-books-admin.php';
require_once GUTENDEX_BOOKS__PLUGIN_DIR . 'includes/class.gutendex-books-ajax.php';
require_once GUTENDEX_BOOKS__PLUGIN_DIR . 'includes/class.gutendex-books-cli.php';


class Gutendex_Books
{
    public function __construct()
    {
        $this->register_actions();
        $this->register_shortcodes();
        register_activation_hook(__FILE__, [ $this, 'gutendex_activate' ]);



        new Gutendex_Books_Ajax();
        new Gutendex_Books_Admin();
    }


    private function register_shortcodes()
    {
        add_shortcode('books_list', [$this, 'gutendex_shortcode_books_list']);
    }

    private function register_actions()
    {
        add_action('wp_enqueue_scripts', [$this, 'gutendex_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'gutendex_tailwind']);
        add_action('plugins_loaded', [$this, 'gutendex_load_textdomain']);
    }
    public function gutendex_scripts()
    {
        wp_enqueue_script(
            'gutendex-js',
            plugin_dir_url(__FILE__) . 'assets/js/gutendex.js',
            [],
            '1.0',
            true
        );


        wp_localize_script(
            'gutendex-js',
            'gutendex_ajax',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'loading_text' => __('Searching...', 'gutendex-books'),
                'error_text' => __('An error occurred while searching.', 'gutendex-books'),
            ]
        );
    }

    public function gutendex_tailwind()
    {
        wp_enqueue_script(
            'tailwindcss',
            'https://cdn.tailwindcss.com',
            [],
            null
        );
    }

    public function gutendex_load_textdomain()
    {
        load_plugin_textdomain(
            'gutendex-books',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );

    }
    public function gutendex_shortcode_books_list()
    {
        $api = new Gutendex_Books_API();
        $books = $api->get_books();

        $renderer = new Gutendex_Books_View_List();
        $output = $renderer->render($books);

        return $output;
    }
    public function gutendex_activate()
    {
        add_option(
            'gutendex_cache_duration',
            3600
        );
    }

}


new Gutendex_Books();
















?>