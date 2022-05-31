(function ($) {
    $.fn.loadAjaxContents = function (elem) {
        if (elem == undefined) {
            elem = '.app-content';
        }

        this.bind("click", function () {
            var nav_url = $(this).attr('href');
            var _nav = $(this).parent('li');
            $(_nav).addClass('active');
            $(_nav).siblings().removeClass('active');

            if (!nav_url || nav_url == '#') {
                return true;
            }

            window.history.pushState('', '', nav_url);
            $.get(nav_url).done(function (page_content) {
                $(elem).html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
            return false;
        });
    };
})(jQuery);
