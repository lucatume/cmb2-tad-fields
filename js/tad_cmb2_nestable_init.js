(function ($, undefined) {
    'use strict';


    function init_nestable() {
        $('.dd:not(.flat-list)').nestable();
        $('.dd.flat-list').nestable({
            maxDepth: 1
        });
    }

    $(document).ready(function () {
        init_nestable();
        //$('body').on('cmb2_add_row cmb2_remove_row', init_nestable());
    });
})
(jQuery);