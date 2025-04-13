(function ($) {
   'use strict';
   $.Login = function (settings) {
      const config = {
         url: 'frontview',
         surl: 'siteurl',
         lang: {
            now: 'Now',

         }
      };

      const $lf = $('#loginform');
      const $pf = $('#passform');
      if (settings) {
         $.extend(config, settings);
      }


      $('#backto').on('click', function () {
         $lf.slideDown();
         $pf.slideUp();
      });
      $('#passreset').on('click', function () {
         $lf.slideUp();
         $pf.slideDown();
      });

      $('#doSubmit').on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading').prop('disabled', true);
         const username = $('input[name=username]').val();
         const password = $('input[name=password]').val();
         $.ajax({
            type: 'post',
            url: config.url + '/controller.php',
            data: {
               'action': 'adminLogin',
               'username': username,
               'password': password
            },
            dataType: 'json',
            success: function (json) {
               if (json.type === 'error') {
                  $.wNotice({
                     autoclose: 6000,
                     type: json.type,
                     title: json.title,
                     text: json.message
                  });
               } else {
                  // Use the redirect URL from server response or fallback to default
                  window.location.href = json.redirect || (config.surl + '/admin/');
               }
               $btn.removeClass('loading').prop('disabled', false);
            }
         });
      });

      $('#dopass').on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading');
         const email = $('input[name=pEmail]').val();
         const fname = $('input[name=fname]').val();
         $.ajax({
            type: 'post',
            url: config.url + '/controller.php',
            data: {
               'action': 'aResetPass',
               'email': email,
               'fname': fname
            },
            dataType: 'json',
            success: function (json) {
               $.wNotice({
                  autoclose: 6000,
                  type: json.type,
                  title: json.title,
                  text: json.message
               });
               if (json.type === 'success') {
                  $btn.prop('disabled', true);
                  $('input[name=pEmail]').val('');
               }
               $btn.removeClass('loading');
            }
         });
      });
   };
})(jQuery);