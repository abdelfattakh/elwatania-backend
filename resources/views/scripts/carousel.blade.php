<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>

<script>
    jQuery(document).ready(function($) {
        "use strict";
        $('.owl_testimonials').each(function(index){
            $(this).owlCarousel({
                loop: false,
                center: false,
                items: 3,
                margin: 35,
                autoplay: false,
                dots: false,
                nav: true,
                autoplayTimeout: 8500,
                smartSpeed: 650,
                navText: [' <img src="/frontend/images/arrow_left.svg" alt="">',
                    '   <img src="/frontend/images/arrow_right.svg" alt=""> '
                ],
                responsive: {
                    0: {
                        items: 1.3
                    },
                    300: {
                        items: 1.5
                    },
                    525: {
                        items: 1.5
                    },
                    600: {
                        items: 1.5
                    },
                    768: {
                        items: 3
                    },
                    1200: {
                        items: 4.5
                    },

                }
            });
        });
    });
</script>

<script>
    jQuery(document).ready(function() {
        imageLoader();
    });

    $('.owl_testimonials').on('resized.owl.carousel', function() {
        imageLoader();
    })

    function imageLoader() {
        const images = document.getElementsByTagName('img');
        for (let i = 0; i < images.length; i++) {
            if (images[i].srcset.length <= 0) continue;
            let size = images[i].getBoundingClientRect().width
            if (!size) continue;
            images[i].sizes = Math.ceil(size / window.innerWidth * 100) + 'vw';
        }
    }
</script>
