<?php $this->layout('layouts/dashboard', ['title' => 'Capabilities']);?>

<div>
  <p>
    <button class="btn btn-primary" id="new_capabilities">New Capability</button>
    <button class="btn btn-danger" id="delete_capabilities">Delete Capability</button>
  </p>
  <p>
    <div id="grid"></div>
  </p>

  <div id="dialog_create_new" title="New Capabilities" style="display: none;">
    <form id="formNewCapabilities">
      <div class="form-group">
        <label for="cap_name">Name</label>
        <input type="text" name="cap_name" id="cap_name" class="form-control" autofocus>
      </div>
      <div class="form-group">
        <label for="cap_slug">Slug</label>
        <input type="text" name="cap_slug" id="cap_slug" class="form-control" readonly>
      </div>
    </form>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    grid();

    $('#new_capabilities').on('click', function () {

      $('#formNewCapabilities').trigger('reset');

      $('#dialog_create_new').dialog({
        modal: true,
        resizable: false,
        height: "auto",
        width: 400,
        buttons: {
          "Submit": function () {
            __http('post', '/api/v1/capabilities/create', {
              cap_slug: $('#cap_slug').val(),
              cap_name: $('#cap_name').val(),
            }).done(function (data) {
              if (data.result === true) {
                $('#dialog_create_new').dialog('close');
                $('#formNewCapabilities').trigger('reset');
                $('#grid').jqxGrid('updatebounddata');
              } else {
                $('#formNewCapabilities').trigger('reset');
                alert(data.message);
              }
            });
          }
        }
      });
    });

    $('#cap_name').keyup(function () {
      $('#cap_slug').val($('#cap_name').val().toLowerCase().replace(/[\s-'.@#\\/+=*%&!$?)({}]/g, "_"));
    });

    $('#delete_capabilities').on('click', function () {
      var rowdata = row_selected('#grid');
      if (typeof rowdata !== 'undefined') {
        __http('post', '/api/v1/capabilities/delete', {
          cap_id: rowdata.id
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

  function grid() {
    var dataAdapter = new $.jqx.dataAdapter({
      datatype: 'json',
      datafields: [
        { name: 'id', type: 'number' },
        { name: 'cap_slug', type: 'string' },
        { name: 'cap_name', type: 'string' },
        { name: 'cap_status', type: 'bool' }
      ],
      url: '/api/v1/capabilities',
      updaterow: function (rowid, rowdata, commit) {
        __http('post', '/api/v1/capabilities/edit', {
          id: rowdata.id,
          slug: rowdata.cap_name.toLowerCase().replace(/[\s-'.@#\\/+=*%&!$?)({}]/g, "_"),
          name: rowdata.cap_name,
          status: rowdata.cap_status
        }).done(function (data) {
          if (data.result === true) {
            commit(true);
            $('#grid').jqxGrid('updatebounddata');
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
        { text: 'Slug', datafield: 'cap_slug', width: 200, editable: false },
        { text: 'Name', datafield: 'cap_name', width: 300 },
        { text: 'Status', datafield: 'cap_status', columntype: 'checkbox', filtertype: 'bool', width: 100 }
      ]
    });
  }
</script>
<?php $this->end() ?>