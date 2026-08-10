document.addEventListener('DOMContentLoaded', () => {




    window.gutendexPage = async function (button) {

        const formData = new FormData();

        formData.append('action', 'gutendex_search');
        formData.append('gutendex_page', button.dataset.page);
        formData.append('search', button.dataset.search);
        formData.append('languages', button.dataset.languages);

        const booksList = document.querySelector('#books-list');


        booksList.innerHTML = `
            <div class="flex justify-center items-center py-10">
                <p class="text-lg font-semibold">
                    ${gutendex_ajax.loading_text}
                </p>
            </div>
        `;

        try {
            const response = await fetch(
                gutendex_ajax.ajax_url,
                {
                    method: 'POST',
                    body: formData
                }
            );


            const result = await response.json();
            if (result.success) {
                booksList.outerHTML = result.data;
            }
        } catch (error) {
            booksList.innerHTML = `
                <div class="text-red-600 text-center py-10">
                    ${gutendex_ajax.error_text}
                </div>
            `;
        }
    };


    const form = document.querySelector('#books-search-form');

    if (!form) return;

    form.addEventListener('submit', async (e) => {

        e.preventDefault();


        const formData = new FormData(form);

        formData.append('action', 'gutendex_search');
        const booksList = document.querySelector('#books-list');


        booksList.innerHTML = `
            <div class="flex justify-center items-center py-10">
                <p class="text-lg font-semibold">
                    ${gutendex_ajax.loading_text}
                </p>
            </div>
        `;

        try {
            const response = await fetch(
                gutendex_ajax.ajax_url,
                {
                    method: 'POST',
                    body: formData
                }
            );


            const result = await response.json();


            if (result.success) {
                booksList.outerHTML = result.data;
            }
        } catch (error) {
            booksList.innerHTML = `
                <div class="text-red-600 text-center py-10">
                    ${gutendex_ajax.error_text}
                </div>
            `;
        }

    });



});