<?php
require_once('../controllers/helperController.php');
require_once(__DIR__.'/partials/head.php');

?>
<div class="container">
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="box">
                <div class="box-header  with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Data Transaksi</h3>
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
                                <table id="transaksitable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Pengerjaan</th>
                                        <th>DP</th>
                                        <th>Total Transaksi</th>
                                        <th>Status</th>
                                        <th>Dikerjan Oleh</th>
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

<?php require_once(__DIR__.'/partials/footer.php') ?>

<script>
    var transaksiTable = $('#transaksitable').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: "../controllers/transaksiController.php",
            data: {transaksi:'transaksi' },
            type: "GET"
        },
        "columnDefs":[
            {"width":"60px","targets":0},
            {
            "targets":[0],
            "orderable":false,
            },
        ],
        "pageLength": 10
    });

    $('#add_button').click(function(){
        $('#transaksiModal').modal('show');
        $('.modal-title').html("<i class='fa fa-plus'></i> Tambah Transaksi");
        $('#user_form')[0].reset();
        $('#action').val('Tambah');
        $('#btn_action').val('Add');
    });

    // ============= save data
    $(document).on('submit','#user_form', function(e){
        e.preventDefault();
        // $('#action').attr('disabled','disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "../controllers/transaksiController.php",
            method: "POST",
            data: formData,
            dataType:'JSON',
            success: function(data){
                if(data.errors) {
                    $('#text_error').html(data.msg);
                }else {
                    $('#user_form')[0].reset();
                    $('#transaksiModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.msg+'</div>');
                    $('#action').attr('disabled', false);
                    transaksiTable.ajax.reload();
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
            url: '../controllers/transaksiController.php',
            method: 'POST',
            data: {id:id, btn_action:btn_action},
            dataType: 'json',
            success: function(data){
                $('#transaksiModal').modal('show');
                // $('#pekerjaan_ayah').val(data.pekerjaan_ayah);
                $('#id_customer').val(data.id_customer);
                $('#pengerjaan').val(data.pengerjaan);
                $('.tgl').val(data.tgl);
                $('input[name="status"][value="'+data.status+'"]').prop('checked',true);
                // $('#'+data.status+'').prop('checked',true);
                $('#uang_muka').val(data.uang_muka);
                $('#total_transaksi').val(data.total_transaksi);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Transaksi");
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
                url: '../controllers/transaksiController.php',
                method: 'POST',
                data: {user_id: user_id, status:status, btn_action:btn_action},
                success: function(data) {
                $('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data+'</div>')
                transaksiTable.ajax.reload();
                }
            })
        }else {
            return false;
        }
    })

    // ============= Display single data and update
    $(document).on('click','.perkerjaan',function(){
        var id = $(this).attr("id");
        $('#password').prop('disabled',true);
        $('#username').prop('disabled',true);
        var btn_action = 'fetch_single';
        $('#pengerjaanModal').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
</script>

<div id="pengerjaanModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <form method="post" id="pengerjaan_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
                </div>
                <div class="modal-body">
                    <div id="errors"><span class="text-danger" id="text_error"></span></div>
                    <input type="text" name="time" value="" id="timer">
                    <button id="startButton">Start</button>
                    <button id="pauseButton">Pause</button>
                    <button id="clearButton">Clear</button>
                    <div id="additionalTime" style="display:none">0</div>

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

<div id="transaksiModal" class="modal fade">
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
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Customer</label>
                                <select class="form-control" name="id_customer">
                                    <?php echo getListCostomer($connect) ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Pengerjaan</label>
                                <input type="text" name="pengerjaan" id="pengerjaan" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" name="tgl" id="datepicker" class="form-control tgl" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Uang Muka</label>
                                <input type="number" name="uang_muka" id="uang_muka" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Pembayaran</label>
                                <input type="number" name="total_transaksi" id="total_transaksi" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
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
