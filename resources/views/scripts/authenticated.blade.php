<script>
    function addFavourite(productId) {
        $.ajax({
            url: '{{ route('products.favorite') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
            },
            headers: {
                "Accept-Language": "{{ app()->getLocale() }}",
                "Accept": "application/json"
            },
            beforeSend: function () {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.loading') }}   ',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
            success: function (response) {

                const src = response.body.create ?
                    '{{ asset('frontend/images/heart_on.svg?v'. config('app.version')) }}' :
                    '{{ asset('frontend/images/heart_red_dark_off.svg?v'. config('app.version')) }}';

                const elements = $('.product-fav-' + productId);

                elements.each(function () {
                    $(this).attr('src', src);
                });
                if (response.body.create) {
                    Snackbar.show({
                        pos: 'top-center',
                        text: '{{ __('web.added_to_favourites') }}   ',
                        actionTextColor: '#ff0000',
                        actionText: '{{ __('web.dismiss') }}'
                    });
                }else{
                    Snackbar.show({
                        pos: 'top-center',
                        text: '{{ __('web.remove_from_favourite') }}   ',
                        actionTextColor: '#ff0000',
                        actionText: '{{ __('web.dismiss') }}'
                    });
                }

            },
            error: function () {
                {{--window.location.href = "{{route('login')}}";--}}
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('site.failed_add_to_favourites') }}',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
        });
    }
</script>
 <script>
    function addToCart(productId, type = '+') {
        let quantity = null;
        const quantityId = 'quantity-' + productId;
        if (document.getElementById(quantityId)) {
            if (type !== '=') {
                quantity = (type === '+' ? increaseUiQu(quantityId) : decreaseUiQu(quantityId));
                if (quantity == null) return;
            } else {
                quantity = document.getElementById(quantityId).innerText;
            }
        }

        const priceId = 'final-price-' + productId;
        if (document.getElementById(priceId) && quantity !== null) {
            updatePrice(productId);
        }

        updateSummary();

        $.ajax({
            url: '{{ route('addToCart') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: productId,
                quantity: quantity || null,
            },
            headers: {
                "Accept-Language": "{{ app()->getLocale() }}",
                "Accept": "application/json"
            },
            beforeSend: function () {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.loading') }}   ',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
            success: function (response) {
                if (response.count == 0) {
                    document.getElementById('cart-count-number').style.display = 'none';
                } else {
                    document.getElementById('cart-count-number').innerText = response.count;
                    document.getElementById('cart-count-number').style.display = 'flex';
                }

                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.added_to_cart') }}   ',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
            error: function () {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.failed_add_to_cart') }}   ',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
        });
    }
</script>
<script>
    function AddToCart(product) {

        quantity = $('#num-tv').val();
        $.ajax({
            url: '{{ route('addToCart') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product.id,
                quantity: quantity,
            },
            headers: {
                "Accept-Language": "{{ app()->getLocale() }}",
                "Accept": "application/json"
            },
            success: function (response) {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.adding_to_cart') }}   ',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.adding_to_cart') }}'
                });
            },
            error: function () {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('web.failed_add_to_cart') }}',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
        });
    }

</script>
<script>
    function AddProductToCart(product_id) {

        quantity = 1
        $.ajax({
            url: '{{ route('toggleCart') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id,
                quantity: quantity,
            },
            headers: {
                "Accept-Language": "{{ app()->getLocale() }}",
                "Accept": "application/json"
            },
            success: function (response) {

                const src = response.body.create ?
                    '{{ asset('frontend/images/cart_on.svg?v'. config('app.version')) }}' :
                    '{{ asset('frontend/images/cart_red.svg?v'. config('app.version')) }}';

                const elements = $('.product-cart-' + product_id);

                elements.each(function () {
                    $(this).attr('src', src);
                });
                if (response.body.create) {
                    Snackbar.show({
                        pos: 'top-center',
                        text: '{{ __('web.add_to_cart') }}   ',
                        actionTextColor: '#ff0000',
                        actionText: '{{ __('web.dismiss') }}'
                    });
                }else{
                    Snackbar.show({
                        pos: 'top-center',
                        text: '{{ __('web.remove_from_cart') }}   ',
                        actionTextColor: '#ff0000',
                        actionText: '{{ __('web.dismiss') }}'
                    });
                }
            },
            error: function () {
                Snackbar.show({
                    pos: 'top-center',
                    text: '{{ __('site.remove_from_cart') }}',
                    actionTextColor: '#ff0000',
                    actionText: '{{ __('web.dismiss') }}'
                });
            },
        });
    }

</script>
<script>
    function failedAddingToCart() {
        Snackbar.show({
            pos: 'top-center',
            text: '{{ __('web.failed_add_to_cart') }}',
            actionTextColor: '#ff0000',
            actionText: '{{ __('web.dismiss') }}'
        });
    }
</script>
<script>
 function   toaster(){
     Snackbar.show({
         pos: 'top-center',
         text: '{{ __('web.adding_to_favourites') }}   ',
         actionTextColor: '#ff0000',
         actionText: '{{ __('web.dismiss') }}'
     });
    }
</script>

