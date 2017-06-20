(function($) {
    $(function() {
        $('body').delegate('.fl-builder-settings-fields select[name="use_pods"]', 'change', function (e) {
            var parentTab               = $(this).closest('#fl-builder-settings-tab-content');
            var sourceSelectField       = $(parentTab).find('select[name="data_source"]');
            var sourceSelectFieldParent = $(sourceSelectField).closest('tbody');

            if (e.target.value !== 'no') {
                $(sourceSelectField).val('custom_query').trigger('change');

                $(sourceSelectFieldParent).hide();
            }
            else {
                $(sourceSelectFieldParent).show();
            }
        });
    });
})(jQuery);
