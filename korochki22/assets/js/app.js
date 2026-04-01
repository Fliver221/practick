document.addEventListener('DOMContentLoaded', function () {
    var navToggle = document.querySelector('[data-nav-toggle]');
    var navMenu = document.querySelector('[data-nav-menu]');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('is-open');
        });
    }

    function applyPhoneMask(input) {
        var digits = input.value.replace(/\D/g, '').slice(0, 11);

        if (digits.length === 0) {
            input.value = '';
            return;
        }

        if (digits.charAt(0) !== '8') {
            digits = '8' + digits.slice(1);
        }

        var result = '8';
        if (digits.length > 1) result += '(' + digits.slice(1, 4);
        if (digits.length >= 4) result += ')';
        if (digits.length > 4) result += digits.slice(4, 7);
        if (digits.length > 7) result += '-' + digits.slice(7, 9);
        if (digits.length > 9) result += '-' + digits.slice(9, 11);

        input.value = result;
    }

    function applyDateMask(input) {
        var digits = input.value.replace(/\D/g, '').slice(0, 8);
        var result = '';

        if (digits.length > 0) result += digits.slice(0, 2);
        if (digits.length >= 3) result += '.' + digits.slice(2, 4);
        if (digits.length >= 5) result += '.' + digits.slice(4, 8);

        input.value = result;
    }

    document.querySelectorAll('[data-mask="phone"]').forEach(function (input) {
        input.addEventListener('input', function () {
            applyPhoneMask(input);
        });
    });

    document.querySelectorAll('[data-mask="date"]').forEach(function (input) {
        input.addEventListener('input', function () {
            applyDateMask(input);
        });
    });
});
