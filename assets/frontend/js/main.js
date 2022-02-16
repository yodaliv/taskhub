// Text animation
"use strict";
var spanText = function spanText(text) {
    var string = text.innerText;
    var spaned = '';
    for (var i = 0; i < string.length; i++) {
        if (string.substring(i, i + 1) === ' ') spaned += string.substring(i, i + 1);
        else spaned += '<span>' + string.substring(i, i + 1) + '</span>';
    }
    text.innerHTML = spaned;
}

var headline = document.querySelector("h2");

spanText(headline);

let animations = document.querySelectorAll('.animation');

animations.forEach(animation => {
    let letters = animation.querySelectorAll('span');
    letters.forEach((letter, i) => {
        letter.style.animationDelay = (i * 0.04) + 's';
    })
})


// for navbar
$(window).on("scroll", function () {
    "use strict";
    if ($(window).scrollTop() > 50) {
        $(".header").addClass("active");
    } else {
        //remove the background property so it comes transparent again (defined in your css)
        $(".header").removeClass("active");
    }
});


// form validation

// Disable form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();



// swiper slider
"use strict";
var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 10,
    // init: false,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        '@0.00': {
            slidesPerView: 1,
            spaceBetween: 10,
        },
        '@0.75': {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        '@1.00': {
            slidesPerView: 3,
            spaceBetween: 40,
        },
        '@1.50': {
            slidesPerView: 4,
            spaceBetween: 50,
        },
    }
});


// mobile nav
"use strict";
$('.js-menu').on('click', () => {
    $('.js-menu').toggleClass('active');
    $('.js-nav').toggleClass('open');
    $('.js-nav__list').toggleClass('show');
});


// navigation active class

$(function () {
    var current = location.pathname.substring(23);
    // alert(current);
    $('.navbar-nav li a').each(function () {
        var $this = $(this);
        // if the current path is like this link, make it active
        if ($this.attr('href').indexOf(current) !== -1 && current != '') {
            $this.closest('.nav-link').addClass('active');        
        }else if(current == ''){
            $('.home').addClass('active');
        }
    });
});
