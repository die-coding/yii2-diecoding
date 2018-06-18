/**
 * @Author: Die Coding <www.diecoding.com>
 * @Date:   10 March 2018
 * @Email:  diecoding@gmail.com
 * @Last modified by:   Die Coding <www.diecoding.com>
 * @Last modified time: 11 June 2018
 * @License: MIT
 * @Copyright: 2018
 */

var _items = <?= $items ?>

$('#parent_name').autocomplete({
  source: function(request, response) {
    var result = [];
    var limit  = 10;
    var term   = request.term.toLowerCase();
    $.each(_items.menus, function() {
      var menu = this;
      if (term == '' || menu.name.toLowerCase().indexOf(term) >= 0 || (menu.parent_name && menu.parent_name.toLowerCase().indexOf(term) >= 0) || (menu.route && menu.route.toLowerCase().indexOf(term) >= 0)) {
        result.push(menu);
        limit--;
        if (limit <= 0) {
          return false;
        }
      }
    });
    response(result);
  },
  focus: function(event, ui) {
    $('#parent_name').val(ui.item.name);
    return false;
  },
  select: function(event, ui) {
    $('#parent_name').val(ui.item.name);
    $('#parent_id').val(ui.item.id);
    return false;
  },
  search: function() {
    $('#parent_id').val('');
  }
}).autocomplete("instance")._renderItem = function(ul, item) {
  return $("<li>").append($('<div>').text(item.id + ' → ' + item.name)).appendTo(ul);
};

$('#route').autocomplete({source: _items.routes});
