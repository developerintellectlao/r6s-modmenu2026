
; (function ($, window, document, undefined) {
  'use strict';

  //
  // Constants
  //
  var SP_WPCF = SP_WPCF || {};

  SP_WPCF.funcs = {};

  SP_WPCF.vars = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $('body').hasClass('rtl'),
  };

  //
  // Helper Functions
  //
  SP_WPCF.helper = {

    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || '') + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + '').replace(/(\[|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {

      var checks = [];
      var regex = new RegExp(SP_WPCF.helper.preg_quote(field_id + '[\\d+]'), 'g');

      $selector.find(':radio').each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this).find(':input').each(function () {
          this.name = this.name.replace(regex, field_id + '[' + index + ']');
          if (this.orginal_checked) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function (callback, threshold, immediate) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = (immediate && !timeout);
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },

    //
    // Get a cookie
    //
    get_cookie: function (name) {
      var e

      var b

      var cookie = document.cookie

      var p = name + '='

      if (!cookie) {
        return
      }

      b = cookie.indexOf('; ' + p)

      if (b === -1) {
        b = cookie.indexOf(p)

        if (b !== 0) {
          return null
        }
      } else {
        b += 2
      }

      e = cookie.indexOf(';', b)

      if (e === -1) {
        e = cookie.length
      }

      return decodeURIComponent(cookie.substring(b + p.length, e))
    },

    //
    // Set a cookie
    //
    set_cookie: function (name, value, expires, path, domain, secure) {
      var d = new Date()

      if (typeof expires === 'object' && expires.toGMTString) {
        expires = expires.toGMTString()
      } else if (parseInt(expires, 10)) {
        d.setTime(d.getTime() + parseInt(expires, 10) * 1000)
        expires = d.toGMTString()
      } else {
        expires = ''
      }

      document.cookie =
        name +
        '=' +
        encodeURIComponent(value) +
        (expires ? '; expires=' + expires : '') +
        (path ? '; path=' + path : '') +
        (domain ? '; domain=' + domain : '') +
        (secure ? '; secure' : '')
    },


  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.wpcf_clone = function () {

    var base = $.fn.clone.apply(this, arguments),
      clone = this.find('select').add(this.filter('select')),
      cloned = base.find('select').add(base.filter('select'));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {

        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each(function () {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.wpcf_expand_all = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        e.preventDefault();
        $('.wpcf-wrapper').toggleClass('wpcf-show-all');
        $('.wpcf-section').wpcf_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.wpcf_nav_options = function () {
    return this.each(function () {

      var $nav = $(this),
        $window = $(window),
        $wpwrap = $('#wpwrap'),
        $links = $nav.find('a'),
        $last;

      $window.on('hashchange wpcf.hashchange', function () {

        var hash = window.location.hash.replace('#tab=', '');
        var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
        var $link = $('[data-tab-id="' + slug + '"]');

        if ($link.length) {

          $link.closest('.wpcf-tab-item').addClass('wpcf-tab-expanded').siblings().removeClass('wpcf-tab-expanded');

          if ($link.next().is('ul')) {

            $link = $link.next().find('li').first().find('a');
            slug = $link.data('tab-id');

          }

          $links.removeClass('wpcf-active');
          $link.addClass('wpcf-active');

          if ($last) {
            $last.addClass('hidden');
          }

          var $section = $('[data-section-id="' + slug + '"]');

          $section.removeClass('hidden');
          $section.wpcf_reload_script();

          $('.wpcf-section-id').val($section.index() + 1);

          $last = $section;

          if ($wpwrap.hasClass('wp-responsive-open')) {
            $('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
            $wpwrap.removeClass('wp-responsive-open');
          }

        }

      }).trigger('wpcf.hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  // $.fn.wpcf_nav_metabox = function () {
  //   return this.each(function () {

  //     var $nav = $(this),
  //       $links = $nav.find('a'),
  //       $sections = $nav.parent().find('.wpcf-section'),
  //       $last;

  //     $links.each(function (index) {

  //       $(this).on('click', function (e) {

  //         e.preventDefault();

  //         var $link = $(this);

  //         $links.removeClass('wpcf-active');
  //         $link.addClass('wpcf-active');

  //         if ($last !== undefined) {
  //           $last.addClass('hidden');
  //         }

  //         var $section = $sections.eq(index);

  //         $section.removeClass('hidden');
  //         $section.wpcf_reload_script();

  //         $last = $section;

  //       });

  //     });

  //     $links.first().trigger('click');

  //   });
  // };
  //
  // Metabox Tabs
  //
  $.fn.wpcf_nav_metabox = function () {
    return this.each(function () {
      var $nav = $(this)

      var $links = $nav.find('a')

      var unique_id = $nav.data('unique')

      var post_id = $('#post_ID').val() || 'global'

      var $last_section

      var $last_link

      $links.on('click', function (e) {
        e.preventDefault()

        var $link = $(this)

        var section_id = $link.data('section')

        if ($last_link !== undefined) {
          $last_link.removeClass('wpcf-active')
        }

        if ($last_section !== undefined) {
          $last_section.addClass('hidden');
        }

        $link.addClass('wpcf-active')

        var $section = $('#wpcf-section-' + section_id)
        $section.removeClass('hidden');
        $section.wpcf_reload_script()

        SP_WPCF.helper.set_cookie(
          'wpcf-last-metabox-tab-' + post_id + '-' + unique_id,
          section_id
        )

        $last_section = $section
        $last_link = $link
      })

      var get_cookie = SP_WPCF.helper.get_cookie(
        'wpcf-last-metabox-tab-' + post_id + '-' + unique_id
      )

      if (get_cookie) {
        $nav.find('a[data-section="' + get_cookie + '"]').trigger('click')
      } else {
        $links.first('a').trigger('click')
      }
    })
  }
  //
  // Metabox Page Templates Listener
  //
  $.fn.wpcf_page_templates = function () {
    if (this.length) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template', function () {

        var maybe_value = $(this).val() || 'default';

        $('.wpcf-page-templates').removeClass('wpcf-metabox-show').addClass('wpcf-metabox-hide');
        $('.wpcf-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('wpcf-metabox-hide').addClass('wpcf-metabox-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.wpcf_post_formats = function () {
    if (this.length) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

        $('.wpcf-post-formats').removeClass('wpcf-metabox-show').addClass('wpcf-metabox-hide');
        $('.wpcf-post-format-' + maybe_value).removeClass('wpcf-metabox-hide').addClass('wpcf-metabox-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.wpcf_search = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input');

      $input.on('change keyup', function () {

        var value = $(this).val(),
          $wrapper = $('.wpcf-wrapper'),
          $section = $wrapper.find('.wpcf-section'),
          $fields = $section.find('> .wpcf-field:not(.wpcf-depend-on)'),
          $titles = $fields.find('> .wpcf-title, .wpcf-search-tags');

        if (value.length > 3) {

          $fields.addClass('wpcf-metabox-hide');
          $wrapper.addClass('wpcf-search-all');

          $titles.each(function () {

            var $title = $(this);

            if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

              var $field = $title.closest('.wpcf-field');

              $field.removeClass('wpcf-metabox-hide');
              $field.parent().wpcf_reload_script();

            }

          });

        } else {

          $fields.removeClass('wpcf-metabox-hide');
          $wrapper.removeClass('wpcf-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.wpcf_sticky = function () {
    return this.each(function () {

      var $this = $(this),
        $window = $(window),
        $inner = $this.find('.wpcf-header-inner'),
        padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {

          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = $window.innerWidth();

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({ width: $this.outerWidth() - padding });
            $this.css({ height: $this.outerHeight() }).addClass('wpcf-sticky');
          } else {
            $inner.removeAttr('style');
            $this.removeAttr('style').removeClass('wpcf-sticky');
          }

        },
        requestTick = function () {

          if (!ticking) {
            requestAnimationFrame(function () {
              stickyUpdate();
              ticking = false;
            });
          }

          ticking = true;

        },
        onSticky = function () {

          scrollTop = $window.scrollTop();
          requestTick();

        };

      $window.on('scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.wpcf_dependency = function () {
    return this.each(function () {

      var $this = $(this),
        $fields = $this.children('[data-controller]');

      if ($fields.length) {

        var normal_ruleset = $.wpcf_deps.createRuleset(),
          global_ruleset = $.wpcf_deps.createRuleset(),
          normal_depends = [],
          global_depends = [];

        $fields.each(function () {

          var $field = $(this),
            controllers = $field.data('controller').split('|'),
            conditions = $field.data('condition').split('|'),
            values = $field.data('value').toString().split('|'),
            is_global = $field.data('depend-global') ? true : false,
            ruleset = (is_global) ? global_ruleset : normal_ruleset;

          $.each(controllers, function (index, depend_id) {

            var value = values[index] || '',
              condition = conditions[index] || conditions[0];

            ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

            ruleset.include($field);

            if (is_global) {
              global_depends.push(depend_id);
            } else {
              normal_depends.push(depend_id);
            }

          });

        });

        if (normal_depends.length) {
          $.wpcf_deps.enable($this, normal_ruleset, normal_depends);
        }

        if (global_depends.length) {
          $.wpcf_deps.enable(SP_WPCF.vars.$body, global_ruleset, global_depends);
        }

      }

    });
  };


  //
  // Field: group
  //
  $.fn.wpcf_field_group = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.wpcf-fieldset'),
        $group = $fieldset.length ? $fieldset : $this,
        $wrapper = $group.children('.wpcf-cloneable-wrapper'),
        $hidden = $group.children('.wpcf-cloneable-hidden'),
        $max = $group.children('.wpcf-cloneable-max'),
        $min = $group.children('.wpcf-cloneable-min'),
        field_id = $wrapper.data('field-id'),
        is_number = Boolean(Number($wrapper.data('title-number'))),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));


      // clear accordion arrows if multi-instance
      if ($wrapper.hasClass('ui-accordion')) {
        $wrapper.find('.ui-accordion-header-icon').remove();
      }

      var update_title_numbers = function ($selector) {
        $selector.find('.wpcf-cloneable-title-number').each(function (index) {
          $(this).html(($(this).closest('.wpcf-cloneable-item').index() + 1) + '.');
        });
      };

      $wrapper.accordion({
        header: '> .wpcf-cloneable-item > .wpcf-cloneable-title',
        collapsible: true,
        active: false,
        animate: false,
        heightStyle: 'content',
        beforeActivate: function (event, ui) {
          var $panel = ui.newPanel;
          var $header = ui.newHeader;
          if ($panel.length && !$panel.data('opened')) {
            var $fields = $panel.children();
            var $first = $fields.first();
            var $title = $header.find('.wpcf-cloneable-value');
            $first.on('change keyup', function (event) {
              setTimeout(function () {
                var $group_title = $first.find('.wpcf--active .sp-carousel-type');
                $title.text($group_title.html());
              }, 300);
            });

          }
        },
        icons: {
          'header': 'wpcf-cloneable-header-icon fa fa-angle-right',
          'activeHeader': 'wpcf-cloneable-header-icon fa fa-angle-down'
        },
        activate: function (event, ui) {

          var $panel = ui.newPanel;
          var $header = ui.newHeader;

          if ($panel.length && !$panel.data('opened')) {
            var $fields = $panel.children();
            var $first = $fields.first();
            var $title = $header.find('.wpcf-cloneable-value');
            $first.on('change keyup', function (event) {
              setTimeout(function () {
                var $group_title = $first.find('.wpcf--active .sp-carousel-type');
                $title.text($group_title.html());
              }, 300);
            });
            $panel.wpcf_reload_script();
            $panel.data('opened', true);
            $panel.data('retry', false);
          } else if ($panel.data('retry')) {
            $panel.wpcf_reload_script_retry();
            $panel.data('retry', false);
          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.wpcf-cloneable-title,.wpcf-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function (event, ui) {
          $wrapper.accordion({ active: false });
          $wrapper.sortable('refreshPositions');
          ui.item.children('.wpcf-cloneable-content').data('retry', true);
        },
        update: function (event, ui) {

          SP_WPCP_Framework.helper.name_nested_replace($wrapper.children('.wpcf-cloneable-item'), field_id);
          //  $wrapper.sp_wpcp_customizer_refresh();

          if (is_number) {
            update_title_numbers($wrapper);
          }

        },
      });
      $group.children('.wpcf-cloneable-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wpcf-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $cloned_item = $hidden.wpcf_clone(true);

        $cloned_item.removeClass('wpcf-cloneable-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({ active: count });
        // $wrapper.sp_wpcp_customizer_refresh();
        // $wrapper.sp_wpcp_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wpcf-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent(),
          $cloned_helper = $parent.children('.wpcf-cloneable-helper').wpcf_clone(true),
          $cloned_title = $parent.children('.wpcf-cloneable-title').wpcf_clone(),
          $cloned_content = $parent.children('.wpcf-cloneable-content').wpcf_clone(),
          $cloned_item = $('<div class="wpcf-cloneable-item" />');

        $cloned_item.append($cloned_helper);
        $cloned_item.append($cloned_title);
        $cloned_item.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned_item);

        SP_WPCF.helper.name_nested_replace($wrapper.children('.wpcf-cloneable-item'), field_id);

        $wrapper.accordion('refresh');
        //  $wrapper.sp_wpcp_customizer_refresh();
        // $wrapper.sp_wpcp_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wpcf-cloneable-item').children('.wpcf-cloneable-helper').on('click', '.wpcf-cloneable-clone', event_clone);
      $group.children('.wpcf-cloneable-hidden').children('.wpcf-cloneable-helper').on('click', '.wpcf-cloneable-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wpcf-cloneable-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.wpcf-cloneable-item').remove();

        SP_WPCP_Framework.helper.name_nested_replace($wrapper.children('.wpcf-cloneable-item'), field_id);

        // $wrapper.sp_wpcp_customizer_refresh();

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wpcf-cloneable-item').children('.wpcf-cloneable-helper').on('click', '.wpcf-cloneable-remove', event_remove);
      $group.children('.wpcf-cloneable-hidden').children('.wpcf-cloneable-helper').on('click', '.wpcf-cloneable-remove', event_remove);

      setTimeout(function () {
        $group.find('.wpcf-cloneable-item:not(.wpcf-cloneable-hidden)').each(function () {
          var $header = $(this).find('.wpcf-cloneable-title');
          var $title = $header.find('.wpcf-cloneable-value');
          var $group_title = $(this).find('.wpcf--active .sp-carousel-type');
          $title.html($group_title.html());
        });
      }, 100);
    });
  };
  //
  // Field: wp_editor
  //
  $.fn.wpcf_field_wp_editor = function () {
    return this.each(function () {

      if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.wpcf_wp_editor === 'undefined') {
        return;
      }

      var $this = $(this),
        $editor = $this.find('.wpcf-wp-editor'),
        $textarea = $this.find('textarea');

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

      if ($has_wp_editor) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css('display', '');
      }

      // Generate a unique id
      var uid = SP_WPCF.helper.uid('wpcf-editor-');

      $textarea.attr('id', uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.wpcf_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.wpcf_wp_editor
      };

      // Get default editor settings
      var field_editor_settings = $editor.data('editor-settings');

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if (wpEditor && wpEditor.hasOwnProperty('autop')) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Add on change event handle
      var editor_on_change = function (editor) {
        editor.on('change keyup', function () {
          var value = (field_editor_settings.wpautop) ? editor.getContent() : wp.editor.removep(editor.getContent());
          $textarea.val(value).trigger('change');
        });
      };

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });

      // Override editor tinymce settings
      if (field_editor_settings.tinymce === false) {
        default_editor_settings.tinymce = false;
        $editor.addClass('wpcf-no-tinymce');
      }

      // Override editor quicktags settings
      if (field_editor_settings.quicktags === false) {
        default_editor_settings.quicktags = false;
        $editor.addClass('wpcf-no-quicktags');
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ($this.is(':visible')) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if (field_editor_settings.media_buttons && window.wpcf_media_buttons) {
        var $editor_buttons = $editor.find('.wp-media-buttons');
        if ($editor_buttons.length) {
          $editor_buttons.find('.wpcf-shortcode-button').data('editor-id', uid);
        } else {
          var $media_buttons = $(window.wpcf_media_buttons);
          $media_buttons.find('.wpcf-shortcode-button').data('editor-id', uid);
          $editor.prepend($media_buttons);
        }
      }
    });
  };
  //
  // Field: code_editor
  //
  $.fn.wpcf_field_code_editor = function () {
    return this.each(function () {

      if (typeof CodeMirror !== 'function') { return; }

      var $this = $(this),
        $textarea = $this.find('textarea'),
        $inited = $this.find('.CodeMirror'),
        data_editor = $textarea.data('editor');

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(':visible')) {

          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== 'default' && SP_WPCF.vars.code_themes.indexOf(data_editor.theme) === -1) {

            var $cssLink = $('<link>');

            $('#wpcf-codemirror-css').after($cssLink);

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'wpcf-codemirror-' + data_editor.theme + '-css',
              href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
              type: 'text/css',
              media: 'all'
            });

            SP_WPCF.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on('change', function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };


  //
  // Field: gallery
  //
  $.fn.wpcf_field_gallery = function () {
    return this.each(function () {

      var $this = $(this),
        $edit = $this.find('.wpcf-edit-gallery'),
        $clear = $this.find('.wpcf-clear-gallery'),
        $list = $this.find('ul.sp-gallery-images'),
        $sorter = $this.find('ul.sp-gallery-images'),
        $input = $this.find('input'),
        $img = $this.find('img'),
        wp_media_frame;
      $sorter.sortable({
        cursor: "move",
        // connectWith: $disabled,
        // placeholder: 'ui-sortable-placeholder',
        update: function (event, ui) {
          var selectedIds = Array();
          $('.sp-gallery-images li a.edit-attachment-modify').each(function () {
            selectedIds.push($(this).data('id'));
          });
          $input.val(selectedIds.join(',')).trigger('change');
          wpcp_image_edit.init();
        }
      });
      $(document).find('.wpcp_image-thumbnail-delete').on('click', function (e) {
        e.preventDefault();
        $(this).parents('li').remove();
        setTimeout(() => {
          wpcp_image_edit.init();
          var selectedIds = Array();
          $('.sp-gallery-images li a.edit-attachment-modify').each(function () {
            selectedIds.push($(this).data('id'));
          });
          $input.val(selectedIds.join(',')).trigger('change');
        }, 3000);
      });



      $this.on('click', '.wpcf-button, .wpcf-edit-gallery', function (e) {

        var $el = $(this),
          ids = $input.val(),
          what = ($el.hasClass('wpcf-edit-gallery')) ? 'edit' : 'add',
          state = (what === 'add' && !ids.length) ? 'gallery' : 'gallery-edit';

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) { return; }

        // Open media with state
        // if (state === 'gallery') {

        //   wp_media_frame = window.wp.media({
        //     library: {
        //       type: 'image'
        //     },
        //     frame: 'post',
        //     state: 'gallery',
        //     multiple: true
        //   });

        //   wp_media_frame.open();

        // } else {

        //   wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');

        //   if (what === 'add') {
        //     wp_media_frame.setState('gallery-library');
        //   }

        // }

        // Open media with state.
        if (state === 'gallery') {
          wp_media_frame = window.wp.media({
            library: {
              type: 'image'
            },
            frame: 'post',
            state: 'gallery',
            multiple: true
          });
          wp_media_frame.open();
        } else {
          wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');
          if (what === 'add') {
            wp_media_frame.setState('gallery-library');
          }
        }

        // Media Update
        // Media Update handler
        wp_media_frame.on('update', function (selection) {
          // Clear the current media list
          $list.empty();

          // Iterate over the selected media items and process each one
          const selectedIds = selection.models.map(attachment => {
            // Convert the media item to a JSON object for easier manipulation
            const item = attachment.toJSON();

            // Determine the thumbnail image URL or fallback to the default media URL if no thumbnail exists
            //	const thumb = item.sizes?.thumbnail?.url || item.url;
            const thumb = (item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url) || item.url;
            // Create a deep copy of the media item data and remove unnecessary properties
            const dataItem = { ...item };
            delete dataItem.compat;
            delete dataItem.nonces;
            delete dataItem.sizes; // Remove the 'sizes' to keep the data lightweight

            // Append the media item to the list with an edit and delete button
            $list.append(`<li>
									<img src="${thumb}">
									<a class="edit-attachment-modify ${item.id} wcp-icon edit-icon"
									data-id="${item.id}" href="#">
										<span class="wpcf-icon-edit"></span>
									</a>
									<a class="wpcp_image-thumbnail-delete remove-icon wcp-icon"
									data-id="${item.id}" href="#">
										<span class="wpcf-icon-delete"></span>
									</a>
								</li>`);

            // Convert the media item data into a JSON string for storage
            const jsonString = JSON.stringify(dataItem);

            // Find the current media item’s edit button and attach the JSON data to it
            const currentItem = $list.find(`.edit-attachment-modify.${item.id}`);
            currentItem.attr('data-wpcp_image-model', jsonString);

            // Return the item's ID to be used later for input updates
            return item.id;
          });

          // Update the hidden input field with the list of selected media item IDs
          $input.val(selectedIds.join(',')).trigger('change');

          // Make the clear and edit buttons visible (remove hidden class)
          $clear.removeClass('hidden');
          $edit.removeClass('hidden');

          // Initialize any custom image editing scripts (likely a plugin-specific function)
          wpcp_image_edit.init();

          // Handle the delete functionality for each media item
          $(document).find('.wpcp_image-thumbnail-delete').on('click', function (e) {
            e.preventDefault(); // Prevent the default anchor link behavior

            // Remove the media item from the DOM
            $(this).closest('li').remove();

            // Collect the remaining media item IDs
            const updatedIds = [];
            $('.sp-gallery-images li a.edit-attachment-modify').each(function () {
              updatedIds.push($(this).data('id')); // Push the remaining item's ID to the array
            });

            // Update the hidden input field with the new list of IDs
            $input.val(updatedIds.join(',')).trigger('change');

            // Re-initialize the custom image editing scripts
            wpcp_image_edit.init();
          });

          // Fetch additional attachment details for each selected media item
          setTimeout(() => {
            selectedIds.forEach(id => {

              // Find the corresponding edit button for this media item
              const currentItem = $list.find(`.edit-attachment-modify.${id}`);

              // Get the stored JSON data associated with this media item
              const jsonData = currentItem.data('wpcp_image-model');

              // Make an AJAX call to retrieve additional data for this media item
              wp.media.ajax('wpcp_image_get_attachment_links', {
                data: {
                  nonce: wpcf_metabox_local.save_nonce, // Security nonce for validation
                  attachment_id: id, // Media item ID
                },
                // If the request is successful, merge the new data with the existing JSON data
                success(response) {
                  const mergedObject = { ...jsonData, ...response }; // Merge existing and new data
                  const updatedJsonString = JSON.stringify(mergedObject); // Convert to JSON string
                  currentItem.attr('data-wpcp_image-model', updatedJsonString); // Update the data attribute

                  // Re-initialize custom image editing scripts with updated data
                  wpcp_image_edit.init();
                },
                // If an error occurs during the request, log it in the console
                error(error_message) {
                  console.error('Error fetching attachment links:', error_message);
                }
              });
            });
          }, 2000);
        });

      });
      $this.on('click', '.edit-attachment', function (e) {
        var $el = $(this),
          ids = $input.val(),
          what = ($el.hasClass('sp_wpcp-edit-gallery')) ? 'edit' : 'add',
          attachId = $el.data('id'),
          state = (what === 'add' && !ids.length) ? 'gallery' : 'gallery-edit';
        e.preventDefault();
        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) { return; }
        // Get the attachment object for the specified image ID
        var attachment = wp.media.attachment();
        // Open media editor popup for the single image
        wp.media.editor.open({
          title: 'Edit Image', // Title of the modal
          multiple: false, // Set to false to only allow selecting one image.
          button: { text: 'Select' }, // Custom text for the select button.
          // Set the initial selection to the specified image attachment.
          selection: attachment,
          // Callback function when image is selected
          // 'selection' parameter contains the selected image information
          // Here, you can perform actions with the selected image
          // For example, you can log the selected image details to console
          // or perform additional operations with it
          // onSelect: function (selection) {
          // 	console.log(selection);
          // }
        });

      });
      $clear.on('click', function (e) {
        e.preventDefault();
        $list.empty();
        $input.val('').trigger('change');
        $clear.addClass('hidden');
        $edit.addClass('hidden');
      });

    });

  };

  //
  // Field: slider
  //
  $.fn.wpcf_field_slider = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find('input'),
        $slider = $this.find('.wpcf-slider-ui'),
        data = $input.data(),
        value = $input.val() || 0;

      if ($slider.hasClass('ui-slider')) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        slide: function (e, o) {
          $input.val(o.value).trigger('change');
        }
      });

      $input.on('keyup', function () {
        $slider.slider('value', $input.val());
      });

    });
  };



  //
  // Field: spinner
  //
  $.fn.wpcf_field_spinner = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $inited = $this.find('.ui-button'),
        data = $input.data();

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        create: function (event, ui) {
          if (data.unit) {
            $input.after('<span class="ui-button wpcf--unit">' + data.unit + '</span>');
          }
        },
        spin: function (event, ui) {
          $input.val(ui.value).trigger('change');
        }
      });

    });
  };

  //
  // Field: switcher
  //
  $.fn.wpcf_field_switcher = function () {
    return this.each(function () {

      var $switcher = $(this).find('.wpcf--switcher');

      $switcher.on('click', function () {

        var value = 0;
        var $input = $switcher.find('input');

        if ($switcher.hasClass('wpcf--active')) {
          $switcher.removeClass('wpcf--active');
        } else {
          value = 1;
          $switcher.addClass('wpcf--active');
        }

        $input.val(value).trigger('change');

      });

    });
  };



  //
  // Field: typography
  //
  $.fn.wpcf_field_typography = function () {
    return this.each(function () {

      var base = this;
      var $this = $(this);
      var loaded_fonts = [];
      var webfonts = wpcf_typography_json.webfonts;
      var googlestyles = wpcf_typography_json.googlestyles;
      var defaultstyles = wpcf_typography_json.defaultstyles;

      //
      //
      // Sanitize google font subset
      base.sanitize_subset = function (subset) {
        subset = subset.replace('-ext', ' Extended');
        subset = subset.charAt(0).toUpperCase() + subset.slice(1);
        return subset;
      };

      //
      //
      // Sanitize google font styles (weight and style)
      base.sanitize_style = function (style) {
        return googlestyles[style] ? googlestyles[style] : style;
      };

      //
      //
      // Load google font
      base.load_google_font = function (font_family, weight, style) {

        if (font_family && typeof WebFont === 'object') {

          weight = weight ? weight.replace('normal', '') : '';
          style = style ? style.replace('normal', '') : '';

          if (weight || style) {
            font_family = font_family + ':' + weight + style;
          }

          if (loaded_fonts.indexOf(font_family) === -1) {
            WebFont.load({ google: { families: [font_family] } });
          }

          loaded_fonts.push(font_family);

        }

      };

      //
      //
      // Append select options
      base.append_select_options = function ($select, options, condition, type, is_multi) {

        $select.find('option').not(':first').remove();

        var opts = '';

        $.each(options, function (key, value) {

          var selected;
          var name = value;

          // is_multi
          if (is_multi) {
            selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
          } else {
            selected = (condition && condition === value) ? ' selected' : '';
          }

          if (type === 'subset') {
            name = base.sanitize_subset(value);
          } else if (type === 'style') {
            name = base.sanitize_style(value);
          }

          opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

        });

        $select.append(opts).trigger('wpcf.change').trigger('chosen:updated');

      };

      base.init = function () {

        //
        //
        // Constants
        var selected_styles = [];
        var $typography = $this.find('.wpcf--typography');
        var $type = $this.find('.wpcf--type');
        var $styles = $this.find('.wpcf--block-font-style');
        var unit = $typography.data('unit');
        var line_height_unit = $typography.data('line-height-unit');
        var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

        //
        //
        // Chosen init
        if ($this.find('.wpcf--chosen').length) {

          var $chosen_selects = $this.find('select');

          $chosen_selects.each(function () {

            var $chosen_select = $(this),
              $chosen_inited = $chosen_select.parent().find('.chosen-container');

            if ($chosen_inited.length) {
              $chosen_inited.remove();
            }

            $chosen_select.chosen({
              allow_single_deselect: true,
              disable_search_threshold: 15,
              width: '100%'
            });

          });

        }

        //
        //
        // Font family select
        var $font_family_select = $this.find('.wpcf--font-family');
        var first_font_family = $font_family_select.val();

        // Clear default font family select options
        $font_family_select.find('option').not(':first-child').remove();

        var opts = '';

        $.each(webfonts, function (type, group) {

          // Check for exclude fonts
          if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

          opts += '<optgroup label="' + group.label + '">';

          $.each(group.fonts, function (key, value) {

            // use key if value is object
            value = (typeof value === 'object') ? key : value;
            var selected = (value === first_font_family) ? ' selected' : '';
            opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

          });

          opts += '</optgroup>';

        });

        // Append google font select options
        $font_family_select.append(opts).trigger('chosen:updated');

        //
        //
        // Font style select
        var $font_style_block = $this.find('.wpcf--block-font-style');

        if ($font_style_block.length) {

          var $font_style_select = $this.find('.wpcf--font-style-select');
          var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

          //
          // Font Style on on change listener
          $font_style_select.on('change wpcf.change', function (event) {

            var style_value = $font_style_select.val();

            // set a default value
            if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
              style_value = selected_styles[0];
            }

            // set font weight, for eg. replacing 800italic to 800
            var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
            var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
            var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

            $this.find('.wpcf--font-weight').val(font_weight);
            $this.find('.wpcf--font-style').val(font_style);

          });

          //
          //
          // Extra font style select
          var $extra_font_style_block = $this.find('.wpcf--block-extra-styles');

          if ($extra_font_style_block.length) {
            var $extra_font_style_select = $this.find('.wpcf--extra-styles');
            var first_extra_style_value = $extra_font_style_select.val();
          }

        }

        //
        //
        // Subsets select
        var $subset_block = $this.find('.wpcf--block-subset');
        if ($subset_block.length) {
          var $subset_select = $this.find('.wpcf--subset');
          var first_subset_select_value = $subset_select.val();
          var subset_multi_select = $subset_select.data('multiple') || false;
        }

        //
        //
        // Backup font family
        var $backup_font_family_block = $this.find('.wpcf--block-backup-font-family');

        //
        //
        // Font Family on Change Listener
        $font_family_select.on('change wpcf.change', function (event) {

          // Hide subsets on change
          if ($subset_block.length) {
            $subset_block.addClass('hidden');
          }

          // Hide extra font style on change
          if ($extra_font_style_block.length) {
            $extra_font_style_block.addClass('hidden');
          }

          // Hide backup font family on change
          if ($backup_font_family_block.length) {
            $backup_font_family_block.addClass('hidden');
          }

          var $selected = $font_family_select.find(':selected');
          var value = $selected.val();
          var type = $selected.data('type');

          if (type && value) {

            // Show backup fonts if font type google or custom
            if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
              $backup_font_family_block.removeClass('hidden');
            }

            // Appending font style select options
            if ($font_style_block.length) {

              // set styles for multi and normal style selectors
              var styles = defaultstyles;

              // Custom or gogle font styles
              if (type === 'google' && webfonts[type].fonts[value][0]) {
                styles = webfonts[type].fonts[value][0];
              } else if (type === 'custom' && webfonts[type].fonts[value]) {
                styles = webfonts[type].fonts[value];
              }

              selected_styles = styles;

              // Set selected style value for avoid load errors
              var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
              var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

              // Append style select options
              base.append_select_options($font_style_select, styles, set_style_value, 'style');

              // Clear first value
              first_style_value = false;

              // Show style select after appended
              $font_style_block.removeClass('hidden');

              // Appending extra font style select options
              if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

                // Append extra-style select options
                base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

                // Clear first value
                first_extra_style_value = false;

                // Show style select after appended
                $extra_font_style_block.removeClass('hidden');

              }

            }

            // Appending google fonts subsets select options
            if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

              var subsets = webfonts[type].fonts[value][1];
              var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
              var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

              // check for multiple subset select
              set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

              base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

              first_subset_select_value = false;

              $subset_block.removeClass('hidden');

            }

          } else {

            // Clear Styles
            $styles.find(':input').val('');

            // Clear subsets options if type and value empty
            if ($subset_block.length) {
              $subset_select.find('option').not(':first-child').remove();
              $subset_select.trigger('chosen:updated');
            }

            // Clear font styles options if type and value empty
            if ($font_style_block.length) {
              $font_style_select.find('option').not(':first-child').remove();
              $font_style_select.trigger('chosen:updated');
            }

          }

          // Update font type input value
          $type.val(type);

        }).trigger('wpcf.change');

        //
        //
        // Preview
        var $preview_block = $this.find('.wpcf--block-preview');

        if ($preview_block.length) {

          var $preview = $this.find('.wpcf--preview');

          // Set preview styles on change
          $this.on('change', SP_WPCF.helper.debounce(function (event) {

            $preview_block.removeClass('hidden');

            var font_family = $font_family_select.val(),
              font_weight = $this.find('.wpcf--font-weight').val(),
              font_style = $this.find('.wpcf--font-style').val(),
              font_size = $this.find('.wpcf--font-size').val(),
              font_variant = $this.find('.wpcf--font-variant').val(),
              line_height = $this.find('.wpcf--line-height').val(),
              text_align = $this.find('.wpcf--text-align').val(),
              text_transform = $this.find('.wpcf--text-transform').val(),
              text_decoration = $this.find('.wpcf--text-decoration').val(),
              text_color = $this.find('.wpcf--color').val(),
              word_spacing = $this.find('.wpcf--word-spacing').val(),
              letter_spacing = $this.find('.wpcf--letter-spacing').val(),
              custom_style = $this.find('.wpcf--custom-style').val(),
              type = $this.find('.wpcf--type').val();

            if (type === 'google') {
              base.load_google_font(font_family, font_weight, font_style);
            }

            var properties = {};

            if (font_family) { properties.fontFamily = font_family; }
            if (font_weight) { properties.fontWeight = font_weight; }
            if (font_style) { properties.fontStyle = font_style; }
            if (font_variant) { properties.fontVariant = font_variant; }
            if (font_size) { properties.fontSize = font_size + unit; }
            if (line_height) { properties.lineHeight = line_height + line_height_unit; }
            if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
            if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
            if (text_align) { properties.textAlign = text_align; }
            if (text_transform) { properties.textTransform = text_transform; }
            if (text_decoration) { properties.textDecoration = text_decoration; }
            if (text_color) { properties.color = text_color; }

            $preview.removeAttr('style');

            // Customs style attribute
            if (custom_style) { $preview.attr('style', custom_style); }

            $preview.css(properties);

          }, 100));

          // Preview black and white backgrounds trigger
          $preview_block.on('click', function () {

            $preview.toggleClass('wpcf--black-background');

            var $toggle = $preview_block.find('.wpcf--toggle');

            if ($toggle.hasClass('fa-toggle-off')) {
              $toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
            } else {
              $toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
            }

          });

          if (!$preview_block.hasClass('hidden')) {
            $this.trigger('change');
          }

        }

      };

      base.init();

    });
  };

  //
  // Field: tabbed
  //
  $.fn.wpcf_field_tabbed = function () {
    return this.each(function () {
      var $this = $(this),
        $links = $this.find('.wpcf-tabbed-nav a'),
        $sections = $this.find('.wpcf-tabbed-section');
      $links.on('click', function (e) {
        e.preventDefault();
        var $link = $(this),
          index = $link.index(),
          $section = $sections.eq(index);

        // Store the active tab index in a cookie
        SP_WPCF.helper.set_cookie('activeTabIndex', index);

        $link.addClass('wpcf-tabbed-active').siblings().removeClass('wpcf-tabbed-active');
        $section.wpcf_reload_script();
        $section.removeClass('hidden').siblings().addClass('hidden');
      });
      // Check if there's a stored active tab index in the cookie
      var activeTabIndex = SP_WPCF.helper.get_cookie('activeTabIndex');
      // Check if the cookie exists
      if (activeTabIndex !== null) {
        $links.eq(activeTabIndex).trigger('click');
      } else {
        $links.first().trigger('click');
      }
    });
  };

  //
  // Field: fieldset
  //
  $.fn.wpcf_field_fieldset = function () {
    return this.each(function () {
      $(this).find('.wpcf-fieldset-content').wpcf_reload_script();
    });
  };

  //
  // Confirm
  //
  $.fn.wpcf_confirm = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        var confirm_text = $(this).data('confirm') || window.wpcf_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);

        if (confirm_answer) {
          SP_WPCF.vars.is_confirm = true;
          SP_WPCF.vars.form_modified = false;
        } else {
          e.preventDefault();
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function () {

    var obj = {};

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value;

      obj[n] = obj[n] === undefined ? v
        : $.isArray(obj[n]) ? obj[n].concat(v)
          : [obj[n], v];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.wpcf_save = function () {
    return this.each(function () {

      var $this = $(this),
        $buttons = $('.wpcf-save'),
        $panel = $('.wpcf-options'),
        flooding = false,
        timeout;

      $this.on('click', function (e) {

        if (!flooding) {

          var $text = $this.data('save'),
            $value = $this.val();

          $buttons.attr('value', $text);

          if ($this.hasClass('wpcf-save-ajax')) {

            e.preventDefault();

            $panel.addClass('wpcf-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post('wpcf_' + $panel.data('unique') + '_ajax_save', {
              data: $('#wpcf-form').serializeJSONSP_WPCF()
            })
              .done(function (response) {

                // clear errors
                $('.wpcf-error').remove();

                if (Object.keys(response.errors).length) {

                  var error_icon = '<i class="wpcf-label-error wpcf-error">!</i>';

                  $.each(response.errors, function (key, error_message) {

                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $('a[href="#tab=' + $field.closest('.wpcf-section').data('section-id') + '"]'),
                      $tab = $link.closest('.wpcf-tab-item');

                    $field.closest('.wpcf-fieldset').append('<p class="wpcf-error wpcf-error-text">' + error_message + '</p>');

                    if (!$link.find('.wpcf-error').length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find('.wpcf-arrow .wpcf-error').length) {
                      $tab.find('.wpcf-arrow').append(error_icon);
                    }

                  });

                }

                $panel.removeClass('wpcf-saving');
                $buttons.prop('disabled', false).attr('value', $value);
                flooding = false;

                SP_WPCF.vars.form_modified = false;
                SP_WPCF.vars.$form_warning.hide();

                clearTimeout(timeout);

                var $result_success = $('.wpcf-form-success');
                $result_success.empty().append(response.notice).fadeIn('fast', function () {
                  timeout = setTimeout(function () {
                    $result_success.fadeOut('fast');
                  }, 1000);
                });

              })
              .fail(function (response) {
                alert(response.error);
              });

          } else {

            SP_WPCF.vars.form_modified = false;

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Option Framework
  //
  $.fn.wpcf_options = function () {
    return this.each(function () {

      var $this = $(this),
        $content = $this.find('.wpcf-content'),
        $form_success = $this.find('.wpcf-form-success'),
        $form_warning = $this.find('.wpcf-form-warning'),
        $save_button = $this.find('.wpcf-header .wpcf-save');

      SP_WPCF.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ($form_warning.length) {

        window.onbeforeunload = function () {
          return (SP_WPCF.vars.form_modified) ? true : undefined;
        };

        $content.on('change keypress', ':input', function () {
          if (!SP_WPCF.vars.form_modified) {
            $form_success.hide();
            $form_warning.fadeIn('fast');
            SP_WPCF.vars.form_modified = true;
          }
        });

      }

      if ($form_success.hasClass('wpcf-form-show')) {
        setTimeout(function () {
          $form_success.fadeOut('fast');
        }, 1000);
      }

      $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which === 83) {
          $save_button.trigger('click');
          event.preventDefault();
          return false;
        }
      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.wpcf_taxonomy = function () {
    return this.each(function () {

      var $this = $(this),
        $form = $this.parents('form');

      if ($form.attr('id') === 'addtag') {

        var $submit = $form.find('#submit'),
          $cloned = $this.find('.wpcf-field').wpcf_clone();

        $submit.on('click', function () {

          if (!$form.find('.form-required').hasClass('form-invalid')) {

            $this.data('inited', false);

            $this.empty();

            $this.html($cloned);

            $cloned = $cloned.wpcf_clone();

            $this.wpcf_reload_script();

          }

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.wpcf_shortcode = function () {

    var base = this;

    base.shortcode_parse = function (serialize, key) {

      var shortcode = '';

      $.each(serialize, function (shortcode_key, shortcode_values) {

        key = (key) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {

          if (shortcode_tag === 'content') {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/' + key + '';

          } else {

            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {

      var shortcode = '';

      if (shortcode_value !== '') {

        if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

            // sanitize spesific key/value
            switch (sub_shortcode_tag) {

              case 'background-image':
                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                break;

            }

            if (sub_shortcode_value !== '') {
              shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function (_this, currentValue) {

      var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
        obj.focus();
        return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function (html, editor_id) {

      var tinymce_editor;

      if (typeof tinymce !== 'undefined') {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand('mceInsertContent', false, html);
      } else {
        var $editor = $('#' + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger('change');
      }

    };

    return this.each(function () {

      var $modal = $(this),
        $load = $modal.find('.wpcf-modal-load'),
        $content = $modal.find('.wpcf-modal-content'),
        $insert = $modal.find('.wpcf-modal-insert'),
        $loading = $modal.find('.wpcf-modal-loading'),
        $select = $modal.find('select'),
        modal_id = $modal.data('modal-id'),
        nonce = $modal.data('nonce'),
        editor_id,
        target_id,
        gutenberg_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on('click', '.wpcf-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

        e.preventDefault();

        $button = $(this);
        editor_id = $button.data('editor-id') || false;
        target_id = $button.data('target-id') || false;
        gutenberg_id = $button.data('gutenberg-id') || false;

        $modal.removeClass('hidden');

        // single usage trigger first shortcode
        if ($modal.hasClass('wpcf-shortcode-single') && sc_name === undefined) {
          $select.trigger('change');
        }

      });

      $select.on('change', function () {

        var $option = $(this);
        var $selected = $option.find(':selected');

        sc_key = $option.val();
        sc_name = $selected.data('shortcode');
        sc_view = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if (sc_key) {

          $loading.show();

          window.wp.ajax.post('wpcf-get-shortcode-' + modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
            .done(function (response) {

              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass('hidden');

              $cloned = $appended.find('.wpcf--repeat-shortcode').wpcf_clone();

              $appended.wpcf_reload_script();
              $appended.find('.wpcf-fields').wpcf_reload_script();

            });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function (e) {

        e.preventDefault();

        if ($insert.prop('disabled') || $insert.attr('disabled')) { return; }

        var shortcode = '';
        var serialize = $modal.find('.wpcf-field:not(.wpcf-depend-on)').find(':input:not(.ignore)').serializeObjectSP_WPCF();

        switch (sc_view) {

          case 'contents':
            var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = (sc_name) ? sc_name : sc_key;
              shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
            });
            break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += ']';
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += '[/' + sc_name + ']';

            break;

          case 'repeater':
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;

        }

        shortcode = (shortcode === '') ? '[' + sc_name + ']' : shortcode;

        if (gutenberg_id) {

          var content = window.wpcf_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.wpcf_gutenberg_props.attributes.shortcode : '';
          window.wpcf_gutenberg_props.setAttributes({ shortcode: content + shortcode });

        } else if (editor_id) {

          base.send_to_editor(shortcode, editor_id);

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

        }

        $modal.addClass('hidden');

      });

      $modal.on('click', '.wpcf--repeat-button', function (e) {

        e.preventDefault();

        var $repeatable = $modal.find('.wpcf--repeatable');
        var $new_clone = $cloned.wpcf_clone();
        var $remove_btn = $new_clone.find('.wpcf-repeat-remove');

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find('.wpcf-fields').wpcf_reload_script();

        SP_WPCF.helper.name_nested_replace($modal.find('.wpcf--repeat-shortcode'), sc_group);

        $remove_btn.on('click', function () {

          $new_clone.remove();

          SP_WPCF.helper.name_nested_replace($modal.find('.wpcf--repeat-shortcode'), sc_group);

        });

      });

      $modal.on('click', '.wpcf-modal-close, .wpcf-modal-overlay', function () {
        $modal.addClass('hidden');
      });

    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === 'function') {

    Color.prototype.toString = function () {

      if (this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) { return ''; }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  SP_WPCF.funcs.parse_color = function (color) {

    var value = color.replace(/\s+/g, ''),
      trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
      rgba = (trans < 100) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.wpcf_color = function () {
    return this.each(function () {

      var $input = $(this),
        picker_color = SP_WPCF.funcs.parse_color($input.val()),
        palette_color = window.wpcf_vars.color_palette.length ? window.wpcf_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass('wp-color-picker')) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('wpcf--transparent-active');
          $container.find('.wpcf--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function () {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
            $transparent_wrap = $('<div class="wpcf--transparent-wrap">' +
              '<div class="wpcf--transparent-slider"></div>' +
              '<div class="wpcf--transparent-offset"></div>' +
              '<div class="wpcf--transparent-text"></div>' +
              '<div class="wpcf--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
              '</div>').appendTo($container.find('.wp-picker-holder')),
            $transparent_slider = $transparent_wrap.find('.wpcf--transparent-slider'),
            $transparent_text = $transparent_wrap.find('.wpcf--transparent-text'),
            $transparent_offset = $transparent_wrap.find('.wpcf--transparent-offset'),
            $transparent_button = $transparent_wrap.find('.wpcf--transparent-button');

          if ($input.val() === 'transparent') {
            $container.addClass('wpcf--transparent-active');
          }

          $transparent_button.on('click', function () {
            if ($input.val() !== 'transparent') {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('wpcf--transparent-active');
            } else {
              $input.val(a8cIris._color.toString()).trigger('change');
              $container.removeClass('wpcf--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function (event, ui) {

              var slide_value = parseFloat(ui.value / 100);
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker('color', a8cIris._color.toString());
              $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

            },
            create: function () {

              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function () {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('wpcf--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function () {

                var default_color = SP_WPCF.funcs.parse_color($input.data('default-color')),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

                if (default_color.value === 'transparent') {
                  $input.removeClass('iris-error');
                  $container.addClass('wpcf--transparent-active');
                }

              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.wpcf_chosen = function () {
    return this.each(function () {

      var $this = $(this),
        $inited = $this.parent().find('.chosen-container'),
        is_sortable = $this.hasClass('wpcf-chosen-sortable') || false,
        is_ajax = $this.hasClass('wpcf-chosen-ajax') || false,
        is_multiple = $this.attr('multiple') || false,
        set_width = is_multiple ? '100%' : 'auto',
        set_options = $.extend({
          allow_single_deselect: true,
          disable_search_threshold: 10,
          width: set_width,
          no_results_text: window.wpcf_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

      if ($inited.length) {
        $inited.remove();
      }

      // Chosen ajax
      if (is_ajax) {

        var set_ajax_options = $.extend({
          data: {
            type: 'post',
            nonce: '',
          },
          allow_single_deselect: true,
          disable_search_threshold: -1,
          width: '100%',
          min_length: 3,
          type_delay: 500,
          typing_text: window.wpcf_vars.i18n.typing_text,
          searching_text: window.wpcf_vars.i18n.searching_text,
          no_results_text: window.wpcf_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

        $this.SP_WPCFAjaxChosen(set_ajax_options);

      } else {

        $this.chosen(set_options);

      }

      // Chosen keep options order
      if (is_multiple) {

        var $hidden_select = $this.parent().find('.wpcf-hide-select');
        var $hidden_value = $hidden_select.val() || [];

        $this.on('change', function (obj, result) {

          if (result && result.selected) {
            $hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
          } else if (result && result.deselected) {
            $hidden_select.find('option[value="' + result.deselected + '"]').remove();
          }

          // Force customize refresh
          if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
            window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
          }

          $hidden_select.trigger('change');

        });

        // Chosen order abstract
        $this.SP_WPCFChosenOrder($hidden_value, true);

      }

      // Chosen sortable
      if (is_sortable) {

        var $chosen_container = $this.parent().find('.chosen-container');
        var $chosen_choices = $chosen_container.find('.chosen-choices');

        $chosen_choices.bind('mousedown', function (event) {
          if ($(event.target).is('span')) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: 'li:not(.search-field)',
          helper: 'orginal',
          cursor: 'move',
          placeholder: 'search-choice-placeholder',
          start: function (e, ui) {
            ui.placeholder.width(ui.item.innerWidth());
            ui.placeholder.height(ui.item.innerHeight());
          },
          update: function (e, ui) {

            var select_options = '';
            var chosen_object = $this.data('chosen');
            var $prev_select = $this.parent().find('.wpcf-hide-select');

            $chosen_choices.find('.search-choice-close').each(function () {
              var option_array_index = $(this).data('option-array-index');
              $.each(chosen_object.results_data, function (index, data) {
                if (data.array_index === option_array_index) {
                  select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger('change');

          }
        });

      }

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.wpcf_checkbox = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('.wpcf--input'),
        $checkbox = $this.find('.wpcf--checkbox');

      $checkbox.on('click', function () {
        $input.val(Number($checkbox.prop('checked'))).trigger('change');
      });

    });
  };

  //
  // Siblings
  //
  $.fn.wpcf_siblings = function () {
    return this.each(function () {

      var $this = $(this),
        $siblings = $this.find('.wpcf--sibling:not(.wpcf-disabled):not(.wpcf-pro-only)'),
        multiple = $this.data('multiple') || false;

      $siblings.on('click', function () {

        var $sibling = $(this);

        if (multiple) {

          if ($sibling.hasClass('wpcf--active')) {
            $sibling.removeClass('wpcf--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('wpcf--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('wpcf--active').siblings().removeClass('wpcf--active');

        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.wpcf_help = function () {
    return this.each(function () {

      var $this = $(this),
        $tooltip,
        offset_left,
        $class = '';

      $this.on({
        mouseenter: function () {
          // this class add with the support tooltip.
          if ($this.find('.wpcf-support').length > 0) {
            $class = 'support-tooltip';
          }
          // this class add with the support tooltip.
          if ($this.find('.sp_wpcp-support').length > 0) {
            $class = 'support-tooltip';
          }

          var help_text = $this.find('.wpcf-help-text').html();
          if ($('.wpcf-tooltip').length > 0) {
            $tooltip = $('.wpcf-tooltip').html(help_text);
          } else {
            $tooltip = $('<div class="wpcf-tooltip ' + $class + '"></div>').html(help_text).appendTo('body');
          }

          offset_left = SP_WPCF.vars.is_rtl
            ? $this.offset().left + 36
            : $this.offset().left + 36
          var $top = $this.offset().top - (($tooltip.outerHeight() / 2) - 14);
          // This block used for support tooltip.
          if ($this.find('.sp_wpcp-support').length > 0) {
            $top = $this.offset().top + 52;
            offset_left = $this.offset().left - 222;
          }
          $tooltip.css({
            top: $top,
            left: offset_left,
            textAlign: 'left',
          });

        },
        mouseleave: function () {
          if (!$tooltip.is(':hover')) {
            $tooltip.remove();
          }
        }
      });
      // Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
      $('body').on('mouseleave', '.wpcf-tooltip', function () {
        if ($tooltip !== undefined) {
          $tooltip.remove();
        }
      });
    });
  };
  //
  // Help Tooltip
  //
  // $.fn.wpcf_help = function () {
  //   return this.each(function () {

  //     var $this = $(this),
  //       $tooltip,
  //       offset_left,
  //       $class = '';

  //     $this.on({
  //       mouseenter: function () {
  //         // this class add with the support tooltip.
  //         if ($this.find('.sp_wpcp-support').length > 0) {
  //           $class = 'support-tooltip';
  //         }

  //         var help_text = $this.find('.wpcf-help-text').html();
  //         if ($('.sp_wpcp-tooltip').length > 0) {
  //           $tooltip = $('.sp_wpcp-tooltip').html(help_text);
  //         } else {
  //           $tooltip = $('<div class="sp_wpcp-tooltip ' + $class + '"></div>').html(help_text).appendTo('body');
  //         }

  //         offset_left = SP_WPCP_Framework.vars.is_rtl
  //           ? $this.offset().left + 36
  //           : $this.offset().left + 36;
  //         var $top = $this.offset().top - (($tooltip.outerHeight() / 2) - 14);
  //         // This block used for support tooltip.
  //         if ($this.find('.sp_wpcp-support').length > 0) {
  //           $top = $this.offset().top + 52;
  //           offset_left = $this.offset().left - 212;
  //         }
  //         $tooltip.css({
  //           top: $top,
  //           left: offset_left,
  //           textAlign: 'left',
  //         });

  //       },
  //       mouseleave: function () {
  //         if (!$tooltip.is(':hover')) {
  //           $tooltip.remove();
  //         }
  //       }

  //     });
  //     // Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
  //     $('body').on('mouseleave', '.sp_wpcp-tooltip', function () {
  //       if ($tooltip !== undefined) {
  //         $tooltip.remove();
  //       }
  //     });

  //   });
  // }

  //
  // Customize Refresh
  //
  $.fn.wpcf_customizer_refresh = function () {
    return this.each(function () {

      var $this = $(this),
        $complex = $this.closest('.wpcf-customize-complex');

      if ($complex.length) {

        var unique_id = $complex.data('unique-id');

        if (unique_id === undefined) {
          return;
        }

        var $input = $complex.find(':input'),
          option_id = $complex.data('option-id'),
          obj = $input.serializeObjectSP_WPCF(),
          data = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '',
          control = window.wp.customize.control(unique_id + '[' + option_id + ']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('wpcf-customizer-refresh', $this);

    });
  };


  //
  // Window on resize
  //
  SP_WPCF.vars.$window.on('resize wpcf.resize', SP_WPCF.helper.debounce(function (event) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SP_WPCF.vars.$window.width() : window.innerWidth;

    if (window_width <= 782 && !SP_WPCF.vars.onloaded) {
      $('.wpcf-section').wpcf_reload_script();
      SP_WPCF.vars.onloaded = true;
    }

  }, 200)).trigger('wpcf.resize');

  //
  // Widgets Framework
  //
  $.fn.wpcf_widgets = function () {
    if (this.length) {

      $(document).on('widget-added widget-updated', function (event, $widget) {
        $widget.find('.wpcf-fields').wpcf_reload_script();
      });

      $('.widgets-sortables, .control-section-sidebar').on('sortstop', function (event, ui) {
        ui.item.find('.wpcf-fields').wpcf_reload_script_retry();
      });

      $(document).on('click', '.widget-top', function (event) {
        $(this).parent().find('.wpcf-fields').wpcf_reload_script();
      });

    }
  };

  //
  // Nav Menu Options Framework
  //
  $.fn.wpcf_nav_menu = function () {
    return this.each(function () {

      var $navmenu = $(this);

      $navmenu.on('click', 'a.item-edit', function () {
        $(this).closest('li.menu-item').find('.wpcf-fields').wpcf_reload_script();
      });

      $navmenu.on('sortstop', function (event, ui) {
        ui.item.find('.wpcf-fields').wpcf_reload_script_retry();
      });

    });
  };

  //
  // Retry Plugins
  //
  $.fn.wpcf_reload_script_retry = function () {
    return this.each(function () {

      var $this = $(this);
      if ($this.data('inited')) {
        $this.children('.wpcf-field-wp_editor').wpcf_field_wp_editor();
      }
    });
  };

  //
  // Reload Plugins
  //
  $.fn.wpcf_reload_script = function (options) {

    var settings = $.extend({
      dependency: true,
    }, options);

    return this.each(function () {

      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data('inited')) {

        // Field plugins.
        $this.children('.wpcf-field-code_editor').wpcf_field_code_editor();
        $this.children('.wpcf-field-gallery').wpcf_field_gallery();
        $this.children('.wpcf-field-slider').wpcf_field_slider();
        $this.children('.wpcf-field-spinner').wpcf_field_spinner();
        $this.children('.wpcf-field-switcher').wpcf_field_switcher();
        $this.children('.wpcf-field-typography').wpcf_field_typography();
        $this.children('.wpcf-field-tabbed').wpcf_field_tabbed();
        $this.children('.wpcf-field-fieldset').wpcf_field_fieldset();
        $this.children('.wpcf-field-fieldset_tx').wpcf_field_fieldset();
        $this.children('.wpcf-field-fieldset_cpt').wpcf_field_fieldset();

        // Field colors
        $this.children('.wpcf-field-box_shadow ').find('.wpcf-color').wpcf_color();
        $this.children('.wpcf-field-border').find('.wpcf-color').wpcf_color();
        $this.children('.wpcf-field-color').find('.wpcf-color').wpcf_color();
        $this.children('.wpcf-field-color_group').find('.wpcf-color').wpcf_color();
        $this.children('.wpcf-field-typography').find('.wpcf-color').wpcf_color();
        $this.children('.wpcf-field-group').wpcf_field_group();
        $this.children('.wpcf-field-wp_editor').wpcf_field_wp_editor();
        // Field chosenjs
        $this.children('.wpcf-field-select').find('.wpcf-chosen').wpcf_chosen();

        // Field Checkbox
        $this.children('.wpcf-field-checkbox').find('.wpcf-checkbox').wpcf_checkbox();

        // Field Siblings
        $this.children('.wpcf-field-button_set').find('.wpcf-siblings').wpcf_siblings();
        $this.children('.wpcf-field-image_select').find('.wpcf-siblings').wpcf_siblings();
        $this.children('.wpcf-field-carousel_type').find('.wpcf-siblings').wpcf_siblings();

        // Help Tooptip
        $this.children('.wpcf-field').find('.wpcf-help').wpcf_help();
        $('.wpcp-admin-header').find('.sp_wpcp-support-area').wpcf_help();

        if (settings.dependency) {
          $this.wpcf_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('wpcf-reload-script', $this);

      }

    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {

    $('.wpcf-save').wpcf_save();
    $('.wpcf-options').wpcf_options();
    $('.wpcf-sticky-header').wpcf_sticky();
    $('.wpcf-nav-options').wpcf_nav_options();
    $('.wpcf-nav-metabox').wpcf_nav_metabox();
    $('.wpcf-taxonomy').wpcf_taxonomy();
    $('.wpcf-page-templates').wpcf_page_templates();
    $('.wpcf-post-formats').wpcf_post_formats();
    $('.wpcf-shortcode').wpcf_shortcode();
    $('.wpcf-search').wpcf_search();
    $('.wpcf-confirm').wpcf_confirm();
    $('.wpcf-expand-all').wpcf_expand_all();
    $('.wpcf-onload').wpcf_reload_script();
    $('.widget').wpcf_widgets();
    $('#menu-to-edit').wpcf_nav_menu();


    /* Copy to clipboard */
    $('.wpcf-shortcode-selectable').on('click', function (e) {
      e.preventDefault();
      wpcf_copyToClipboard($(this));
      wpcf_SelectText($(this));
      $(this).focus().select();
      jQuery(".spwpc-after-copy-text").animate({
        opacity: 1,
        bottom: 25
      }, 300);
      setTimeout(function () {
        jQuery(".spwpc-after-copy-text").animate({
          opacity: 0,
        }, 200);
        jQuery(".spwpc-after-copy-text").animate({
          bottom: -100
        }, 0);
      }, 2000);
    });
    function wpcf_copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }
    function wpcf_SelectText(element) {
      var r = document.createRange();
      var w = element.get(0);
      r.selectNodeContents(w);
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(r);
    }

    $('.post-type-sp_wp_carousel .shortcode.column-shortcode input').on('click', function (e) {
      e.preventDefault();
      /* Get the text field */
      var copyText = $(this);
      /* Select the text field */
      copyText.select();
      document.execCommand("copy");

      jQuery(".spwpc-after-copy-text").animate({
        opacity: 1,
        bottom: 25
      }, 300);
      setTimeout(function () {
        jQuery(".spwpc-after-copy-text").animate({
          opacity: 0,
        }, 200);
        jQuery(".spwpc-after-copy-text").animate({
          bottom: -100
        }, 0);
      }, 2000);
    });
    $('.wpcp_post_pagination_type label:contains("Pro")').css({ 'pointer-events': 'none', 'color': 'rgba(85,85,85,.6)!important' });

    // WP Carousel export.
    var $export_type = $('.wpcp_what_export').find('input:checked').val();
    $('.wpcp_what_export').on('change', function () {
      $export_type = $(this).find('input:checked').val();
    });

    // Check If the string is a valid JSON string.
    function isValidJSONString(str) {
      try {
        JSON.parse(str);
      } catch (e) {
        return false;
      }
      return true;
    }

    $('.wpcp_export .wpcf--button').on('click', function (event) {
      event.preventDefault();

      var $shortcode_ids = $('.wpcp_post_ids select').val();
      var $ex_nonce = $('#wpcf_options_noncesp_wpcf_tools').val();
      var selected_shortcode = $export_type === 'selected_shortcodes' ? $shortcode_ids : 'all_shortcodes';
      if ($export_type === 'all_shortcodes' || $export_type === 'selected_shortcodes') {
        var data = {
          action: 'wpcp_export_shortcodes',
          wpcf_ids: selected_shortcode,
          nonce: $ex_nonce,
        }
      } else {
        $('.wpcf-form-result.wpcf-form-success').text('No carousel selected.').show();
        setTimeout(function () {
          $('.wpcf-form-result.wpcf-form-success').hide().text('');
        }, 3000);
      }
      $.post(ajaxurl, data, function (resp) {
        if (resp) {
          // Convert JSON Array to string.
          if (isValidJSONString(resp)) {
            var json = JSON.stringify(JSON.parse(resp));
          } else {
            var json = JSON.stringify(resp);
          }

          // Convert JSON string to BLOB.
          var blob = new Blob([json], { type: 'application/json' });
          var link = document.createElement('a');
          var wpcp_time = $.now();
          link.href = window.URL.createObjectURL(blob);
          link.download = "wp-carousel-export-" + wpcp_time + ".json";
          link.click();
          $('.wpcf-form-result.wpcf-form-success').text('Exported successfully!').show();
          setTimeout(function () {
            $('.wpcf-form-result.wpcf-form-success').hide().text('');
            $('.wpcp_post_ids select').val('').trigger('chosen:updated');
          }, 3000);
        }
      });
    })

    // Get the last activated or selected layout.
    var lastSelectedOption = $('input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val();

    $('input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]').each(function () {
      if ($(this).val() === 'audio-carousel' || $(this).val() === 'content-carousel' || $(this).val() === 'mix-content' || $(this).val() === 'external-carousel' ) {
        $(this).prop('disabled', true);
      }
    });

    $('input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]').on('change', function () {
      if (!$(this).is(':disabled')) {
        lastSelectedOption = $(this).val();
        console.log(lastSelectedOption);
      }
    });
    $('#publishing-action').on('click', '#publish', function (e) {
      if ($('input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').is(':disabled')) {
        $('input[name="sp_wpcp_upload_options[wpcp_carousel_type]"][value="' + lastSelectedOption + '"]').prop('checked', true);
      }
    });

    // WP Carousel Import.
    $('.wpcp_import button.import').on('click', function (event) {
      event.preventDefault();
      var $this = $(this),
        button_text = $this.text(),
        wpcp_shortcodes = $('#import').prop('files')[0];

      if ($('#import').val() != '') {
        $this.css('opacity', '0.7');
        $this.append('<span class="wpcf-page-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');

        var $im_nonce = $('#wpcf_options_noncesp_wpcf_tools').val();
        var reader = new FileReader();
        reader.readAsText(wpcp_shortcodes);
        reader.onload = function (event) {
          var jsonObj = JSON.stringify(event.target.result);
          $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
              shortcode: jsonObj,
              action: 'wpcp_import_shortcodes',
              nonce: $im_nonce,
            },
            success: function (resp) {
              $this.html(button_text).css('opacity', '1');

              $('.wpcf-form-result.wpcf-form-success').text('Imported successfully!').show();
              setTimeout(function () {
                $('.wpcf-form-result.wpcf-form-success').hide().text('');
                $('#import').val('');
                window.location.replace($('#wpcf_shortcode_link_redirect').attr('href'));
              }, 2000);
            },
            error: function (error) {
              $('#import').val('');
              $this.html(button_text).css('opacity', '1');
              $('.wpcf-form-result.wpcf-form-success').addClass('error')
                .text('Something went wrong, please try again!').show();
              setTimeout(function () {
                $('.wpcf-form-result.wpcf-form-success').hide().text('').removeClass('error');
              }, 2000);
            }
          });
        }
      } else {
        $('.wpcf-form-result.wpcf-form-success').text('No exported json file chosen.').show();
        setTimeout(function () {
          $('.wpcf-form-result.wpcf-form-success').hide().text('');
        }, 3000);
      }
    });

    if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'image-carousel') {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(5)').show();
    } else {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(5)').hide();
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
    }
    // Show/hide and trigger click for product carousel tab.
    if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'product-carousel') {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').show();
    } else {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').hide();
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
    }
    // show/hide lightbox tab.
    if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'image-carousel' || $('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'video-carousel') {
      $(".wpcf-nav-metabox li:nth-child(3)").show();
    } else {
      $(".wpcf-nav-metabox li:nth-child(3)").hide();
    }




    // Hide justified layout if source type is not "image carousel".
    $('.wpcf-field-carousel_type.wpcp_carousel_type').on('change', function () {

      // Show/hide and trigger click for post carousel tab.
      if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'post-carousel') {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(3)').show();
      } else {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(3)').hide();
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
      }
      if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'image-carousel') {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(5)').show();
      } else {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(5)').hide();
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
      }
      // Show/hide and trigger click for product carousel tab.
      if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'product-carousel') {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').show();
      } else {
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').hide();
        $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
      }
      //  show/hide Lightbox tab.
      if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'image-carousel' || $('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'video-carousel') {
        $(".wpcf-nav-metabox li:nth-child(3)").show();
      } else {
        $(".wpcf-nav-metabox li:nth-child(3)").hide();
      }
    });
    // Hide Carousel Settings when grid layout will be selected.
    if ($('.wpcp_layout input[name="sp_wpcp_shortcode_options[wpcp_layout]"]:checked').val() == 'grid') {
      $(".wpcf-nav-metabox li:nth-child(4)").hide();
    } else {
      $(".wpcf-nav-metabox li:nth-child(4)").show();
    }
    if ('hide' != $('.wpcp_product_desc input[name="sp_wpcp_shortcode_options[wpcp_product_desc]"]:checked').val()) {
      $('.wpcp_product_desc .wpcf-desc-text').show();
    } else {
      $('.wpcp_product_desc .wpcf-desc-text').hide();
    }
    $('.wpcp_product_desc').on('change', function () {
      if ('hide' != $('.wpcp_product_desc input[name="sp_wpcp_shortcode_options[wpcp_product_desc]"]:checked').val()) {
        $('.wpcp_product_desc .wpcf-desc-text').show();
      } else {
        $('.wpcp_product_desc .wpcf-desc-text').hide();
      }
    });
    if ('vertical_outer' != $('.wpcp-carousel-nav-position select option:selected').val()) {
      $('.wpcp-carousel-nav-position .wpcf-desc-text').show();
    } else {
      $('.wpcp-carousel-nav-position .wpcf-desc-text').hide();
    }
    if ('content_with_limit' == $('.wpcp_post_content_type select option:selected').val()) {
      $('.wpcp_post_content_type .wpcf-desc-text').show();
    } else {
      $('.wpcp_post_content_type .wpcf-desc-text').hide();
    }
    $('.wpcp_post_content_type').on('change', function () {
      if ('content_with_limit' == $('.wpcp_post_content_type select option:selected').val()) {
        $('.wpcp_post_content_type .wpcf-desc-text').show();
      } else {
        $('.wpcp_post_content_type .wpcf-desc-text').hide();
      }
    });
    $('.wpcp-carousel-nav-position').on('change', function () {
      if ('vertical_outer' != $('.wpcp-carousel-nav-position select option:selected').val()) {
        $('.wpcp-carousel-nav-position .wpcf-desc-text').show();
      } else {
        $('.wpcp-carousel-nav-position .wpcf-desc-text').hide();
      }
    });
    $('.wpcf-field-image_select.wpcp_layout').on('change', function () {
      if ($('.wpcp_layout input[name="sp_wpcp_shortcode_options[wpcp_layout]"]:checked').val() == 'grid') {
        $(".wpcf-nav-metabox li:nth-child(4)").hide();
      } else {
        $(".wpcf-nav-metabox li:nth-child(4)").show();
      }
    });

    // hide Lightbox Settings when Click Action Type = 'none'.
    if ($('.wpcp_logo_link_show_class input[name="sp_wpcp_shortcode_options[wpcp_logo_link_show]"]:checked').val() == 'none') {
      $(".wpcf-nav-metabox li:nth-child(3)").hide();
    } else {
      $(".wpcf-nav-metabox li:nth-child(3)").show();
    }
    $('.wpcf-field-image_select.wpcp_logo_link_show_class').on('change', function () {
      if ($('.wpcp_logo_link_show_class input[name="sp_wpcp_shortcode_options[wpcp_logo_link_show]"]:checked').val() == 'none') {
        $(".wpcf-nav-metabox li:nth-child(3)").hide();
      } else {
        $(".wpcf-nav-metabox li:nth-child(3)").show();
      }
    });

    // Show/hide and trigger click for post carousel tab
    if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'post-carousel') {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(3)').show();
    } else {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(3)').hide();
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
    }

    // Show/hide and trigger click for product carousel tab
    if ($('.wpcp_carousel_type input[name="sp_wpcp_upload_options[wpcp_carousel_type]"]:checked').val() == 'product-carousel') {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').show();
    } else {
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(4)').hide();
      $('.wp-carousel-style-tabs .wpcf-tabbed-nav a:nth-child(1)').trigger('click');
    }
  });
  $(document).on('keyup change', '.sp_wp_carousel_page_wpcp_settings #wpcf-form', function (e) {
    e.preventDefault();
    var $button = $(this).find('.wpcf-save');
    $button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
  });
  $('.sp_wp_carousel_page_wpcp_settings .wpcf-save').on('click', function (e) {
    e.preventDefault();
    $(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
  })

  $(document).on('keyup change', '.sp_wp_carousel_page_wpcp_settings #wpcf-form', function (e) {
    e.preventDefault();
    var $button = $(this).find('.wpcf-save');
    $button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
  });
  $('.sp_wp_carousel_page_wpcp_settings .wpcf-save').on('click', function (e) {
    e.preventDefault();
    $(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
  })

  // Live Preview script for Wp-Carousel-free.
  var preview_box = $('#sp-wpcf-preview-box');
  var preview_display = $('#sp_wpcf_live_preview').hide();
  $(document).on('click', '#sp__wpcf-show-preview:contains(Hide)', function (e) {
    e.preventDefault();
    var _this = $(this);
    _this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
    preview_box.html('');
    preview_display.hide();
  });
  $(document).on('click', '#sp__wpcf-show-preview:not(:contains(Hide))', function (e) {
    e.preventDefault();
    var previewJS = wpcf_vars.previewJS;
    var _data = $('form#post').serialize();
    var _this = $(this);
    var data = {
      action: 'sp_wpcf_preview_meta_box',
      data: _data,
      ajax_nonce: $('#wpcf_metabox_noncesp_wpcf_live_preview').val()
    };
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: data,
      error: function (response) {
        console.log(response)
      },
      success: function (response) {
        preview_display.show();
        preview_box.html(response);
        $.getScript(previewJS, function () {
          _this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
          $(document).on('keyup change', '.post-type-sp_wp_carousel', function (e) {
            e.preventDefault();
            _this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
          });
          $("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
        });
        $('.wpcp-carousel-preloader').animate({ opacity: 1 }, 600).hide();
        var carousel_id = $('.wpcp-carousel-section.wpcp-preloader').attr('id');
        $('#' + carousel_id).animate({ opacity: 1 }, 600);
        $('.wpcpro-post-pagination-number').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $('.spwpc-pagination-not-work').animate({
            opacity: 1,
            bottom: 25
          }, 300);
          setTimeout(function () {
            jQuery(".spwpc-pagination-not-work").animate({
              opacity: 0,
            }, 200);
            jQuery(".spwpc-pagination-not-work").animate({
              bottom: 0
            }, 0);
          }, 3000);
        });
      }
    })
  });

  /* Carousel Navigation - Select Position Preview */
  function navigationPositionPreview(selector, regex) {
    var str = "";
    $(selector + ' option:selected').each(function () {
      str = $(this).val();
    });
    var src = $(selector + ' .wpcf-fieldset img').attr('src');
    var result = src.match(regex);
    if (result && result[1]) {
      src = src.replace(result[1], str);
      $(selector + ' .wpcf-fieldset img').attr('src', src);
    }
  }
  $('.wpcp-carousel-nav-position').on('change', function () {
    navigationPositionPreview(".wpcp-carousel-nav-position", /carousel-navigation\/(.+)\.svg/);
  });

  // Disable and style the switcher element
  $('.wpcf_show_hide .wpcf--switcher').attr('disabled', 'disabled').addClass('wp_carousel_only_pro_switcher').css({ 'background': '#99AAB2' });

  // Apply common styling to elements with the 'wp_carousel_only_pro_switcher' class
  $('.wp_carousel_only_pro_switcher').css({ 'pointer-events': 'none', 'color': '#99AAB2', 'position': 'relative' });

  $(document).on("change", ".wpcp_logo_link_show_class", function (event) {
    event.stopPropagation();
    var select_value = $(this)
      .find("input:checked")
      .val();

    if (select_value == "l_box" || select_value == "image-carousel") {
      $(".sp_wpcp-metabox .sp_wpcp-nav.sp_wpcp-nav-metabox li:nth-child(4) a").show();
    } else {
      $(".sp_wpcp-metabox .sp_wpcp-nav.sp_wpcp-nav-metabox li:nth-child(4) a").hide();
      $(".sp_wpcp-metabox .sp_wpcp-nav.sp_wpcp-nav-metabox li:nth-child(1) a").trigger('click');
    }
  });
})(jQuery, window, document);



/**
* Slide Model
*/
var WPCarouselSlide = Backbone.Model.extend({

  /**
  * Defaults
  * As we always populate this model with existing data, we
  * leave these blank to just show how this model is structured.
  */
  defaults: {
    'id': '',
    'title': '',
    'caption': '',
    'alt': '',
    'link': '',
    'type': '',

  },

});

/**
* Images Collection
* - Comprises of all slides in an WPCarousel Slider
* - Each image is represented by an WPCarouselSlides Model
*/
var WPCarouselSlides = new Backbone.Collection();

/**
* Modal Window
*/
var WPCarouselModalWindow = new wp.media.view.Modal({
  controller: {
    trigger: function () {

    }
  }
});

/**
* View
*/
var WPCarouselView = wp.Backbone.View.extend({
  /**
  * The Tag Name and Tag's Class(es)
  */
  id: 'wpcp_image-meta-edit',
  tagName: 'div',
  className: 'edit-attachment-frame mode-select hide-menu hide-router',

  /**
  * Template
  * - The template to load inside the above tagName element.
  */
  template: wp.template('wpcp_image-meta-editor'),

  /**
  * Events
  * - Functions to call when specific events occur
  */
  events: {
    'click .edit-media-header .left': 'loadPreviousItem',
    'click .edit-media-header .right': 'loadNextItem',
    'keyup input': 'updateItem',
    'keyup textarea': 'updateItem',
    'change input': 'updateItem',
    'change textarea': 'updateItem',
    'keyup .CodeMirror': 'updateCode',
    'blur textarea': 'updateItem',
    'change select': 'updateItem',
    'click a.wpcp_image-meta-submit': 'saveItem',
    'keyup input#link-search': 'searchLinks',
    'click div.query-results li': 'insertLink',

    'click a.wpcp_image-thumbnail': 'insertThumb',
    'click a.wpcp_image-thumbnail-delete': 'removeThumb',

    'click button.media-file': 'insertMediaFileLinks',
    'click button.attachment-page': 'insertAttachmentPageLink',
  },
  /**
  * Initialize
  *
  * @param object model WPCarouselImage Backbone Model
  */
  initialize: function (args) {

    // Set some flags
    this.is_loading = false;
    this.collection = args.collection;
    this.child_views = args.child_views;
    this.attachment_id = args.attachment_id;
    this.attachment_index = 0;
    this.search_timer = '';

    // Get the model from the collection
    var count = 0;
    this.collection.each(function (model) {

      // If this model's id matches the attachment id, this is the model we want, also make sure both are int
      if (String(model.get('id')) == String(this.attachment_id)) {
        this.model = model;
        this.attachment_index = count;

        return false;
      }

      // Increment the index count
      count++;
    }, this);

  },
  updateCode: function (e) {

    $model = this.model;

    $textarea = this.$el.find('.wpcp_image-html-slide-code');

    $model.set('code', this.editor.getValue(), { silent: true });

    $textarea.text();

  },
  insertThumb: function (e) {
    $model = this.model;
    e.preventDefault();
    // Get input field class name
    var fieldClassName = this.$el.data('field');
    var wpcp_image_media_frame = wp.media.frames.wpcp_image_media_frame = wp.media({
      className: 'media-frame wpcp_image-media-frame',
      frame: 'select',
      multiple: false,
      title: wpcf_metabox_local.videoframe,
      library: {
        type: 'image'
      },
      button: {
        text: wpcf_metabox_local.videouse
      }
    });

    wpcp_image_media_frame.on('select', function () {
      // Grab our attachment selection and construct a JSON representation of the model.
      var thumbnail = wpcp_image_media_frame.state().get('selection').first().toJSON();

      $model.set('src', thumbnail.url, { silent: true });
      jQuery('div.thumbnail > img', $parent.find('.media-frame-content')).attr('src', thumbnail.url);

    });

    // Now that everything has been set, let's open up the frame.
    wpcp_image_media_frame.open();
  },
  removeThumb: function (e) {
    e.preventDefault();
    $model = this.model;
    $parent = this.$el.parent();

    jQuery('div.thumbnail > img', $parent.find('.media-frame-content')).attr('src', '');

    $model.set('src', '', { silent: true });

  },
  /**
  * Render
  * - Binds the model to the view, so we populate the view's fields and data
  */
  render: function () {
    // Get HTML
    if (this.model) {
      this.$el.html(this.template(this.model.attributes))
    } else {
      return false;
    }
    // If any child views exist, render them now.
    if (this.child_views.length > 0) {
      this.child_views.forEach(function (view) {
        // Init with model.
        var child_view = new view({
          model: this.model
        });
        // Render view within our main view.
        this.$el.find('div.addons').append(child_view.render().el);
      }, this);
    }

    // Set caption
    this.$el.find('textarea[name=caption]').val(this.model.get('caption'));
    this.$el.find('textarea[name=description]').val(this.model.get('description'));
    this.$el.find('select[name=crop_position]').val(this.model.get('crop_position'));
    var $current_element = this;
    // Change tab class and display content
    this.$el.find('.wpcp_tabs-nav a').on('click', function (event) {
      event.preventDefault();
      $current_element.$el.find('.tab-active').removeClass('tab-active');
      jQuery(this).parent().addClass('tab-active');
      $current_element.$el.find('.wpcp_tabs-stage > div').hide();
      jQuery(jQuery(this).attr('href')).show();
    });

    jQuery('.wpcp_tabs-nav a:first').trigger('click'); // Default.

    // Init QuickTags on the caption editor.
    // Delay is required for the first load for some reason.
    setTimeout(function () {
      quicktags({
        id: 'caption',
        buttons: 'strong,em,link,ul,ol,li,close'
      });
      quicktags({
        id: 'description',
        buttons: 'strong,em,link,ul,ol,li,close'
      });
      QTags._buttonsInit();
    }, 100);

    // Init Link Searching.
    wpLink.init;

    // Enable / disable the buttons depending on the index.
    if (this.attachment_index === 0) {
      // Disable left button.
      this.$el.find('button.left').addClass('disabled');
    }
    if (this.attachment_index == (this.collection.length - 1)) {
      // Disable right button.
      this.$el.find('button.right').addClass('disabled');
    }
    textarea = this.$el.find('.wpcp_image-html-slide-code');
    if (textarea.length) {
      this.editor = CodeMirror.fromTextArea(textarea[0], {
        enterMode: 'keep',
        indentUnit: 4,
        electricChars: false,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        mode: 'php',
        smartIndent: false,
        tabMode: 'shift',
        theme: 'ttcn'
      });
    }
    this.$el.trigger('wpcp_imageRenderMeta');
    this.$el.trigger('insertMediaFileLinks');
    // Return
    return this;

  },

  /**
  * Tells the view we're loading by displaying a spinner
  */
  loading: function () {

    // Set a flag so we know we're loading data
    this.is_loading = true;

    // Show the spinner
    this.$el.find('.spinner').css('visibility', 'visible');
  },

  /**
  * Hides the loading spinner
  */
  loaded: function (response) {

    // Set a flag so we know we're not loading anything now
    this.is_loading = false;

    // Hide the spinner
    this.$el.find('.spinner').css('visibility', 'hidden');

    // Display the error message, if it's provided
    if (typeof response !== 'undefined') {
      alert(response);
    }

  },

  /**
  * Load the previous model in the collection
  */
  loadPreviousItem: function () {
    // Decrement the index.
    this.attachment_index--;
    if (this.attachment_index < 0) {
      this.attachment_index = this.collection.length - 1;
    }
    // Get the model at the new index from the collection.
    this.model = this.collection.at(this.attachment_index);
    // Update the attachment_id.
    this.attachment_id = this.model.get('id');
    // Re-render the view.
    this.render();
  },

  /**
  * Load the next model in the collection
  */
  loadNextItem: function () {

    // Increment the index.
    this.attachment_index++;

    // Get the model at the new index from the collection.
    if (this.attachment_index > this.collection.length - 1) {
      this.attachment_index = 0;
    }

    this.model = this.collection.at(this.attachment_index);

    // Update the attachment_id.
    this.attachment_id = this.model.get('id');

    // Re-render the view.
    this.render();
  },

  /**
  * Updates the model based on the changed view data.
  */
  updateItem: function (event) {

    // Check if the target has a name. If not, it's not a model value we want to store.
    if (event.target.name == '') {
      return;
    }

    // Update the model's value, depending on the input type.
    if (event.target.type == 'checkbox') {
      value = (event.target.checked ? 1 : 0);
    } else {
      value = event.target.value;
    }

    // Update the model.
    this.model.set(event.target.name, value);

  },

  /**
  * Saves the image metadata
  */
  saveItem: function (event) {

    event.preventDefault();

    // Tell the View we're loading
    this.trigger('loading');
    // Make an AJAX request to save the image metadata
    wp.media.ajax('wpcf_image_save_meta', {
      context: this,
      data: {
        nonce: wpcf_metabox_local.save_nonce,
        post_id: wpcf_metabox_local.id,
        attach_id: this.model.get('id'),
        meta: this.model.attributes,
      },

      success: function (response) {

        // Tell the view we've finished successfully
        this.trigger('loaded loaded:success');

        // Assign the model's JSON string back to the underlying item
        var item = JSON.stringify(this.model.attributes);
        jQuery('ul#wpcp_image-output li#' + this.model.get('id')).attr('data-wpcp_image-model', item);
        // Show the user the 'saved' notice for 1.5 seconds
        var saved = this.$el.find('.saved');
        saved.fadeIn();
        setTimeout(function () {
          saved.fadeOut();
        }, 1500);

      },
      error: function (error_message) {

        // Tell wp.media we've finished, but there was an error
        this.trigger('loaded loaded:error', error_message);

      }
    });

  },

  /**
  * Searches Links
  */
  searchLinks: function (event) { },

  /**
  * Inserts the clicked link into the URL field
  */
  insertLink: function (event) { },

  /**
  * Inserts the direct media link for the Media Library item
  *
  * The button triggering this event is only displayed if we are editing a
  * Media Library item, so there's no need to perform further checks
  */
  insertMediaFileLinks: function (event) {

    // Tell the View we're loading
    this.trigger('loading');
    // Make an AJAX request to get the media link
    wp.media.ajax('wpcp_image_get_attachment_links', {
      context: this,
      data: {
        nonce: wpcf_metabox_local.save_nonce,
        attachment_id: this.model.get('id'),
      },
      success: function (response) {

        // Update model
        this.model.set('wpcplink', response.media_link);
        // Tell the view we've finished successfully.
        this.trigger('loaded loaded:success');

        // Re-render the view.
        this.render();

      },
      error: function (error_message) {

        // Tell wp.media we've finished, but there was an error.
        this.trigger('loaded loaded:error', error_message);

      }
    });

  },

  /**
  * Inserts the attachment page link for the Media Library item
  *
  * The button triggering this event is only displayed if we are editing a
  * Media Library item, so there's no need to perform further checks
  */
  insertAttachmentPageLink: function (event) {

    // Tell the View we're loading
    this.trigger('loading');

    // Make an AJAX request to get the media link
    wp.media.ajax('wpcp_image_get_attachment_links', {
      context: this,
      data: {
        nonce: wpcf_metabox_local.save_nonce,
        attachment_id: this.model.get('id'),
      },
      success: function (response) {

        // Update model
        this.model.set('wpcplink', response.attachment_page);

        // Tell the view we've finished successfully
        this.trigger('loaded loaded:success');

        // Re-render the view
        this.render();

      },
      error: function (error_message) {

        // Tell wp.media we've finished, but there was an error
        this.trigger('loaded loaded:error', error_message);

      }
    });

  }

});

/**
* Sub Views
* - Addons must populate this array with their own Backbone Views, which will be appended
* to the settings region
*/
var WPCarouselChildViews = [];
var WPCarouselContentViews = [];

/**
* DOM
*/
; (function ($) {

  $(document).ready(function () {

    wpcp_image_edit = {

      init: function () {

        // Populate the collection
        WPCarouselSlidesUpdate();

        // Edit Image
        $(document).on('click.wpcp_imageModify', '.edit-attachment-modify', function (e) {
          // Prevent default action
          e.preventDefault();
          // Get the selected attachment
          var attachment_id = $(this).data('id');

          // Pass the collection of images for this gallery to the modal view, as well
          // as the selected attachment
          WPCarouselModalWindow.content(new WPCarouselView({
            collection: WPCarouselSlides,
            child_views: WPCarouselChildViews,
            attachment_id: attachment_id,
          }));

          // Open the modal window.
          WPCarouselModalWindow.open();

          $(document).trigger('wpcp_imageEditOpen');

          $('.CodeMirror').each(function (i, el) {
            el.CodeMirror.refresh();
          });

        });

      }
    };

    wpcp_image_edit.init();

  });

  $(document).on('wpcp_imageUploaded', function () {
    wpcp_image_edit.init();
  });
  /**
  * Populates the WPCarouselSlides Backbone collection
  *
  * Called when images are added, deleted or reordered
  * Doesn't need to be called when an image is edited, as the model will be updated automatically in the collection
  */
  function WPCarouselSlidesUpdate(selected) {

    // Clear the collection
    WPCarouselSlides.reset();

    // var $items = 'ul#wpcp_image-output li.wpcp_image-slide' + (selected ? '.selected' : '');
    var $items = '.edit-attachment-modify';

    // Iterate through the gallery images in the DOM, adding them to the collection
    jQuery($items).each(function () {
      // Build an WPCarouselImage Backbone Model from the JSON supplied in the element
      var wpcp_image_slide = jQuery.parseJSON(jQuery(this).attr('data-wpcp_image-model'));

      // Add the model to the collection
      WPCarouselSlides.add(new WPCarouselSlide(wpcp_image_slide));

    });

  }
  if (typeof ClipboardJS !== 'undefined') {
    var copyAttachmentURLClipboard = new ClipboardJS('.copy-attachment-url'),
      copyAttachmentURLSuccessTimeout;
    /**
     * Handles media list copy media URL button.
     *
     * @param {MouseEvent} event A click event.
     * @return {void}
     */
    copyAttachmentURLClipboard.on('success', function (event) {
      var triggerElement = $(event.trigger),
        successElement = $('.success', triggerElement.closest('.copy-to-clipboard-container'));

      // Clear the selection and move focus back to the trigger.
      event.clearSelection();

      // Show success visual feedback.
      clearTimeout(copyAttachmentURLSuccessTimeout);
      successElement.removeClass('hidden');

      // Hide success visual feedback after 3 seconds since last success and unfocus the trigger.
      copyAttachmentURLSuccessTimeout = setTimeout(function () {
        successElement.addClass('hidden');
      }, 3000);

      // Handle success audible feedback.
      wp.a11y.speak(wp.i18n.__('The file URL has been copied to your clipboard'));
    });
  }
})(jQuery);