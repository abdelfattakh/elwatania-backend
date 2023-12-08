<script>
    // View More Pagination.
    function viewMorePagination(obj) {
        obj = $(obj);
        const $link = obj.data('link'); // get url to fetch data from
        const $page = obj.data('page'); // get the next page #
        const $divId = obj.data('div');
        const $div = $($divId);
        const $countDiv = $($divId + '-count');

        const url = new URL($link);
        const searchParams = url.searchParams;
        searchParams.set('page', ($page + 1));

        // append data
        $.get(url, function (response) {
            const $html = $(response).find($divId).html();
            $div.append($html);
            const $countHtml = $(response).find($divId + '-count').html();
            $countDiv.html($countHtml);
        });

        obj.data('page', (parseInt($page) + 1)); //update page #
    }
</script>

<script>
    // Updating Query Parameter OnChange for example.
    function updateQueryParameter(obj) {
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set(obj.name, obj.value);
        window.location.search = searchParams.toString();
    }
</script>

<script>
    // NavBar Toggle.
    let nav_toggle = document.querySelector('.nav_toggle');
    let nav_toggle_icon = document.querySelector('.nav_toggle ion-icon');
    let nav_menu = document.querySelector('.nav_menu');

    nav_toggle.addEventListener('click', () => {
        nav_menu.classList.toggle('active');
        nav_toggle_icon.setAttribute('name',
            nav_menu.classList.contains('active') ? 'close-outline' : 'menu-outline'
        );
    });
</script>
