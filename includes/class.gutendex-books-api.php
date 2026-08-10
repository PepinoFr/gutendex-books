<?php

class Gutendex_Books_API
{
    const API_URL = 'https://gutendex.com/books/';

    public function get_books()
    {
        $params = [];


        if (!empty($_POST['search'])) {
            $params['search'] = sanitize_text_field($_POST['search']);
        }

        if (!empty($_POST['languages'])) {
            $params['languages'] = sanitize_text_field($_POST['languages']);
        }


        $paged = isset($_POST['gutendex_page']) ? absint($_POST['gutendex_page']): 1;


        $params['page'] = $paged;


        $url = add_query_arg(
            $params,
            self::API_URL
        );
       
        $cache = get_transient('gutendex_books'.implode('_', $params));

        if ($cache !== false) {
            return json_decode($cache, true);
        }
        
        $response = wp_remote_get($url, [
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            return sprintf(
                __('Network error: %s', 'gutendex-books'),
                $response->get_error_message()
            );
        }

        $status = wp_remote_retrieve_response_code($response);

        if ($status !== 200) {
            return sprintf(
                __('HTTP error: %d', 'gutendex-books'),
                $status
            );
        }

        $body = wp_remote_retrieve_body($response);
        set_transient('gutendex_books'.implode('_', $params), $body,   get_option(Gutendex_Books_Admin::GUTENDEX_CACHE_DURATION_OPTION, 3600));
        update_option('gutendex_last_update', current_time('mysql'));

        return json_decode($body, true);
    }
}