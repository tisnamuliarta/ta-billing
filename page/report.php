<?php
include ('../connection.php');
require_once('../include/head.php');

?>
<div class="container">
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <!-- small box -->
                <div class="box">
                    <div class="box-header  with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Report</h3>
                        <div id="alert_action"></div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="userstable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Kode</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once('../include/footer.php') ?>

<script>
    var userTable = $('#userstable').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: "../controllers/reportController.php",
            data: {report:'report' },
            type: "GET"
        },
        "columnDefs":[
            {"width":"10px","targets":0},
            {
                "targets":[0],
                "orderable":false,
            },
        ],
        "pageLength": 10
    });

    $('#add_button').click(function(){
        $('#userModal').modal('show');
        $('.modal-title').html("<i class='fa fa-plus'></i> Tambah User");
        $('#user_form')[0].reset();
        $('#action').val('Tambah');
        $('#btn_action').val('Add');
        $('#password').prop('disabled',false);
        $('#username').prop('disabled',false);
    });

    // ============= save data
    $(document).on('submit','#user_form', function(e){
        e.preventDefault();
        // $('#action').attr('disabled','disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "../controllers/reportController.php",
            method: "POST",
            data: formData,
            dataType:'JSON',
            success: function(data){
                if(data.errors) {
                    $('#text_error').html(data.msg);
                }else {
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.msg+'</div>');
                    $('#action').attr('disabled', false);
                    userTable.ajax.reload();
                }
            }
        })
    });

    // ============= Display single data and update
    $(document).on('click','.print-report',function(){
        var id = $(this).attr("id");
        var kode = $(this).data("kode");
        $.ajax({
            'url' : '../controllers/reportController.php',
            dataType: 'JSON',
            data: {id_report: id}
        }).done(function(result){
            if (result.success) {
                var url = '../controllers/printBilling.php?id_customer='+id+'&kode='+kode;
                var params = [
                    'height='+screen.availHeight,
                    'width='+screen.availWidth,
                    'fullscreen=yes',
                    'alwaysLowered = false'
                ].join(',');
                window.open(url, 'popup_window', params);
                window.document.title = "Billing";
            }
        }).fail(function(){

        });
    });

    // ================== delete data
    $(document).on('click','.delete-user',function(){
        var user_id = $(this).attr("id");
        var status = $(this).data("status");
        var btn_action = 'Delete';
        if (confirm("Anda yakin akan akan menonaktifkan user ini?")) {
            $.ajax({
                url: '../controllers/customerController.php',
                method: 'POST',
                data: {user_id: user_id, status:status, btn_action:btn_action},
                success: function(data) {
                    $('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data+'</div>')
                    userTable.ajax.reload();
                }
            })
        }else {
            return false;
        }
    })

</script>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
                </div>
                <div class="modal-body">
                    <div id="errors"><span class="text-danger" id="text_error"></span></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Handphone</label>
                                <input type="number" name="tlpn" id="tlpn" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="btn_action" id="btn_action" />
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
