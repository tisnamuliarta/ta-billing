<?php
include ('../connection.php');
require_once('../include/head.php');

?>
<div class="container">
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="box">
                <div class="box-header  with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Data User</h3>
                    <div id="alert_action"></div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-1 pull-right">
                                <button type="button" name="add" id="add_button" class="btn form-control btn-success btn-xs">Tambah</button>
                                <br><br>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="userstable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Jabatan</th>
                                    <th>No Telepon</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th></th>
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
    $(function() {
        $('#tlpn').inputmask({"mask": "999-999-999-9999"})
    });

    var userTable = $('#userstable').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: "../controllers/adminController.php",
            data: {admin:'admin' },
            type: "GET"
        },
        "columnDefs":[
            {"width":"10px","targets":0},
            {
            "targets":[0,6,7],
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
            url: "../controllers/adminController.php",
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
    $(document).on('click','.update-user',function(){
        var id = $(this).attr("id");
        $('#password').prop('disabled',true);
        $('#username').prop('disabled',true);
        var btn_action = 'fetch_single';
        $.ajax({
            url: '../controllers/adminController.php',
            method: 'POST',
            data: {id:id, btn_action:btn_action},
            dataType: 'json',
            success: function(data){
                $('#userModal').modal('show');
                // $('#pekerjaan_ayah').val(data.pekerjaan_ayah);
                $('#username').val(data.username);
                $('#alamat').val(data.alamat);
                $('#email').val(data.email);
                $('select#jabatan').val(data.jabatan);
                if (data.jabatan === 'admin') {
                    $('input[name="status"]').attr('disabled',true);
                    $('input[name="status"][value="'+data.status+'"]').prop('checked',true);
                } else {
                    $('input[name="status"]').attr('disabled',false);
                    $('input[name="status"][value="'+data.status+'"]').prop('checked',true);
                }
                // $('#'+data.status+'').prop('checked',true);
                $('#tlpn').val(data.tlpn);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit User");
                $('#action').val("Edit");
                $('#user_id').val(id)
                $('#btn_action').val("Edit");
            }
        })
    });

        // ================== delete data
    $(document).on('click','.delete-user',function(){
        var user_id = $(this).attr("id");
        var status = $(this).data("status");
        var btn_action = 'Delete';
        if (confirm("Anda yakin akan akan menonaktifkan user ini?")) {
            $.ajax({
                url: '../controllers/adminController.php',
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
                                <label>Username</label>
                                <input type="text" name="username" id="username" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select name="jabatan" id="jabatan" class="form-control">
                                    <option value="desainer">Desainer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Handphone</label>
                                <input type="text" name="tlpn" id="tlpn" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" required id="password" name="password"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label><input type="radio" id="active" name="status" checked="checked" value="active" required> Active</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label><input type="radio" id="non-active" name="status" value="non-active"> Non Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
