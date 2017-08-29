$(document).ready(function(){function e(e){$(e).block({message:"",css:{border:"none",padding:"0px",margin:"-20px 0 0 0",width:"100%",height:"100%",backgroundColor:"transparent"},overlayCSS:{backgroundColor:"#fff",opacity:.6,cursor:"wait"}})}function a(e){$(e).unblock()}if(1===$(".material-design-hamburger__icon").length&&document.querySelector(".material-design-hamburger__icon").addEventListener("click",function(){var e;document.body.classList.toggle("background--blur"),this.parentNode.nextElementSibling.classList.toggle("menu--on"),e=this.childNodes[1].classList,e.contains("material-design-hamburger__icon--to-arrow")?(e.remove("material-design-hamburger__icon--to-arrow"),e.add("material-design-hamburger__icon--from-arrow")):(e.remove("material-design-hamburger__icon--from-arrow"),e.add("material-design-hamburger__icon--to-arrow"))}),$(".fixed-sidebar .navigation-toggle a").removeClass("button-collapse"),$(".fixed-sidebar .navigation-toggle a").addClass("reverse-icon"),$(".fixed-sidebar .navigation-toggle a").click(function(){$("#slide-out").toggle(),$(".mn-inner").toggleClass("hidden-fixed-sidebar"),$(".mn-content").toggleClass("fixed-sidebar-on-hidden"),$(document).trigger("fixedSidebarClick")}),$(window).width()<993&&!$(".mn-content").hasClass("fixed-sidebar-on-hidden")&&($(".fixed-sidebar .navigation-toggle a").click(),$(".fixed-sidebar .navigation-toggle a span").addClass("material-design-hamburger__icon--to-arrow")),$(".sidebar-menu > li > a.collapsible-header").click(function(){$(".sidebar-menu > li > a.active:not(.collapsible-header)").parent().removeClass("active"),$(".sidebar-menu > li > a.active:not(.collapsible-header)").removeClass("active")}),$(".search-toggle").click(function(){$(".search").removeClass("hide-on-small-and-down"),$(".search input").focus()}),$(".close-search").click(function(){$(".search").addClass("hide-on-small-and-down")}),$(".search-results").hide(),!$("body").hasClass("quick-results-off")){$(document).mouseup(function(e){var a=$(".search-results"),t=$(".search input#search");a.is(e.target)||t.is(e.target)||0!==a.has(e.target).length||0!==t.has(e.target).length||a.fadeOut(300)}),$(document).keyup(function(e){27==e.keyCode&&($(".search-results").fadeOut(300),$(".search input#search").blur())}),$(".search input#search").focus(function(){0!=$.trim($(".search input#search").val()).length&&$(".search-results").fadeIn(300)}),$(".search input#search").keypress(function(){$(".search-results").fadeIn(300)}),$(".search-result-container").fadeOut();var t=function(){var e=0;return function(a,t){clearTimeout(e),e=setTimeout(a,t)}}();$(".search-result-container").fadeOut(1),$(".res-not-found").fadeOut(1),$(".search input#search").on("input",function(){$(".search-result-container").fadeOut(1),$(".res-not-found").fadeOut(1),t(function(){0!=!$.trim($(".search input#search").val()).length?($(".search-result-container").fadeOut(1),$(".res-not-found").fadeIn(1)):$(".search-result-container").fadeIn()},500),$(".search-text").text(this.value)})}$(".sidebar-account-settings:not(.show)").fadeOut(0),$(".account-settings-link").click(function(){$(".sidebar-account-settings").hasClass("show")?($(".sidebar-account-settings").fadeOut(0),$(".sidebar-menu").fadeIn(300),$(".sidebar-account-settings").removeClass("show")):($(".sidebar-account-settings").fadeIn(300),$(".sidebar-menu").fadeOut(0),$(".sidebar-account-settings").addClass("show"))}),$(".dropdown-right").dropdown({alignment:"right"}),$(".button-collapse:not(.right-sidebar-button)").sideNav(),$(".button-collapse.right-sidebar-button").sideNav({edge:"right"}),$(".chat-button").sideNav({edge:"right"}),$(".chat-message-link").sideNav({menuWidth:320,edge:"right"}),$(".chat-message").click(function(){$(".chat-message-link").click()}),$(".collapsible").collapsible(),$(".slider").slider({full_width:!0}),$(".left-sidebar-hover").mouseover(function(){$(".button-collapse").click(),$(".material-design-hamburger__layer").removeClass("material-design-hamburger__icon--from-arrow"),$(".material-design-hamburger__layer").addClass("material-design-hamburger__icon--to-arrow"),$("#slide-out").addClass("openOnHover"),$("#slide-out").mouseleave(function(){$(this).hasClass("openOnHover")&&($(".button-collapse").click(),$(".material-design-hamburger__layer").addClass("material-design-hamburger__icon--from-arrow"),$(".material-design-hamburger__layer").removeClass("material-design-hamburger__icon--to-arrow"),$("#slide-out").removeClass("openOnHover"))})}),$(".modal-trigger").leanModal(),$("select").material_select(),preloader=new $.materialPreloader({position:"top",height:"5px",col_1:"#159756",col_2:"#da4733",col_3:"#3b78e7",col_4:"#fdba2c",fadeIn:200,fadeOut:200}),preloader.on(),$(window).load(function(){preloader.off()}),$(".card-refresh").click(function(){var t=$(this).closest(".card");e(t),window.setTimeout(function(){a(t)},1e3)}),$(".card-remove").click(function(){$(this).closest(".card").fadeOut(300)}),window.onload=function(){setTimeout(function(){$("body").addClass("loaded")},1e3),setTimeout(function(){$(".loader").fadeOut("400")},600)},$("input.expand-search").click(function(){$(this).addClass("open-search")}),$("input.expand-search").blur(function(){$(this).removeClass("open-search")})});
;(function ($) {

  var methods = {
    init: function (options) {
      return this.each(function () {
        var origin = $('#' + $(this).attr('data-activates'));
        var screen = $('body');

        // Creating tap target
        var tapTargetEl = $(this);
        var tapTargetWrapper = tapTargetEl.parent('.tap-target-wrapper');
        var tapTargetWave = tapTargetWrapper.find('.tap-target-wave');
        var tapTargetOriginEl = tapTargetWrapper.find('.tap-target-origin');
        var tapTargetContentEl = tapTargetEl.find('.tap-target-content');

        // Creating wrapper
        if (!tapTargetWrapper.length) {
          tapTargetWrapper = tapTargetEl.wrap($('<div class="tap-target-wrapper"></div>')).parent();
        }

        // Creating content
        if (!tapTargetContentEl.length) {
          tapTargetContentEl = $('<div class="tap-target-content"></div>');
          tapTargetEl.append(tapTargetContentEl);
        }

        // Creating foreground wave
        if (!tapTargetWave.length) {
          tapTargetWave = $('<div class="tap-target-wave"></div>');

          // Creating origin
          if (!tapTargetOriginEl.length) {
            tapTargetOriginEl = origin.clone(true, true);
            tapTargetOriginEl.addClass('tap-target-origin');
            tapTargetOriginEl.removeAttr('id');
            tapTargetOriginEl.removeAttr('style');
            tapTargetWave.append(tapTargetOriginEl);
          }

          tapTargetWrapper.append(tapTargetWave);
        }

        // Open
        var openTapTarget = function () {
          if (tapTargetWrapper.is('.open')) {
            return;
          }

          // Adding open class
          tapTargetWrapper.addClass('open');

          setTimeout(function () {
            tapTargetOriginEl.off('click.tapTarget').on('click.tapTarget', function (e) {
              closeTapTarget();
              tapTargetOriginEl.off('click.tapTarget');
            });

            $(document).off('click.tapTarget').on('click.tapTarget', function (e) {
              closeTapTarget();
              $(document).off('click.tapTarget');
            });

            var throttledCalc = Materialize.throttle(function () {
              calculateTapTarget();
            }, 200);
            $(window).off('resize.tapTarget').on('resize.tapTarget', throttledCalc);
          }, 0);
        };

        // Close
        var closeTapTarget = function () {
          if (!tapTargetWrapper.is('.open')) {
            return;
          }

          tapTargetWrapper.removeClass('open');
          tapTargetOriginEl.off('click.tapTarget');
          $(document).off('click.tapTarget');
          $(window).off('resize.tapTarget');
        };

        // Pre calculate
        var calculateTapTarget = function () {
          // Element or parent is fixed position?
          var isFixed = origin.css('position') === 'fixed';
          if (!isFixed) {
            var parents = origin.parents();
            for (var i = 0; i < parents.length; i++) {
              isFixed = $(parents[i]).css('position') == 'fixed';
              if (isFixed) {
                break;
              }
            }
          }

          // Calculating origin
          var originWidth = origin.outerWidth();
          var originHeight = origin.outerHeight();
          var originTop = isFixed ? origin.offset().top - $(document).scrollTop() : origin.offset().top;
          var originLeft = isFixed ? origin.offset().left - $(document).scrollLeft() : origin.offset().left;

          // Calculating screen
          var windowWidth = $(window).width();
          var windowHeight = $(window).height();
          var centerX = windowWidth / 2;
          var centerY = windowHeight / 2;
          var isLeft = originLeft <= centerX;
          var isRight = originLeft > centerX;
          var isTop = originTop <= centerY;
          var isBottom = originTop > centerY;
          var isCenterX = originLeft >= windowWidth * 0.25 && originLeft <= windowWidth * 0.75;
          var isCenterY = originTop >= windowHeight * 0.25 && originTop <= windowHeight * 0.75;

          // Calculating tap target
          var tapTargetWidth = tapTargetEl.outerWidth();
          var tapTargetHeight = tapTargetEl.outerHeight();
          var tapTargetTop = originTop + originHeight / 2 - tapTargetHeight / 2;
          var tapTargetLeft = originLeft + originWidth / 2 - tapTargetWidth / 2;
          var tapTargetPosition = isFixed ? 'fixed' : 'absolute';

          // Calculating content
          var tapTargetTextWidth = isCenterX ? tapTargetWidth : tapTargetWidth / 2 + originWidth;
          var tapTargetTextHeight = tapTargetHeight / 2;
          var tapTargetTextTop = isTop ? tapTargetHeight / 2 : 0;
          var tapTargetTextBottom = 0;
          var tapTargetTextLeft = isLeft && !isCenterX ? tapTargetWidth / 2 - originWidth : 0;
          var tapTargetTextRight = 0;
          var tapTargetTextPadding = originWidth;
          var tapTargetTextAlign = isBottom ? 'bottom' : 'top';

          // Calculating wave
          var tapTargetWaveWidth = originWidth > originHeight ? originWidth * 2 : originWidth * 2;
          var tapTargetWaveHeight = tapTargetWaveWidth;
          var tapTargetWaveTop = tapTargetHeight / 2 - tapTargetWaveHeight / 2;
          var tapTargetWaveLeft = tapTargetWidth / 2 - tapTargetWaveWidth / 2;

          // Setting tap target
          var tapTargetWrapperCssObj = {};
          tapTargetWrapperCssObj.top = isTop ? tapTargetTop : '';
          tapTargetWrapperCssObj.right = isRight ? windowWidth - tapTargetLeft - tapTargetWidth : '';
          tapTargetWrapperCssObj.bottom = isBottom ? windowHeight - tapTargetTop - tapTargetHeight : '';
          tapTargetWrapperCssObj.left = isLeft ? tapTargetLeft : '';
          tapTargetWrapperCssObj.position = tapTargetPosition;
          tapTargetWrapper.css(tapTargetWrapperCssObj);

          // Setting content
          tapTargetContentEl.css({
            width: tapTargetTextWidth,
            height: tapTargetTextHeight,
            top: tapTargetTextTop,
            right: tapTargetTextRight,
            bottom: tapTargetTextBottom,
            left: tapTargetTextLeft,
            padding: tapTargetTextPadding,
            verticalAlign: tapTargetTextAlign
          });

          // Setting wave
          tapTargetWave.css({
            top: tapTargetWaveTop,
            left: tapTargetWaveLeft,
            width: tapTargetWaveWidth,
            height: tapTargetWaveHeight
          });
        };

        if (options == 'open') {
          calculateTapTarget();
          openTapTarget();
        }

        if (options == 'close') closeTapTarget();
      });
    },
    open: function () {},
    close: function () {}
  };

  $.fn.tapTarget = function (methodOrOptions) {
    if (methods[methodOrOptions] || typeof methodOrOptions === 'object') return methods.init.apply(this, arguments);

    $.error('Method ' + methodOrOptions + ' does not exist on jQuery.tap-target');
  };
})(jQuery);

// Click on Notification
$('a[data-activates="dropdown1"]').click(function(){
    var _elem = $(this);
    
    // Отправка запроса на чтение
    $.post('/api/admin/readAllNotifications/', {}, function(dt){
        try{ // Попытка обработки
            var resp = JSON.parse(dt); // Парсинг JSON
            if(resp.complete){ // Все ок
                $('.new-notification').removeClass('new-notification');
                _elem.find('.badge').remove();
            }else{ // Не ок
                console.log("Failed to read notifications: "+resp.message);
            }
        }catch(e){ // Не удалось обработать ответ
            console.log("Failed to read notifications: Failed to convert JSON data: "+dt);
        }
    }).error(function(){ // Ошибка
        console.log("Failed to read notifications: Server Error");
    });
});