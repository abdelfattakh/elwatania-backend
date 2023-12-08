<script>
    var del = document.getElementsByName("del");
    const add = document.getElementsByName("add")
    const val = document.getElementById(`num-${product_id}`).value
    const addcount = []
    quantity = parseInt(val);

    if (add.value == "+") {
        quantity += 1;

    } else if (del.value == "-") {
        quantity -= 1;
    }

    if (quantity <= 0) {
        quantity = 1
        document.getElementById(`num-${product_id}`).value = 1;

    }
       
</script>
