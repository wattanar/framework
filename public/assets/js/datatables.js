// @ts-nocheck
function loadGrid(selector, options) {
  $(selector).DataTable(options);
}

function singleSelect(selector) {
  $(selector + ' tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
    } else {
      $(selector).DataTable().$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
}

function multipleSelect(selector) {
  $(selector + ' tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('selected');
  });
}

function clearSelected(selector) {
  $(selector + ' tbody tr').removeClass('selected');
}

function rowSelected(selector) {
  var data = [];
  var row_selected = $(selector).DataTable().rows('.selected').data();
  $.each(row_selected, function (i, v) {
    data.push(v);
  });
  return data;
}

function reloadGrid(selector) {
  $(selector).DataTable().ajax.reload( null, false );
}

function rowDoubleClick(selector, el) {
  return $(selector).DataTable().rows(el).data()[0];
}

function getInput(data, type, name) {
  var el = '';

  switch (type) {
    case 'text':
      el = '<input type='+type+' name="'+name+'" value="'+data+'" />';
      break;
    
    case 'textarea':
      el = '<textarea name="'+name+'">'+data+'</textarea>';
      break;
  
    default:
      el = '<input type='+type+' name="'+name+'" value="'+data+'" />';
      break;
  }

  return el;
}

function renderColumn(input, target) {
  return {
    "render": function ( data, type, row ) {
      if ( type === "sort" || type === "type" ) {
        return data;
      }
      return getInput(data, input.type, input.name);
    },
    "targets": target
  }
}