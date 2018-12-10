// @ts-nocheck

function loadGrid(selector, options) {
  $(selector).DataTable(options);

  if ( typeof options.modeSelect !== 'undefined' ) {
    if ( options.modeSelect === 'single') {
      singleSelect(selector);
    } else if ( options.modeSelect === 'multiple' ) {
      multipleSelect(selector);
    }
  }
}

function singleSelect(selector) {
  $(selector + ' tbody').on( 'click', 'tr', function () {
    if ( !$(this).hasClass('tb-selected') ) {
      $(selector).DataTable().$('tr.tb-selected').removeClass('tb-selected');
      $(this).addClass('tb-selected');
    }
  });
}

function multipleSelect(selector) {
  $(selector + ' tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('tb-selected');
  });
}

function clearSelected(selector) {
  $(selector + ' tbody tr').removeClass('tb-selected');
}

function rowSelected(selector) {
  var data = [];
  var row_selected = $(selector).DataTable().rows('.tb-selected').data();
  $.each(row_selected, function (i, v) {
    data.push(v);
  });
  return data;
}

function reloadGrid(selector) {
  $(selector).DataTable().ajax.reload(null, false);
}

function rowDoubleClick(selector, el) {
  return $(selector).DataTable().rows(el).data()[0];
}

function editableGrid(selector, options) {
  $(selector).DataTable().MakeCellsEditable(options);
}

function setLabelColor(data, color) {
  return "<span class='label label-"+color+"'>"+data+"</span>";
}