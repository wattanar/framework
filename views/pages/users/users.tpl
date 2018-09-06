<?php $this->layout('layouts/default', ['title' => 'Users']);?>

<div>
  <legend>Users</legend>

  <p>
    <button class="btn btn-primary" id="new_user">Create new</button>
  </p>
  <p>
    <div id="grid"></div>
  </p>

  <div id="dialog_create_new" title="New user" style="display: none;">
    <form id="formNewUser">
      <div class="form-group">
        <label for="user_login">Username</label>
        <input type="text" name="user_login" id="user_login" class="form-control" autofocus autocomplete="off" required>
      </div>
      <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" name="user_password" id="user_password" class="form-control" required>
      </div>
    </form>
  </div>

  <!-- Edit role -->
  <div id="dialog_edit_role" title="Edit role" style="display: none;">
    <form id="formEditRole">
      <div class="form-group">
        <label for="select_role">Roles</label>
        <select name="select_role" id="select_role" class="form-control">
          <option value="">-- Select --</option>
        </select>
      </div>
    </form>
  </div>

  <!-- reset password -->
  <div id="dialog_reset_password" title="Reset Password" style="display: none;">
    <form id="formResetPassword">
      <div class="form-group">
        <label for="reset_password">New Password</label>
        <input type="password" name="reset_password" id="reset_password" class="form-control" required>
      </div>
    </form>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    grid();

    $('#new_user').on('click', function () {

      $('#formNewUser').trigger('reset');

      $('#dialog_create_new').dialog({
        modal: true,
        resizable: false,
        height: "auto",
        width: 400,
        buttons: {
          "Submit": function () {
            if (confirm('Are you sure ?')) {
              __http('post', '/api/v1/users/create', {
                user_login: $('#user_login').val(),
                user_password: $('#user_password').val(),
              }).done(function (data) {
                if (data.result === true) {
                  $('#dialog_create_new').dialog('close');
                  $('#formNewUser').trigger('reset');
                  $('#grid').jqxGrid('updatebounddata');
                } else {
                  $('#formNewUser').trigger('reset');
                  alert(data.message);
                }
              });
            }
          }
        }
      });
    });

    $('#user_login').keyup(function () {
      $('#user_login').val($('#user_login').val().toLowerCase().replace(/[\s-'.@#\\/+=*%&!$?)({}]/g, "_"));
    });
  });

  function grid() {
    var dataAdapter = new $.jqx.dataAdapter({
      datatype: 'json',
      datafields: [
        { name: 'id', type: 'number' },
        { name: 'user_login', type: 'string' },
        { name: 'user_email', type: 'string' },
        { name: 'user_registered', type: 'date' },
        { name: 'user_status', type: 'bool' },
        { name: 'user_firstname', type: 'string' },
        { name: 'user_lastname', type: 'string' },
        { name: 'role_id', type: 'number' },
        { name: 'user_role', type: 'string' }
      ],
      url: '/api/v1/users',
      updaterow: function (rowid, rowdata, commit) {

        __http('post', '/api/v1/users/edit', {
          id: rowdata.id,
          user_email: rowdata.user_email,
          user_status: rowdata.user_status,
          user_firstname: rowdata.user_firstname,
          user_lastname: rowdata.user_lastname
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
        { text: 'User Login', datafield: 'user_login', editable: false, width: 100 },
        { text: 'Email', datafield: 'user_email', width: 200 },
        { text: 'Register Date', datafield: 'user_registered', columntype: 'date', editable: false, filtertype: 'date', cellsformat: 'yyyy-MM-dd HH:mm:ss', width: 200 },
        { text: 'Status', datafield: 'user_status', columntype: 'checkbox', filtertype: 'bool', width: 100 },
        { text: 'Firstname', datafield: 'user_firstname', width: 150 },
        { text: 'Lastname', datafield: 'user_lastname', width: 150 },
        {
          text: 'Role', datafield: 'user_role', cellsrenderer: function (row, column, value) {
            return "<div class='inner-grid'><button class='btn-inner-grid' onclick='return editRole()'> Edit </button> " + value + "</div>";
          }, width: 200, editable: false
        },
        {
          text: 'Reset Password', cellsrenderer: function (row, column, value) {
            return "<div class='inner-grid'><button class='btn-inner-grid' onclick='return resetPassword()'> Reset Password </button></div>";
          }, width: 200, editable: false
        }
      ]
    });
  }

  function resetPassword() {
    $('#dialog_reset_password').dialog({
      modal: true,
      resizable: false,
      height: "auto",
      width: 400,
      buttons: {
        "Submit": function () {
          if (confirm('Are you sure ?')) {
            __http('post', '/api/v1/users/reset_password', {
              new_password: $('#reset_password').val()
            }).done(function (data) {
              if (data.result === true) {
                $('#dialog_reset_password').dialog('close');
                $('#formResetPassword').trigger('reset');
                $('#grid').jqxGrid('updatebounddata');
              } else {
                $('#formResetPassword').trigger('reset');
                alert(data.message);
              }
            });
          }
        }
      }
    });
  }

  function editRole() {

    var rowdata = row_selected('#grid');

    __http('get', '/api/v1/roles_active').done(function (data) {
      $('#select_role').html("");
      $.each(data, function (i, v) {
        $('#select_role').append("<option value='" + v.id + "'>" + v.role_name + "</option>");
      });
      $('#select_role').val(rowdata.role_id);
    });

    $('#dialog_edit_role').dialog({
      modal: true,
      resizable: false,
      height: "auto",
      width: 400,
      buttons: {
        "Submit": function () {
          __http('post', '/api/v1/users/update_roles', {
            user_id: rowdata.id,
            role_id: $('#select_role').val()
          }).done(function (data) {
            if (data.result === true) {
              $('#dialog_edit_role').dialog('close');
              $('#formEditRole').trigger('reset');
              $('#grid').jqxGrid('updatebounddata');
            } else {
              $('#formEditRole').trigger('reset');
              alert(data.message);
            }
          });
        }
      }
    });
  }

</script>
<?php $this->end() ?>