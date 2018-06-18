/**
 * @Author: Die Coding <www.diecoding.com>
 * @Date:   07 March 2018
 * @Email:  diecoding@gmail.com
 * @Last modified by:   Die Coding <www.diecoding.com>
 * @Last modified time: 12 March 2018
 * @License: MIT
 * @Copyright: 2018
 */

var _items = <?= $items ?>;

$('i.icon-refresh-animate').hide();
function updateRoutes(r) {
  _items.routes.available = r.available;
  _items.routes.assigned  = r.assigned;
  search('available');
  search('assigned');
}

$('#btn-new').click(function() {
  var $this = $(this);
  var route = $('#inp-route').val().trim();
  if (route != '') {
    $this.children('i.icon-refresh-animate').show();
    $this.children('i.refresh').hide();
    $.post($this.attr('href'), {
      route: route
    }, function(r) {
      $('#inp-route').val('').focus();
      updateRoutes(r);
    }).always(function () {
      $this.children('i.icon-refresh-animate').hide();
      $this.children('i.refresh').show();
    });
  }
  return false;
});

$('.btn-assign').click(function() {
  var $this  = $(this);
  var target = $this.data('target');
  var routes = $('select.list[data-target="' + target + '"]').val();

  if (routes && routes.length) {
    $this.children('i.icon-refresh-animate').show();
    $this.children('i.refresh').hide();
    $.post($this.attr('href'), {
      routes: routes
    }, function(r) {
      updateRoutes(r);
    }).always(function() {
      $this.children('i.icon-refresh-animate').hide();
      $this.children('i.refresh').show();
    });
  }
  return false;
});

$('#btn-refresh').click(function() {
  var $icon = $(this).children('i.glyphicon');
  $icon.addClass('icon-refresh-animate');
  $.post($(this).attr('href'), function(r) {
    updateRoutes(r);
  }).always(function () {
    $icon.removeClass('icon-refresh-animate');
  });
  return false;
});

$('.search[data-target]').keyup(function() {
  search($(this).data('target'));
});

function search(target) {
  var $list = $('select.list[data-target="' + target + '"]');
  $list.html('');
  var q = $('.search[data-target="' + target + '"]').val().toLowerCase();

  $.each(_items.routes[target], function() {
    var r = this;
    if (r.toLowerCase().indexOf(q) >= 0) {
      $('<option>').text(r).val(r).appendTo($list);
    }
  });
}

search('available');
search('assigned');
