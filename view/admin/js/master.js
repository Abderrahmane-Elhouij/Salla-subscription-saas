(function ($) {
   'use strict';
   $.Master = function (settings) {
      const config = {
         weekstart: 0,
         ampm: 0,
         url: '',
         aurl: '',
         surl: '',
         editorCss: '',
         lang: {
            button_text: 'Choose file...',
            empty_text: 'No file...',
            monthsFull: '',
            monthsShort: '',
            weeksFull: '',
            weeksShort: '',
            weeksMed: '',
            weeksSmall:'',
            dateFormat: 'mm/dd/yyyy',
            today: 'Today',
            now: 'Now',
            selPic: 'Select Picture',
            delBtn: 'Delete Record',
            trsBtn: 'Move to Trash',
            arcBtn: 'Move to Archive',
            uarcBtn: 'Restore From Archive',
            restBtn: 'Restore Item',
            canBtn: 'Cancel',
            clsBtn: 'Close',
            clear: 'Clear',
            selProject: 'Select Project',
            delMsg1: 'Are you sure you want to delete this record?',
            delMsg2: 'This action cannot be undone!!!',
            delMsg3: 'Trash',
            delMsg5: 'Move [NAME] to the archive?',
            delMsg6: 'Remove [NAME] from the archive?',
            delMsg7: 'Restore [NAME]?',
            delMsg8: 'The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.',
            delMsg9: 'This action will restore item to it\'s original sate',
            working: 'working...'
         }
      };
      const $fromdate = $('#fromdate');
      const $enddate = $('#enddate');

      if (settings) {
         $.extend(config, settings);
      }

      /* == Navigation Menu == */
      $('.wojo.menu').wMenu({
         breakpoint: 959,
         showArrows: true,
         arrow: '<i class="icon plus alt"></i>'
      });

      /* == Transitions == */
      $(document).on('click', '[data-slide="true"]', function () {
         const trigger = $(this).data('trigger');
         $(trigger).slideToggle(100);
      });

      /* == Input focus == */
      $(document).on('focusout', '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on('focusin', '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      /* == Range Slider == */
      $('input[type="range"]').wRange();

      /* == Tabs == */
      $('.wojo.tabs').wTabs();

      /* == Input Tags == */
      $('.wojo.tags').wTags();

      /* == Progress Bars == */
      $('.wojo.progress').wProgress();

      /* == Accordion == */
      $('.wojo.accordion').wAccordion();

      /* == Dimmable content == */
      $(document).on('change', '.is_dimmable', function () {
         const dataset = $(this).data('set');
         const status = $('input[type=checkbox]', this).is(':checked') ? 1 : 0;
         const result = $.extend(true, dataset.option[0], {
            'active': status
         });
         $.post(config.url + '/helper.php', result);
         $(dataset.parent).toggleClass('active');
         $(this).closest('.card').toggleClass('dimmed');
      });

      /* == Datepicker == */
      $('.datepick').wDate({
         months: config.lang.monthsFull,
         short_months: config.lang.monthsShort,
         days_of_week: config.lang.weeksFull,
         short_days: config.lang.weeksShort,
         days_min: config.lang.weeksSmall,
         selected_format: 'DD, mmmm d',
         month_head_format: 'mmmm yyyy',
         format: config.lang.dateFormat,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      }).on('datechanged', function (event) {
         if ($(this).attr('data-element')) {
            const element = $(this).data('element');
            const parent = $(this).data('parent');

            const date = new Date(event.date);
            const day = date.getDate();
            const month = config.lang.monthsFull[date.getMonth()];
            const year = date.getFullYear();
            const formatted = month + ' ' + day + ', ' + year;

            $(parent).html(formatted);
            if ($(element).is(':input')) {
               $(element).val(event.date).trigger('change');
            } else {
               $(element).html(formatted);
            }
         }
      });

      $('.timepick').wTime({
         timeFormat: 'hh:mm:ss.000', // format of the time value (data-time attribute)
         format: 'hh:mm t', // format of the input value
         is24: true, // format 24 hour header
         readOnly: true, // determines if input is readonly
         hourPadding: true, // determines if display value has zero padding for hour value less than 10 (i.e. 05:30 PM); 24-hour format has padding by default
         btnNow: config.lang.now,
         btnOk: config.lang.ok,
         btnCancel: config.lang.canBtn,
      });

      /* == From/To date range == */
      $fromdate.wDate({
         rangeTo: $enddate,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });
      $enddate.wDate({
         rangeFrom: $fromdate,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });

      /* == Inline Edit == */
      $('#editable, .wedit').on('validate', '[data-editable]', function (e, val) {
         if (val === '') {
            return false;
         }

      }).on('change', '[data-editable]', function (e, val) {
         const dataset = $(this).data('set');
         const $this = $(this);
         const result = $.extend(true, dataset, {
            title: val,
         });

         $.ajax({
            type: 'POST',
            url: (dataset.url) ? dataset.url : config.url + "/helper.php",
            dataType: 'json',
            data: result,
            beforeSend: function () {
               $this.animate({
                  opacity: 0.2
               }, 800);
            },
            success: function (json) {
               $this.animate({
                  opacity: 1
               }, 800);
               setTimeout(function () {
                  $this.html(json.title).fadeIn('slow');
               }, 1000);
            }
         });
      }).editableTableWidget();

      /* == Editor == */
      $('.bodypost').redactor({
         replaceTags: {
            'b': 'strong',
            'strike': 'del'
         },
         structure: true,
         minHeight: '500px',
         maxHeight: '800px',
         plugins: ['alignment', 'fontcolor', 'fullscreen'],
         imageFigure: false,
         imageCaption: false,
      });

      $('.altpost').redactor({
         replaceTags: {
            'b': 'strong',
            'strike': 'del'
         },
         buttons: ['bold', 'italic', 'lists'],
         structure: true,
         minHeight: '200px',
         maxHeight: '400px',
         plugins: ['alignment', 'fontcolor', 'fullscreen'],
         imageFigure: false,
         imageCaption: false,
      });

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.selPic,
         validators: {
            maxWidth: 3200,
            maxHeight: 1800
         },
         reject: function(file, errors) {
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
                  text: file.name + ' must be width:3200px, and height:1800px  max.'
               });
            }
         }
      });

      /* == Clear Session Debug Queries == */
      $("#debug-panel").on('click', 'a.clear_session', function () {
         $.post(config.url + '/helper.php', {
            iaction: "session"
         });
         $(this).css('color', '#222');
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function() {
         let $button = $(this);
         let action = $(this).data('action');
         let $form = $(this).closest("form");
         let asseturl = $(this).data('url');

         function showResponse(json) {
            setTimeout(function() {
               $($button).removeClass("loading").prop("disabled", false);
            }, 500);
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
            if (json.type === "success" && json.redirect) {
               $('main').transition("scaleOut", {
                  duration: 1000,
                  complete: function() {
                     window.location.href = json.redirect;
                  }
               });
            }
         }

         function showLoader() {
            $($button).addClass("loading").prop("disabled", true);
         }
         let options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: "post",
            url: asseturl ? config.url + "/" + asseturl + "/controller.php" : config.url + "/controller.php",
            data: {
               action: action
            },
            dataType: 'json'
         };

         $($form).ajaxForm(options).submit();
      });

      /* == Simple Actions == */
      $(document).on('click', '.iaction', function() {
         let dataset = $(this).data("set");
         let $parent = $(dataset.parent);
         $.ajax({
            type: 'POST',
            url: config.url + dataset.url,
            dataType: 'json',
            data: dataset.option[0]
         }).done(function(json) {
            if (json.type === "success") {
               switch (dataset.complete) {
                  case "remove":
                     $parent.transition("scaleOut", {
                        duration: 300,
                        complete: function() {
                           $parent.remove();
                        }
                     });

                     break;

                  case "replace":
                     $parent.html(json.html).transition('fadeIn', {
                        duration: 600
                     });
                     break;

                  case "prepend":
                     $parent.prepend(json.html).transition('fadeIn', {
                        duration: 600
                     });
                     break;
               }

               if (dataset.redirect) {
                  setTimeout(function() {
                     $("main").transition('scaleOut');
                     window.location.href = dataset.redirect;
                  }, 800);
               }
            }

            if (json.message) {
               $.wNotice({
                  autoclose: 12000,
                  type: json.type,
                  title: json.title,
                  text: json.message
               });
            }

         });
      });

      /* == Add/Edit Modal Actions == */
      /**
       * @property {string} modalclass
       */
      $(document).on('click', 'a.action, button.action', function() {
         let dataset = $(this).data("set");
         let $parent = dataset.parent;
         let $this = $(this);
         let actions = '';
         let asseturl = dataset.url;
         //var closeb = dataset.buttons === false ? '<div class="header"><h5>Modal Header</h5> </div>' : '';
         let url = asseturl ? config.url + "/" + asseturl : config.url + "/controller.php";

         $.get(url, dataset.option[0], function(data) {
            if (dataset.buttons !== false) {
               actions += '' +
                 '<div class="footer">' +
                 '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
                 '<button type="button" class="wojo small positive button" data="modal:ok">' + dataset.label + '</button>' +
                 '</div>';
            }

            let $wmodal = $('<div class="wojo ' + dataset.modalclass + ' modal"><div class="dialog" role="document"><div class="content">' +
              '' + data + '' +
              '' + actions + '' +
              '</div></div></div>').on($.modal.BEFORE_OPEN, function() {
               $('.datepick', this).wDate({
                  months: config.lang.monthsFull,
                  short_months: config.lang.monthsShort,
                  days_of_week: config.lang.weeksFull,
                  short_days: config.lang.weeksShort,
                  days_min: config.lang.weeksSmall,
                  selected_format: 'DD, mmmm d',
                  month_head_format: 'mmmm yyyy',
                  format: 'mm/dd/yyyy',
                  clearBtn: true,
                  todayBtn: true,
                  cancelBtn: true,
                  clearBtnLabel: config.lang.clear,
                  cancelBtnLabel: config.lang.canBtn,
                  okBtnLabel: config.lang.ok,
                  todayBtnLabel: config.lang.today,
               }).on('datechanged', function(event) {
                  if ($(this).attr("data-element")) {
                     let element = $(this).data('element');
                     let parent = $(this).data('parent');

                     let date = new Date(event.date);
                     let day = date.getDate();
                     let month = config.lang.monthsFull[date.getMonth()];
                     let year = date.getFullYear();
                     let formatted = month + ' ' + day + ', ' + year;

                     $(parent).html(formatted);
                     if ($(element).is(":input")) {
                        $(element).val(event.date).trigger('change');
                     } else {
                        $(element).html(formatted);
                     }
                  }
               });
            }).modal().on('click', '[data="modal:ok"]', function() {
               $(this).addClass('loading').prop("disabled", true);
               function showResponse(json) {
                  setTimeout(function() {
                     $('[data="modal:ok"]', $wmodal).removeClass('loading').prop("disabled", false);
                     if (json.message) {
                        $.wNotice({
                           autoclose: 12000,
                           type: json.type,
                           title: json.title,
                           text: json.message
                        });
                     }
                     if (json.type === "success") {
                        if (dataset.redirect) {
                           setTimeout(function() {
                              $("main").transition('scaleOut');
                              window.location.href = json.redirect;
                           }, 800);
                        } else {
                           switch (dataset.complete) {
                              case "replace":
                                 $($parent).html(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "replaceWith":
                                 $($this).replaceWith(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "append":
                                 $($parent).append(json.html).transition('scaleIn', {
                                    duration: 300
                                 });
                                 break;
                              case "prepend":
                                 $($parent).prepend(json.html).transition('scaleIn', {
                                    duration: 300
                                 });
                                 break;
                              case "update":
                                 $($parent).replaceWith(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "insert":
                                 if (dataset.mode === "append") {
                                    $($parent).append(json.html);
                                 }
                                 if (dataset.mode === "prepend") {
                                    $($parent).prepend(json.html);
                                 }
                                 break;
                              case "highlite":
                                 $($parent).addClass('highlite');
                                 break;

                              default:
                                 break;
                           }
                           $.modal.close();
                        }
                     }

                  }, 500);
               }

               let options = {
                  target: null,
                  success: showResponse,
                  type: "post",
                  url: url,
                  data: dataset.option[0],
                  dataType: 'json'
               };
               $('#modal_form').ajaxForm(options).submit();
            });
         });
      });

      /* == Modal Delete/Archive/Trash Actions == */
      /**
       * @property {string} subtext
       */
      $(document).on('click', 'a.data', function() {
         let dataset = $(this).data("set");
         let $parent = $(this).closest(dataset.parent);
         let asseturl = dataset.url;
         let url = asseturl ? config.url + "/" + asseturl : config.url + "/controller.php";
         let subtext = dataset.subtext;
         let children = dataset.children ? dataset.children[0] : null;
         //let complete = dataset.complete;
         let header;
         let content;
         //let icon;
         let btnLabel;

         switch (dataset.action) {
            case 'trash':
               btnLabel = config.lang.trsBtn;
               subtext = config.lang.delMsg8;
               header = config.lang.delMsg3 + ' <span class="text-color-secondary">' + dataset.option[0].title + '?</span>';
               content = '<img src="' + config.url + '/images/trash.svg" class="wojo center badge image" alt="">';
               break;

            case 'archive':
               btnLabel = config.lang.arcBtn;
               header = config.lang.delMsg5.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/archive.svg" class="wojo center badge image" alt="">';
               break;

            case 'unarchive':
               btnLabel = config.lang.uarcBtn;
               header = config.lang.delMsg6.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/unarchive.svg" class="wojo center badge image" alt="">';
               break;

            case 'restore':
               btnLabel = config.lang.restBtn;
               subtext = config.lang.delMsg9;
               header = config.lang.delMsg7.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/restore.svg" class="wojo center badge image" alt="">';
               break;

            case 'delete':
               btnLabel = config.lang.delBtn;
               subtext = config.lang.delMsg2;
               header = config.lang.delMsg1;
               content = '<img src="' + config.url + '/images/delete.svg" class="wojo center badge image" alt="">';
               break;
         }
         $('<div class="wojo modal"><div class="dialog" role="document"><div class="content">' +
           '<div class="header"><h5>' + header + '</h5></div>' +
           '<div class="body center-align">' + content + '<div class="wojo info icon inverted dashed message margin-top compact center-align"><i class="icon information square"></i>' + subtext + '</div></div>' +
           '<div class="footer">' +
           '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
           '<button type="button" class="wojo small positive inverted button" data="modal:ok">' + btnLabel + '</button>' +
           '</div></div></div></div>').modal().on('click', '[data="modal:ok"]', function () {
            $(this).addClass('loading').prop('disabled', true);
            $.ajax({
               type: 'POST',
               url: url,
               dataType: 'json',
               data: dataset.option[0]
            }).done(function(json) {
               if (json.type === "success") {
                  if (dataset.redirect) {
                     $.modal.close();
                     $.wNotice({
                        autoclose: 4000,
                        type: json.type,
                        title: json.title,
                        text: json.message
                     });
                     $("main").transition("scaleOut", {
                        duration: 4000,
                        complete: function() {
                           window.location.href = dataset.redirect;
                        }
                     });
                  } else {
                     $($parent).transition("scaleOut", {
                        duration: 300,
                        complete: function() {
                           $($parent).remove();
                        }
                     });
                     if (children) {
                        $.each(children, function(i, values) {
                           $.each(values, function(k, v) {
                              if (v === "html") {
                                 $(i).html(json[k]);
                              } else {
                                 $(i).val(json[k]);
                              }
                           });
                        });
                     }
                     $('.wojo.modal').find('.badge.image').attr('src', config.url + '/images/checkmark.svg').transition('scaleIn', {
                        duration: 500,
                        complete: function() {
                           $.modal.close();
                           $.wNotice({
                              autoclose: 6000,
                              type: json.type,
                              title: json.title,
                              text: json.message
                           });
                        }
                     });
                  }
               }
            });
         });
      });
   };
})(jQuery);