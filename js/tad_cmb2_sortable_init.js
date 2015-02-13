(function ($, undefined) {
    'use strict';


    function init_sortable() {
        $("ol.sortable").sortable();
    }

    $(document).ready(function () {
        init_sortable();
        //$('body').on('cmb2_add_row cmb2_remove_row', init_sortable());
    });
})(jQuery);