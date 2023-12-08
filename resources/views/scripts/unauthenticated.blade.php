<script>
    function addFavourite(productId) {
        Snackbar.show({
            pos: 'top-center',
            text: '{{ __('web.failed_add_to_favourites') }}   ',
            actionTextColor: '#ff0000',
            actionText: '{{ __('web.dismiss') }}'
        });
    }

    function addToCart(productId) {
        Snackbar.show({
            pos: 'top-center',
            text: '{{ __('web.failed_add_to_cart') }}   ',
            actionTextColor: '#ff0000',
            actionText: '{{ __('web.dismiss') }}'
        });


    }    function AddToCart(productId) {
        Snackbar.show({
            pos: 'top-center',
            text: '{{ __('web.failed_add_to_cart') }}   ',
            actionTextColor: '#ff0000',
            actionText: '{{ __('web.dismiss') }}'
        });
    }
     function  AddProductToCart(product_id)
     {
         Snackbar.show({
             pos: 'top-center',
             text: '{{ __('web.failed_add_to_cart') }}   ',
             actionTextColor: '#ff0000',
             actionText: '{{ __('web.dismiss') }}'
         });
    }
</script>
<script>
    function   toaster(){
        Snackbar.show({
            pos: 'top-center',
            text: '{{ __('web.create_offer') }}   ',
            actionTextColor: '#ff0000',
            actionText: '{{ __('web.dismiss') }}'
        });
    }
</script>
