<?php $this->layout('layouts/dashboard', ['title' => 'Roles']);?>

<div>
  <p>
    <button class="btn btn-primary" id="new_roles">New role</button>
    <button class="btn btn-danger" id="delete_roles">Delete role</button>
  </p>
  <p>
    <div id="grid"></div>
  </p>

  <div id="dialog_create_new" title="New role" style="display: none;">
    <form id="formNewRoles">
      <div class="form-group">
        <label for="role_name">Role name</label>
        <input type="text" name="role_name" id="role_name" class="form-control" autofocus>
      </div>
    </form>
  </div>

  <!-- reset password -->
  <div id="dialog_update_cap" title="Update Capabilities" style="display: none;">
    <form id="form_update_cap">
      <div class="form-group">
        <label for="update_capabilities">Capabilities</label>
        <select name="update_capabilities[]" id="update_capabilities" multiple="multiple" class="form-control">
        </select>
      </div>
    </form>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>

  jQuery(document).ready(function ($) {

    grid();

    $('#new_roles').on('click', function () {

      $('#formNewRoles').trigger('reset');

      $('#dialog_create_new').dialog({
        modal: true,
        resizable: false,
        height: "auto",
        width: 400,
        buttons: {
          "Submit": function () {
            __http('post', '/api/v1/roles/create', {
              role_name: $('#role_name').val(),
            }).done(function (data) {
              if (data.result === true) {
                $('#dialog_create_new').dialog('close');
                $('#formNewRoles').trigger('reset');
                $('#grid').jqxGrid('updatebounddata');
              } else {
                $('#formNewRoles').trigger('reset');
                alert(data.message);
              }
            });
          }
        }
      });
    });

    $('#delete_roles').on('click', function () {
      var rowdata = row_selected('#grid');
      if (typeof rowdata !== 'undefined') {
        __http('post', '/api/v1/roles/delete', {
          role_id: rowdata.id
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
    });
  });

  function updateCapabilities() {

    var rowdata = row_selected('#grid');

    $('#dialog_update_cap').dialog({
      modal: true,
      resizable: false,
      height: "auto",
      width: 400,
      buttons: {
        "Submit": function () {
          __http('post', '/api/v1/roles/update_capabilities', {
            role_id: rowdata.id,
            cap_id: $('#update_capabilities').val(),
          }).done(function (data) {
            if (data.result === true) {
              $('#dialog_update_cap').dialog('close');
              $('#form_update_cap').trigger('reset');
              $('#grid').jqxGrid('updatebounddata');
            } else {
              $('#form_update_cap').trigger('reset');
              alert(data.message);
            }
          });
        }
      }
    });

    __http('get', '/api/v1/roles/capabilities_by_role/' + rowdata.id)
      .done(function (data) {
        $('#update_capabilities').html("");
        $.each(data, function (i, v) {
          if (v.selected === 1) {
            $('#update_capabilities')
              .append(new Option(v.cap_name, v.cap_id, false, true))
              .trigger('change');
          } else {
            $('#update_capabilities')
              .append(new Option(v.cap_name, v.cap_id, false, false))
              .trigger('change');
          }

        });
        $('#update_capabilities').select2();
      });
  }

  function grid() {
    var dataAdapter = new $.jqx.dataAdapter({
      datatype: 'json',
      datafields: [
        { name: 'id', type: 'number' },
        { name: 'role_name', type: 'string' },
        { name: 'role_status', type: 'bool' }
      ],
      url: '/api/v1/roles',
      updaterow: function (rowid, rowdata, commit) {
        __http('post', '/api/v1/roles/edit', {
          id: rowdata.id,
          name: rowdata.role_name,
          status: rowdata.role_status
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
        { text: 'Name', datafield: 'role_name', width: 300 },
        {
          text: 'Capabilities', cellsalign: 'center', cellsrenderer: function (row, column, value) {
            return "<div class='inner-grid'><button class='btn-inner-grid' onclick='return updateCapabilities()'> Show Detail </button></div>";
          }, width: 130, editable: false
        },
        { text: 'Status', datafield: 'role_status', columntype: 'checkbox', filtertype: 'bool', width: 100 }
      ]
    });
  }
</script>
<?php $this->end() ?>