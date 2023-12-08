<script>
    function AddCoupon(element) {
        let coupon_code = $('#coupon_code').val();
        let apply = document.getElementById("applybutton").innerHTML
        console.log(apply)

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route('couponCheck')}}',
            data: {
                coupon_code: coupon_code,
                type: apply

            },

            success: function (data) {
                if (apply == 'Apply') {
                    apply.innerHTML = 'remove';
                } else if (apply == 'remove') {
                    apply.innerHTML = 'Apply';
                }
                $('#parent').html(data.html);

            },
            error: function (error) {
                console.log(error)
                $("#result").html(error.responseJSON.message);

            }
        });

    }
</script>

