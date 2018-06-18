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
function updateItems(r) {
  _items.items.available = r.available;
  _items.items.assigned  = r.assigned;
  search('available');
  search('assigned');
}

$('.btn-assign').click(function() {
  var $this  = $(this);
  var target = $this.data('target');
  var items  = $('select.list[data-target="' + target + '"]').val();

  if (items && items.length) {
    $this.children('i.icon-refresh-animate').show();
    $this.children('i.refresh').hide();
    $.post($this.attr('href'), {
      items: items
    }, function(r) {
      updateItems(r);
    }).always(function() {
      $this.children('i.icon-refresh-animate').hide();
      $this.children('i.refresh').show();
    });
  }
  return false;
});

$('.search[data-target]').keyup(function() {
  search($(this).data('target'));
});

function search(target) {
  var $list = $('select.list[data-target="' + target + '"]');
  $list.html('');
  var q = $('.search[data-target="' + target + '"]').val().toLowerCase();

  var groups = {
    role: [$('<optgroup label="<?= Yii::t('diecoding', 'Roles') ?>">'), false],
    permission: [$('<optgroup label="<?= Yii::t('diecoding', 'Permissions') ?>">'), false],
    route: [$('<optgroup label="<?= Yii::t('diecoding', 'Routes') ?>">'), false]
  };
  $.each(_items.items[target], function(name, group) {
    if (name.toLowerCase().indexOf(q) >= 0) {
      $('<option>').text(name).val(name).appendTo(groups[group][0]);
      groups[group][1] = true;
    }
  });
  $.each(groups, function() {
    if (this[1]) {
      $list.append(this[0]);
    }
  });
}

search('available');
search('assigned');
