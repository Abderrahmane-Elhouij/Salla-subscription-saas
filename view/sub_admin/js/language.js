(function ($) {
   'use strict';
   $.Language = function (settings) {
      const config = {
         url: '',
      };
      if (settings) {
         $.extend(config, settings);
      }

      $('#filter').on('keyup', function () {
         let filter = $(this).val();
         $('span[data-editable=true]').each(function () {
            if ($(this).text().search(new RegExp(filter, 'i')) < 0) {
               $(this).parents('tr').fadeOut();
            } else {
               $(this).parents('tr').fadeIn();
            }
         });
      });

      $('#pgroup').on('change', function () {
         const $element = $('#pgroup option:selected');
         const sel = $element.val();
         const type = $element.data('type');
         const abbr = $(this).data('abbr');
         const $edt = $('#editable');
         $.get(config.url + '/helper.php', {
            action: 'languageSection',
            type: type,
            section: sel,
            abbr: abbr
         }, function (json) {
            $edt.html(json.html).fadeIn('slow');
            $edt.editableTableWidget();
         }, 'json');
      });
   };
})(jQuery);