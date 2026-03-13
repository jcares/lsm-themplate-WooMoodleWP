(function($){
    $(document).ready(function(){
        var $slides = $('.hero-slide');
        var $dots = $('.hero-dot');
        var current = 0;
        var total = $slides.length;

        if (total <= 1) {
            $('.hero-dot').remove();
            $slides.addClass('active');
            return;
        }

        function setActive(index) {
            $slides.removeClass('active');
            $dots.removeClass('active');
            $slides.eq(index).addClass('active');
            $dots.eq(index).addClass('active');
            current = index;
        }

        $dots.on('click', function(){
            var index = $(this).index();
            setActive(index);
        });

        setActive(0);

        setInterval(function() {
            var next = (current + 1) % total;
            setActive(next);
        }, 6000);
    });
})(jQuery);