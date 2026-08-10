<?php

class Gutendex_Books_View_List
{
    public function render($books)
    {
        $output = '';

        $output .= $this->render_form();

        $output .= '<div id="books-list">';
        $output .= $this->render_books($books);
        $output .= '</div>';

        return $output;
    }

    public function render_form()
    {
        ob_start();
        ?>
        <form id="books-search-form" class="mb-6 flex gap-4">

            <input type="hidden" name="current_url" value="<?= esc_attr(wp_unslash($_SERVER['REQUEST_URI'] ?? '')) ?>">

            <input type="text" name="search" placeholder=<?= __('Search by title...', 'gutendex-books') ?> ."
                value="<?= esc_attr($_GET['search'] ?? '') ?>" class="border p-2 rounded">

            <select name="languages" class="border p-2 rounded">

                <option value="">
                    <?= __('All languages', 'gutendex-books') ?>
                </option>

                <option value="fr" <?= selected($_GET['languages'] ?? '', 'fr', false) ?>>
                    <?= __('French', 'gutendex-books') ?>
                </option>

                <option value="en" <?= selected($_GET['languages'] ?? '', 'en', false) ?>>
                    <?= __('English', 'gutendex-books') ?>
                </option>

            </select>
            <button class="bg-blue-600 text-white px-4 rounded">
                <?= __('Search', 'gutendex-books') ?>
            </button>

        </form>
        <?php
        return ob_get_clean();
    }

    public function render_books($books)
    {

        if (!is_array($books)) {
            return '<p>' . esc_html($books) . '</p>';
        }
        $paged = isset($_POST['gutendex_page']) ? absint($_POST['gutendex_page']) : 1;


        ob_start();
        ?>

        <?php if (empty($books['results'])): ?>

            <p class="text-center text-gray-500">
                <?= __('No books found.', 'gutendex-books') ?>
            </p>

        <?php else: ?>
            <div id="books-list">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    <?php foreach ($books['results'] as $book):

                        $title = $book['title'] ?? 'Titre inconnu';

                        $authors = !empty($book['authors'])
                            ? implode(', ', array_column($book['authors'], 'name'))
                            : __('Unknown author', 'gutendex-books');

                        $languages = !empty($book['languages'])
                            ? strtoupper(implode(', ', $book['languages']))
                            : __('Unknown language', 'gutendex-books');

                        $downloads = $book['download_count'] ?? 0;

                        $cover = $book['formats']['image/jpeg'] ?? null;

                        $link = $book['formats']['text/html']
                            ?? $book['formats']['text/html; charset=utf-8']
                            ?? null;

                        ?>

                        <article class="overflow-hidden rounded-xl bg-white shadow">

                            <div class="flex h-72 items-center justify-center bg-gray-100">
                                <?php if ($cover): ?>
                                    <img src="<?= esc_url($cover) ?>" alt="<?= esc_attr($title) ?>" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <span class="text-gray-400"><?= __('No cover available', 'gutendex-books') ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col gap-3 p-5">

                                <h2 class="text-xl font-bold">
                                    <?= esc_html($title) ?>
                                </h2>

                                <p>
                                    <strong><?= __('Authors', 'gutendex-books') ?> :</strong>
                                    <?= esc_html($authors) ?>
                                </p>

                                <p>
                                    <strong><?= __('Language', 'gutendex-books') ?> :</strong>
                                    <?= esc_html($languages) ?>
                                </p>

                                <p>
                                    <strong><?= __('Downloads', 'gutendex-books') ?> :</strong>
                                    <?= number_format_i18n($downloads) ?>
                                </p>

                                <?php if ($link): ?>
                                    <a href="<?= esc_url($link) ?>" target="_blank"
                                        class="mt-auto rounded bg-blue-600 px-4 py-2 text-center text-white hover:bg-blue-700">
                                        <?= __('Read online', 'gutendex-books') ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400"><?= __('Link unavailable', 'gutendex-books') ?></span>
                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>


                </div>
                <div class="flex justify-center gap-4 mt-8">

                    <div id="books-pagination" class="flex justify-center gap-4 mt-8">

                        <?php if (!empty($books['previous'])): ?>

                            <button type="button" class="gutendex-page px-4 py-2 rounded" data-page="<?= esc_attr($paged - 1) ?>"
                                data-search="<?= esc_attr($_POST['search'] ?? '') ?>"
                                data-languages="<?= esc_attr($_POST['languages'] ?? '') ?>" onclick="gutendexPage(this)">
                                <?= __('Previous', 'gutendex-books') ?>
                            </button>

                        <?php endif; ?>


                        <?php if (!empty($books['next'])): ?>

                            <button type="button" class="gutendex-page px-4 py-2 bg-blue-600 text-white rounded"
                                data-page="<?= esc_attr($paged + 1) ?>" data-search="<?= esc_attr($_POST['search'] ?? '') ?>"
                                data-languages="<?= esc_attr($_POST['languages'] ?? '') ?>" onclick="gutendexPage(this)">
                                <?= __('Next', 'gutendex-books') ?>
                            </button>

                        <?php endif; ?>
                    </div>



                </div>
            </div>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }

}
