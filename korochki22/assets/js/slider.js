document.addEventListener('DOMContentLoaded', function () {
    var sliders = document.querySelectorAll('[data-slider]');

    sliders.forEach(function (slider) {
        var slides = slider.querySelectorAll('.slider__slide');
        var prev = slider.querySelector('[data-slider-prev]');
        var next = slider.querySelector('[data-slider-next]');
        var dotsContainer = slider.querySelector('[data-slider-dots]');
        var current = 0;
        var timer = null;

        if (!slides.length) return;

        slides.forEach(function (_, index) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'slider__dot' + (index === 0 ? ' is-active' : '');
            dot.setAttribute('aria-label', 'Слайд ' + (index + 1));
            dot.addEventListener('click', function () {
                goTo(index);
                restart();
            });
            dotsContainer.appendChild(dot);
        });

        var dots = dotsContainer.querySelectorAll('.slider__dot');

        function goTo(index) {
            slides[current].classList.remove('is-active');
            dots[current].classList.remove('is-active');

            current = (index + slides.length) % slides.length;

            slides[current].classList.add('is-active');
            dots[current].classList.add('is-active');
        }

        function nextSlide() {
            goTo(current + 1);
        }

        function prevSlide() {
            goTo(current - 1);
        }

        function start() {
            timer = setInterval(nextSlide, 3000);
        }

        function restart() {
            clearInterval(timer);
            start();
        }

        if (next) {
            next.addEventListener('click', function () {
                nextSlide();
                restart();
            });
        }

        if (prev) {
            prev.addEventListener('click', function () {
                prevSlide();
                restart();
            });
        }

        start();
    });
});
