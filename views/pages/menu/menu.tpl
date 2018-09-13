<?php $this->layout('layouts/default', ['title' => 'Menu']);?>

<div>
  <legend>Menu</legend>
  <p>
    <button id="create" class="btn btn-primary">New menu</button>
    <button id="delete" class="btn btn-danger">Delete menu</button>
  </p>
  <p>
    <div id="grid"></div>
  </p>
</div>

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

    grid();

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
                $('#grid').jqxGrid('updatebounddata');
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
        { text: 'Status', datafield: 'menu_status', width: 100, columntype: 'checkbox', filtertype: 'bool' }
      ]
    });
  }

</script>
<?php $this->end() ?>