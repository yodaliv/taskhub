"use strict";

$("#icons li").each(function () {
    $(this).append('<div class="icon-name">' + $(this).attr('class') + '</div>');
});
$(document).on('click', '#icons li', function (e) {
    $(".icon-name").fadeOut();
    $(this).find('.icon-name').fadeIn();
});
