(function ($) {
   'use strict';
   $.Master = function (settings) {
      const config = {
         url: '',
         surl: '',
         loginCheck: 0,
         lang: {
            button_text: 'Choose file...',
            empty_text: 'No file...',
         }
      };

      const $lf = $('#loginform');
      const $pf = $('#passform');
      const $mr = $("#mResult");

      if (settings) {
         $.extend(config, settings);
      }


      /* == Navigation Menu == */
      $('.wojo.menu').wMenu({
         breakpoint: 768,
         showArrows: true,
         arrow: '<i class="icon plus alt"></i>'
      });

      /* == Input focus == */
      $(document).on('focusout', '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on('focusin', '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      /* == Account actions  == */
      $(".add-cart").on("click", function() {
         $(".wojo.cards .card").removeClass('active');
         $(this).closest('.card').addClass('active');
         let id = $(this).data('id');
         $.post(config.url + "/controller.php", {
            action: "buy",
            id: id
         }, function(json) {
            $mr.html(json.message);
            $('html, body').animate({
               scrollTop: $mr.offset().top
            }, 2000);
         }, "json");
      });

      /* == Gateway Select == */
      $mr.on('click', '.sGateway', function() {
         let button = $(this);
         $('#mResult .sGateway').removeClass('primary');
         button.addClass('primary loading');
         let id = $(this).data('id');
         $.post(config.url + "/controller.php", {
            action: 'gateway',
            cache: false,
            id: id
         }, function(json) {
            $('#mResult #gdata').html(json.message);
            $('html,body').animate({
               scrollTop: $('#gdata').offset().top - 40
            }, 500);
            button.removeClass('loading');
         }, 'json');
      });

      /* == Activate Coupon == */
      $(document).on('click', '.activateCoupon', function () {
         const $this = $(this);
         $this.addClass('loading');
         $.post(config.url + "/controller.php", {
            action: 'activateCoupon',
         }, function (json) {
            if (json.type === 'success') {
               window.location.href = config.surl + '/dashboard/';
            }
            $this.removeClass('loading');
         }, 'json');
      });

      /* == Coupon Select == */
      /**
       * @property {string} tax
       * @property {string} gtotal
       * @property {string} disc
       * @property {string} is_full
       */
      $mr.on('click', '#cinput', function () {
         let id = $(this).data('id');
         let $this = $(this);
         let $icon = $(this).children();
         let $parent = $(this).parent();
         const $input = $('input[name=coupon]');
         const $disc = $('.disc');
         if (!$input.val()) {
            $parent.transition('shake');
         } else {
            $icon.removeClass('search').addClass('spinner circles spinning');
            $.post(config.url + '/controller.php', {
               action: 'coupon',
               id: id,
               code: $input.val()
            }, function (json) {
               if (json.type === 'success') {
                  $this.replaceWith('<a class="wojo small icon simple passive button"><i class="icon check"></i></a');
                  $input.prop('disabled', true);
                  $('.totaltax').html(json.tax);
                  $('.totalamt').html(json.gtotal);
                  $disc.html(json.disc);
                  $disc.parent().addClass('highlight');
                  if (parseInt(json.is_full) === 100) {
                     $mr.find('#activateCoupon').show();
                     $mr.find('#gateList').hide();
                  } else {
                     $mr.find('#activateCoupon').hide();
                     $mr.find('#gateList').show();
                  }
               } else {
                  $parent.transition('shake');
                  $icon.removeClass('spinner circles spinning').addClass('search');
               }
            }, 'json');
         }
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function () {
         const $button = $(this);
         const action = $(this).data('action');
         const $form = $(this).closest('form');
         const asseturl = $(this).data('url');
         const hide = $(this).data('hide');
         const reset = $(this).data('reset');

         function showResponse(json) {
            setTimeout(function () {
               $($button).removeClass('loading').prop('disabled', false);
            }, 500);
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
            if (json.type === 'success' && json.redirect) {
               setTimeout(function () {
                  $('main').transition('scaleOut', {
                     duration: 4000,
                     complete: function () {
                        window.location.href = json.redirect;
                     }
                  });
               }, 1500);
            }

            if (json.type === 'success' && hide) {
               $form.transition('fadeOut', {
                  duration: 5000,
                  complete: function () {
                     $form.hide();
                  }
               });
            }

            if (json.type === 'success' && reset) {
               $form[0].reset();
            }
         }

         function showLoader() {
            $($button).addClass('loading').prop('disabled', true);
         }

         const options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: 'post',
            url: asseturl ? config.url + '/' + asseturl + '/controller.php' : config.url + '/controller.php',
            data: {
               action: action
            },
            dataType: 'json'
         };

         $($form).ajaxForm(options).submit();
      });

      // download error messages
      if($.url().param('msg')) {
         $.wNotice({
            autoclose: 12000,
            type: 'error',
            title: 'Error',
            text: $.url().param('msg')
         });
      }

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.selPic,
         validators: {
            maxWidth: 1200,
            maxHeight: 1200
         },
         reject: function (file, errors) {
            if (errors.mimeType) {
               $.wNotice({
                  autoclose: 4000,
                  type: 'error',
                  title: 'Error',
                  text: file.name + ' must be an image.'
               });
            }
            if (errors.maxWidth || errors.maxHeight) {
               $.wNotice({
                  autoclose: 4000,
                  type: 'error',
                  title: 'Error',
                  text: file.name + ' must be width:1200px, and height:1200px  max.'
               });
            }
         },

         accept: function () {
            if ($(this).data('process')) {
               let action = $(this).data('action');
               let data = new FormData();
               data.append(action, $(this).prop('files')[0]);
               data.append('action', 'avatar');

               $.ajax({
                  type: 'POST',
                  processData: false,
                  contentType: false,
                  data: data,
                  url: config.surl + 'dashboard/action/',
                  dataType: 'json',
               });
            }
         }
      });

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
               'action': 'userLogin',
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
                  window.location.href = config.surl + '/dashboard/';
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
               'action': 'uResetPass',
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

      /* == Show/Hide password == */
      $('#showPassword').on('click', function () {
         let $icon = $(this).children();
         $icon.toggleClass('slash');
         let $input = $(this).prev('input');

         if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
         } else {
            $input.attr('type', 'password');
         }
      });

      /* == Login check == */
      if(config.loginCheck) {
         setTimeout(function() {
            $.post(config.url + "/controller.php", {
               action: "checkLogin",
            }, function(json) {
               if(json.type === "error") {
                  window.location.href = config.surl + '/logout/';
               }
            }, "json");
         }, 30 * 1000);
      }

      /* == Clear Session Debug Queries == */
      $('#debug-panel').on('click', 'a.clear_session', function () {
         $.post(config.url + '/controller.php', {
            action: 'session'
         });
         $(this).css('color', '#222');
      });

      // convert logo svg to editable
      $('.logo img').each(function () {
         let $img = $(this);
         let imgID = $img.attr('id');
         let imgClass = $img.attr('class');
         let imgURL = $img.attr('src');

         $.get(imgURL, function (data) {
            let $svg = $(data).find('svg');
            if (typeof imgID !== 'undefined') {
               $svg = $svg.attr('id', imgID);
            }
            if (typeof imgClass !== 'undefined') {
               $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }
            $svg = $svg.removeAttr('xmlns:a');
            $img.replaceWith($svg);
         }, 'xml');

      });
   };
})(jQuery);