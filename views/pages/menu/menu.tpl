<?php $this->layout('layouts/dashboard', ['title' => 'Menu']);?>

<!-- Button -->
<div class="btn-control">
  <button id="create" class="btn btn-primary">New menu</button>
  <button id="delete" class="btn btn-danger">Delete menu</button>
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
      <th>Status</th>
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
      processing: true,
      serverSide: false,
      deferRender: true,
      searching: true,
      responsive: true,
      modeSelect: "single",
      ajax: "/api/v1/menu",
      columns: [
        { data: "id"},
        { data: "menu_link"},
        { data: "menu_name"},
        { data: "menu_position"},
        { data: "menu_parent"},
        { data: "menu_order"},
        { data: 'menu_status'}
      ],
      columnDefs: [
        {
          render: function(data, type, row) {
            var t = ['Active', 'Deactived'];
            if (data === 1) {
              return setLabelColor(t[0], 'success');
            } else {
              return setLabelColor(t[1], 'danger');
            }
          },
          targets: 6
        }
      ]
    });

    editableGrid('#grid_menu', {
      onUpdate: function (updatedCell, updatedRow, oldValue) {

        var rowdata = updatedRow.data()
        
        call_ajax('post', '/api/v1/menu/edit', {
          id: rowdata.id,
          menu_link: rowdata.menu_link,
          menu_name: rowdata.menu_name,
          menu_position: rowdata.menu_position,
          menu_parent: rowdata.menu_parent,
          menu_order: rowdata.menu_order,
          menu_status: rowdata.menu_status
        }).done(function(data) {
          if (data.result === false) {
            alert(data.message);
          }
          reloadGrid('#grid_menu');
        });
      },
      columns: [1, 2, 3, 4, 5, 6],
      inputCss:'form-control',
      confirmationButton: {
        confirmCss: 'btn btn-sm btn-success',
        cancelCss: 'btn btn-sm btn-danger'
      },
      inputTypes: [
        {
          column: 6, 
          type: "list",
          options: [
            { value: 1, display: "Actived" },
            { value: 0, display: "Deactived" }
          ]
        }
      ]
    });

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
        var rowdata = rowSelected('#grid_menu')[0];
        if (rowdata.length !== 0) {
          call_ajax('post', '/api/v1/menu/delete', {
            id: rowdata.id
          }).done(function (data) {
            if (data.result === true) {
              reloadGrid('#grid_menu');
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