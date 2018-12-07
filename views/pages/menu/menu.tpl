<?php $this->layout('layouts/dashboard', ['title' => 'Menu']);?>

<!-- Button -->
<div class="btn-control">
  <button id="create" class="btn btn-primary">New menu</button>
  <button id="delete" class="btn btn-danger">Delete menu</button>
  <button id="update" class="btn btn-default">Update</button>
</div>

<!-- Table -->
<table id="grid_menu" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Link</th>
      <th>Name</th>
      <th>Position</th>
      <th>Parent</th>
      <th>Order</th>
    </tr>
  </thead>
</table>

<!-- Dialog -->
<div id="dialog_create_new" title="New Menu" style="display: none;">
  <form id="formNewMenu">
    <div class="form-group">
      <label for="menu_link">Link</label>
      <input type="text" name="menu_link" id="menu_link" class="form-control" autofocus autocomplete="off" required>
    </div>
    <div class="form-group">
      <label for="menu_name">Name</label>
      <input type="text" name="menu_name" id="menu_name" class="form-control" autocomplete="off" required>
    </div>
  </form>
</div>

<div id="dialog_update_capabilities" title="Update Capabilities" style="display: none;">
  <form id="formUpdateCapabilities">
    <div class="form-group">
      <label for="capabilities">Capabilities</label>
      <select name="capabilities" id="capabilities" class="form-control"></select>
    </div>
  </form>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    loadGrid('#grid_menu', {
      "processing": true,
      "serverSide": false,
      "deferRender": true,
      "ajax": "/api/v1/menu",
      "columns": [
        { data: "id", width: "30px" },
        { data: "menu_link", width: "100px"},
        { data: "menu_name" },
        { data: "menu_position" },
        { data: "menu_parent" },
        { data: "menu_order" }
      ],
      "columnDefs": [
        renderColumn({
          type: 'text',
          name: 'menu_link'
        }, 1)
      ]
    });

    singleSelect('#grid_menu');

    $('#create').on('click', function () {
      $('#dialog_create_new').dialog({
        modal: true,
        resizable: false,
        height: "auto",
        width: 400,
        buttons: {
          "Submit": function () {
            __http('post', '/api/v1/menu/create', {
              menu_link: $('#menu_link').val(),
              menu_name: $('#menu_name').val(),
            }).done(function (data) {
              if (data.result === true) {
                $('#dialog_create_new').dialog('close');
                $('#formNewMenu').trigger('reset');
                reloadGrid('#grid_menu');
              } else {
                $('#formNewMenu').trigger('reset');
                alert(data.message);
              }
            });
          }
        }
      });
    });

    $('#delete').on('click', function () {
      if (confirm('Are you sure ?')) {
        var rowdata = row_selected('#grid');
        if (typeof rowdata !== 'undefined') {
          __http('post', '/api/v1/menu/delete', {
            id: rowdata.id
          }).done(function (data) {
            if (data.result === true) {
              $('#grid').jqxGrid('updatebounddata');
            } else {
              alert(data.message);
            }
          });
        } else {
          alert('Please select row!');
        }
      }
    });

    $('#update').on('click', function() {
      console.log(rowSelected('#grid_menu')[0]);
    });
  });

  function grid() {
    var dataAdapter = new $.jqx.dataAdapter({
      datatype: 'json',
      datafields: [
        { name: 'id', type: 'number' },
        { name: 'menu_link', type: 'string' },
        { name: 'menu_name', type: 'string' },
        { name: 'menu_position', type: 'number' },
        { name: 'menu_parent', type: 'number' },
        { name: 'menu_order', type: 'number' },
        { name: 'menu_status', type: 'bool' },
        { name: 'cap_id', type: 'number' },
        { name: 'cap_name', type: 'string' }
      ],
      url: '/api/v1/menu',
      updaterow: function (rowid, rowdata, commit) {
        __http('post', '/api/v1/menu/edit', {
          id: rowdata.id,
          menu_link: rowdata.menu_link,
          menu_name: rowdata.menu_name,
          menu_position: rowdata.menu_position,
          menu_parent: rowdata.menu_parent,
          menu_order: rowdata.menu_order,
          menu_status: rowdata.menu_status
        }).done(function (data) {
          if (data.result === true) {
            commit(true);
          } else {
            alert(data.message);
            commit(false);
          }
        });
      }
    });

    return $("#grid").jqxGrid({
      width: '100%',
      source: dataAdapter,
      autoheight: true,
      pageSize: 10,
      altrows: true,
      pageable: true,
      sortable: true,
      filterable: true,
      showfilterrow: true,
      columnsresize: true,
      editable: true,
      theme: 'default',
      columns: [
        { text: 'Menu ID', datafield: 'id', width: 100, editable: false },
        { text: 'Link', datafield: 'menu_link', width: 150 },
        { text: 'Name', datafield: 'menu_name', width: 200 },
        {
          text: 'Position', datafield: 'menu_position', width: 100, cellsalign: 'center',
          cellsrenderer: function (row, column, value) {
            return "<div class='inner-grid'>" + value + "</div>";
          }
        },
        { text: 'Parent', datafield: 'menu_parent', width: 100, cellsalign: 'center' },
        { text: 'Order', datafield: 'menu_order', width: 100, cellsalign: 'center' },
        {
          text: 'Capabilities', datafield: 'cap_name', cellsrenderer: function (row, column, value) {
            return "<div class='inner-grid'><button class='btn-inner-grid' onclick='return updateCapabilities()'> Update </button> " + value + "</div>";
          }, width: 200, editable: false
        },
        { text: 'Status', datafield: 'menu_status', width: 100, columntype: 'checkbox', filtertype: 'bool' }
      ]
    });
  }

  function updateCapabilities() {
    var rowdata = row_selected('#grid');
    $('#dialog_update_capabilities').dialog({
      modal: true,
      resizable: false,
      height: "auto",
      width: 400,
      buttons: {
        "Submit": function () {
          __http('post', '/api/v1/menu/update_capabilities', {
            menu_id: rowdata.id,
            cap_id: $('#capabilities').val(),
          }).done(function (data) {
            if (data.result === true) {
              $('#dialog_update_capabilities').dialog('close');
              $('#formUpdateCapabilities').trigger('reset');
              $('#grid').jqxGrid('updatebounddata');
            } else {
              $('#formUpdateCapabilities').trigger('reset');
              alert(data.message);
            }
          });
        }
      }
    });
    __http('get', '/api/v1/capabilities_active')
      .done(function (data) {
        $('#capabilities').html("");
        $('#capabilities').append('<option value="0">None</option>');
        $.each(data, function (i, v) {
          $('#capabilities')
            .append(new Option(v.cap_name, v.id, false, true))
            .trigger('change');
        });
        $('#capabilities').val(rowdata.cap_id).select2();
      });
  }

</script>
<?php $this->end() ?>