(function ($, undefined) {
    'use strict';

    function init_select2() {
        $('.select2').siblings('span.select2-container').remove();
        $('.select2').select2();
    }

    $(document).ready(function () {
        init_select2();
        $('body').on('cmb2_add_row cmb2_remove_row', init_select2);
    });
})(jQuery);