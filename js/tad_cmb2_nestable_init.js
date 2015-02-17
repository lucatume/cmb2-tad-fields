(function ($, window, undefined) {
    'use strict';


    function update_value(e) {
        var list = e.length ? e : $(e.target),
            output = $('input[name="' + list.data('output') + '"]');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
        } else {
            output.val('JSON browser support required!');
        }
    };

    function init_nestable() {
        var lists = $('.dd:not(.flat-list)');
        lists.nestable();
        lists.on('change', update_value);

        $('.dd.flat-list').nestable({
            maxDepth: 1
        });
    }

    $(document).ready(function () {
        init_nestable();
        //$('body').on('cmb2_add_row cmb2_remove_row', init_nestable());
    });
})(jQuery, window);