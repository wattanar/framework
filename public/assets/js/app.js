function __http(__type, __url, __data = {}, __datatype = 'json') {
  // @ts-ignore
  return $.ajax({
    type: __type,
    url: __url,
    data: __data,
    dataType: __datatype,
    cache: false
  });
}

function row_selected(grid_name) {
  // @ts-ignore
  var selectedrowindex = $(grid_name).jqxGrid('getselectedrowindex');
  // @ts-ignore
  var datarow = $(grid_name).jqxGrid('getrowdata', selectedrowindex);
  return datarow;
}
