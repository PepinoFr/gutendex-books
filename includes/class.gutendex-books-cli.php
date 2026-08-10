<?php

if (defined('WP_CLI') && WP_CLI) {

    class Gutendex_CLI
    {
        public function refresh()
        {
            global $wpdb;

            $wpdb->query(
                " DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_gutendex_books%' "
            );
            WP_CLI::success(
                'Cache Gutendex supprimé. Les nouvelles données seront récupérées au prochain chargement.'
            );
        }
    }


    WP_CLI::add_command(
        'gutendex',
        'Gutendex_CLI'
    );
}