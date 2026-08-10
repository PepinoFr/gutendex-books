<?php
class Gutendex_Books_Admin
{

    const GUTENDEX_CACHE_DURATION_OPTION = 'gutendex_cache_duration';
    public function __construct()
    {
        add_action(
            'admin_menu',
            [$this, 'add_menu']
        );

        add_action(
            'admin_init',
            [$this, 'gutendex_handle_actions']
        );
       
    }

   

    public function add_menu()
    {

        add_menu_page(
            __('Gutendex Books', 'gutendex-books'),
            __('Gutendex', 'gutendex-books'),
            'manage_options',
            'gutendex',
            [$this, 'render'],
            'dashicons-book'
        );

    }
    


    function render()
    {
        $last_update = get_option('gutendex_last_update');
        if (isset($_POST['cache_duration'])) {

            update_option(
                self::GUTENDEX_CACHE_DURATION_OPTION,
                absint($_POST['cache_duration'])
            );

        }

        ?>

        <div class="wrap">

            <h1><?php echo esc_html__('Gutendex Management', 'gutendex-books'); ?></h1>


            <h2><?php echo esc_html__('Cache', 'gutendex-books'); ?></h2>

            <form method="post">

                <?php wp_nonce_field('gutendex_settings'); ?>

                <label>
                    <?php echo esc_html__('Cache duration (seconds)', 'gutendex-books'); ?>
                </label>

                <input type="number" name="cache_duration" value="<?= esc_attr(
                    get_option(self::GUTENDEX_CACHE_DURATION_OPTION, 3600)
                ); ?>">

                <button class="button button-primary">
                    <?php echo esc_html__('Save', 'gutendex-books'); ?>
                </button>

            </form>

                <?php if ($last_update): ?>

                <p>
                    <?php echo esc_html__('Last fetch:', 'gutendex-books'); ?>
                    <strong>
                        <?= esc_html($last_update); ?>
                    </strong>
                </p>

            <?php else: ?>

                <p>
                    <?php echo esc_html__('No data retrieved.', 'gutendex-books'); ?>
                </p>

            <?php endif; ?>


            <form method="post">

                <?php wp_nonce_field('gutendex_action'); ?>


                <button class="button button-primary" name="refresh_books">
                    <?php echo esc_html__('Refresh data', 'gutendex-books'); ?>
                </button>


                <button class="button" name="clear_cache">
                    <?php echo esc_html__('Clear cache', 'gutendex-books'); ?>
                </button>


            </form>


        </div>

        <?php
    }



    function gutendex_handle_actions()
    {

        if (
            !isset($_POST['_wpnonce']) || !wp_verify_nonce(
                $_POST['_wpnonce'],
                'gutendex_action'
            )
        ) {
            return;
        }

        // Vider le cache
        if (isset($_POST['clear_cache']) || isset($_POST['refresh_books'])) {

            global $wpdb;

            $wpdb->query(
                " DELETE FROM {$wpdb->options}  WHERE option_name LIKE '_transient_gutendex_books_%' "
            );

            delete_option(
                'gutendex_last_update'
            );
        }


        // Forcer la récupération
        if (isset($_POST['refresh_books'])) {
            $api = new Gutendex_Books_API();
            $api->get_books();
        }

    }

}