<script>
    function updateCart(product_id, element) {
        let quantity = document.getElementById(`num-${product_id}`).value;
        quantity = parseInt(quantity);
        if (element.value == "+") {
            quantity += 1;

        } else if (element.value == "-") {
            quantity -= 1;
        }

        if (quantity <= 0) {
            quantity = 1
            document.getElementById(`num-${product_id}`).value = 1;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route('updateWebCart')}}',
            data: {
                product_id: product_id,
                quantity: quantity

            },

            success: function (data) {
                $('#parent').html(data.html);

            },
            error: function (error) {
                console.log(error)
            }
        });
    }
</script>
