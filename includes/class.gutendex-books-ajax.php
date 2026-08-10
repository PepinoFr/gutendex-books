<?php
class Gutendex_Books_Ajax
{

    public function __construct()
    {
        $this->register_actions();
    }

    private function register_actions() {
        add_action(
            'wp_ajax_gutendex_search',
            [$this, 'search']
        );

        add_action(
            'wp_ajax_nopriv_gutendex_search',
            [$this, 'search']
        );
    }

    public function search()
    {

        $api = new Gutendex_Books_API();

        $books = $api->get_books();


        $renderer = new Gutendex_Books_View_List();

        wp_send_json_success(
            $renderer->render_books($books)
        );
    }

}