$('.no-js').removeClass('no-js').addClass('js');

svg4everybody();

var template_path = $('html').data('path');

// Cookie-Hinweis.
function cookiebar_open() {
  if (document.cookie.indexOf('cookiebar_closed=true') >= 0) {
    return false;
  }
  return true;
}

if (cookiebar_open()) {
  $('.cookies').show();
}

$('#cookie_info').click(function() {
  $('.cookies').hide();
  set_cookie('cookiebar_closed', 365);
});

$('#cookie_close').click(function(e) {
  e.preventDefault();
  $('.cookies').hide();
  set_cookie('cookiebar_closed', 365);
});

function set_cookie(name, days) {
  var date, expires;
  date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  expires = " expires=" + date.toGMTString();
  document.cookie = name + "=true; path=/;" + expires;
}


// Menü ein-/ausblenden.
$('.toggle').each(function() {

  var toggle_for = $('#' + $(this).data('for'));

  toggle_for.hide();

  $(this).click(function(e) {
    $(this).toggleClass('active');
    toggle_for.slideToggle();
  });
});

function count_parents() {
  return $('.current').parents('.active').length;
}


$('#main-menu li.has-sub > a').on('click', function() {
  $(this).removeAttr('href');
  var element = $(this).parent('li');
  if (element.hasClass('open')) {
    element.removeClass('open');
    $(this).children('.s-icon').replaceWith('<i class="s-icon"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' + template_path + '/img/icons.svg#arrow-right"></use></svg></i>');
    element.children('div').slideUp();
    element.children('ul').slideUp();
  } else { // Falls geschlossen.
    element.addClass('open');
    $(this).children('.s-icon').replaceWith('<i class="s-icon"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' + template_path + '/img/icons.svg#arrow-up"></use></svg></i>');

    element.siblings('li').removeClass('open');
    element.siblings('li').children('div').slideUp();
    element.siblings('li').children('ul').slideUp();
    
    element.children('div').slideDown();
    element.children('ul').slideDown();

    // element.siblings('li').children('li').find('div').slideUp();
    // element.siblings('li').children('li').find('ul').slideUp();

    // element.siblings('li').find('li').removeClass('open');
    // element.siblings('li').find('li').children('ul').slideUp();

  }
  $('#main-menu').find('.has-sub').not('.open').find('.s-icon').replaceWith('<i class="s-icon"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' + template_path + '/img/icons.svg#arrow-right"></use></svg></i>');
});

// $(document).ready(function() {
//   if (count_parents()==2||count_parents()==1) {
//     $('.current').parents('.sub-menu-container').show();
//     $('.current').parents('.has-sub').addClass('open');
//     $('.current').parents('.has-sub').find('.s-icon').replaceWith('<i class="s-icon"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' + template_path + '/img/icons.svg#arrow-right"></use></svg></i>');
//   }
// });

$('.refresh').change(function() {
    $("#events-filter").submit();
});
