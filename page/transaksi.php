<?php
require_once('../controllers/helperController.php');
require_once('../include/head.php');

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
                        <?php
                        if ($_SESSION['jabatan'] == 'admin'){ ?>
                            <div class="col-sm-12">
                                <div class="col-sm-1 pull-right">
                                    <button type="button" name="add" id="add_button" class="btn form-control btn-success btn-xs">Tambah</button>
                                    <br><br>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="transaksitable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Kode</th>
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

<?php require_once('../include/footer.php') ?>

<script>
    $(document).ready(function (e) {
        $('#uang_muka').autoNumeric('init');
        $('#total_transaksi ').autoNumeric('init');
        
    });


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
        $('#datepicker').datepicker('setDate',new Date());
        $('#action').val('Tambah');
        $('#btn_action').val('Add');
        var code = getCode();
    });

    function getCode() {
        var code = '';
        $.ajax({
            url: "../controllers/transaksiController.php",
            method: "POST",
            data: {btn_action: 'getCode'},
            dataType: "JSON",
            success: function(data) {
                $('#kode').val(data);
            }
        })
        .done(function(data){
            $('#kode').val(data);
        });
    }

    // ============= save data
    $(document).on('submit','#user_form', function(e){
        e.preventDefault();
        var getUangMuka = $('#uang_muka').autoNumeric('get');
        $('#uang_muka_get').val(getUangMuka);
        var getTotalTransaksi = $('#total_transaksi').autoNumeric('get');
        $('#total_transaksi_get').val(getTotalTransaksi);
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
                $('select#id_customer').val(data.id_customer);
                $('#pengerjaan').val(data.pengerjaan);
                $('.tgl').val(data.tgl);
                $('input[name="status"][value="'+data.status+'"]').prop('checked',true);
                // $('#'+data.status+'').prop('checked',true);
                $('#uang_muka').val(data.uang_muka);
                $('#kode').val(data.kode);
                $('#total_transaksi').val(data.total_transaksi);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Transaksi");
                $('#action').val("Edit");
                $('#transaksi_id').val(data.id)
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
        var btn_action = 'fetch_single_pengerjaan';
        $.ajax({
            url: '../controllers/transaksiController.php',
            method: 'POST',
            data: {id:id, btn_action:btn_action},
            dataType: 'json',
            success: function(data){
                $('#pengerjaanModal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
                // $('#pekerjaan_ayah').val(data.pekerjaan_ayah);
                $('#id_customer').val(data.id_customer);
                $('#pengerjaan').val(data.pengerjaan);
                $('.tgl').val(data.tgl);
                $('input[name="status"][value="'+data.status+'"]').prop('checked',true);
                // $('#'+data.status+'').prop('checked',true);
                $('#loadCacheAdditionalTime').val(data.cacheAdditionalTime);
                $('#total_transaksi').val(data.total_transaksi);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Transaksi");
                $('#action').val("Edit");
                $('#user_id').val(data.id);
                $('#id_transaksi').val(data.id_transaksi);
                $('#btn_action_pengerjaan').val("edit_pengerjaan");


                $(function() {
                    console.log($('#loadCacheAdditionalTime').val());
                    // (Cookies.get('additionalTime') === '') ? console.log('OK') : console.log('false');
                    // (Cookies.get('additionalTime') === 'NaN') ? console.log("OK") : console.log("FALSE");
                    loadAdditionalTimeCookie();
                    var additionalTime = $('#cacheAdditionalTime').val();
                    transformMillisecondsToFormattedTimeAndPrint(additionalTime);

                    handleSpaceKeyPress();

                    startTimerOnClick();
                    clearTimerOnClick();

                    saveChronometerAndThemeOnExit();
                });
            }
        })
    });

    // ============= save data
    $(document).on('submit','#pengerjaan_form', function(e){
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
                    $('#pengerjaanModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.msg+'</div>');
                    $('#action').attr('disabled', false);
                    transaksiTable.ajax.reload();
                }
            }
        })
    });

    // Timer
    function loadAdditionalTimeCookie() {
        $('#cacheAdditionalTime').val($('#loadCacheAdditionalTime').val());
        // $('#cacheAdditionalTime').val(Cookies.get('additionalTime'));
        /* We show the clear button only if the time retrieved from the cookie
       is greater than 0 millisecond */
        if (parseInt($('#cacheAdditionalTime').val()) > 0) $('#clearButton').css('display', 'inline-block');
    }


    function addZeros(number, length) {
    	var string = '' + number; // Int to string.
    	while (string.length < length) { string = '0' + string; }
    	return string;
    }


    function now() {
    	return (new Date().getTime());
    }


    function transformMillisecondsToFormattedTimeAndPrint(time) { // Time in milliseconds.
    	var hours = parseInt(time / 3600000);
    	var minutes = parseInt(time / 60000) - (hours * 60);
    	var seconds = parseInt(time / 1000) - (minutes * 60) - (hours * 3600);
    	var milliseconds = parseInt(time % 1000);
    	$('#timer').text(addZeros(hours, 2) + ':' + addZeros(minutes, 2) + ':'
    									 + addZeros(seconds, 2) + '.' + addZeros(milliseconds, 3));
    	$('#waktu').val(addZeros(hours, 2) + ':' + addZeros(minutes, 2) + ':'+ addZeros(seconds, 2));
    }

    function startTimer() {
    	var additionalTime = 0;
    	var currentTime = 0;

    	var startTime = now();

    	var timer = setInterval(function() {
    								var additionalTime = parseInt($('#cacheAdditionalTime').val());
    								currentTime = (now() - startTime) + additionalTime;
    								transformMillisecondsToFormattedTimeAndPrint(currentTime);
    							}, 55); // in millisecond.

    	$('#pauseButton').click(function() {
        	clearInterval(timer);

    		Cookies.remove('additionalTime');

    		$('#cacheAdditionalTime').val(currentTime);

    		$('#pauseButton').css('display', 'none');
    		$('#startButton').css('display', 'inline-block');
    		$('#clearButton').css('display', 'inline-block');
    	});
    }


    function handleSpaceKeyPress() {
    	$(document).keyup(function(keyPressed) {
    	    if (keyPressed.keyCode == 32) { // If the space bar has been pressed.
    	      	if ($('#startButton').css('display') == 'inline-block') { // If the start button is visible.
    	      		$('#startButton').click();
    	      	}else {
    	      		$('#pauseButton').click();
    	      	}
    	    }
    	});
    }

    function startTimerOnClick() {
    	$('#startButton').click(function () {
    		$(this).css('display', 'none'); // Hide the 'Start' button.
    		$('#clearButton').css('display', 'none'); // Hide the 'Clear' button in the case it was shown.
    		$('#pauseButton').css('display', 'inline-block'); // Show 'Pause' button.
    		$('#pengerjaanModal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
    		startTimer();
    	});
    }

    function clearTimerOnClick() {
    	$('#clearButton').click(function() {
    		$('#clearButton').css('display', 'none');
    		$('#cacheAdditionalTime').val('0'); // Delete any additional time.
    		$('#timer').text('00:00:00.000');
    	});
    }

    // Puts the chronometer's actual time in milliseconds and the user's theme in a cookie.
    function saveChronometerAndThemeOnExit() {
    	window.onbeforeunload = function() {
    		var additionalTime = $('#cacheAdditionalTime').val();
    		Cookies.set('additionalTime', additionalTime, 365);

    		var actualTheme = $('#styleSheet1').attr('href');
    		Cookies.set('theme', actualTheme, 365);
    	}
    }



</script>

<div id="pengerjaanModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <form method="post" id="pengerjaan_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="form-group">
                            <div id="timer" style="font-size: 3em;"></div>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group">
                            <a href="javascript:void(0)" class="btn btn-success" id="startButton">Mulai</a>
                            <a href="javascript:void(0)" class="btn btn-warning" id="pauseButton">Pause</a>
                            <!-- <a href="javascript:void(0)" class="btn btn-danger" id="clearButton">Clear</a> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-right"><input type="radio" name="status" value="1" checked="checked"/> Sedang dikerjakan</label>
                        <label class="text-right"><input type="radio" name="status" value="2"/> Selesai</label>
                    </div>
                    <input type="hidden" name="waktu" id="waktu" value="">
                    <input type="hidden" name="cacheAdditionalTime" id="cacheAdditionalTime" value="">
                    <input type="hidden" name="loadCacheAdditionalTime" id="loadCacheAdditionalTime" value="">
                    <div id="additionalTime" style="display:none">0</div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="id_transaksi" id="id_transaksi" />
                    <input type="hidden" name="btn_action" id="btn_action_pengerjaan" />
                    <input type="submit" name="action_pengerjaan" id="action_pengerjaan" class="btn btn-info" value="Simpan" />
<!--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
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
                                <select class="form-control" id="id_customer" name="id_customer">
                                    <?php echo getListCostomer($connect) ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" readonly="readonly" name="kode" id="kode" class="form-control" required />
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
                                <input type="text" name="uang_mukas" id="uang_muka" class="form-control" required />
                                <input type="hidden" name="uang_muka" id="uang_muka_get" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Pembayaran</label>
                                <input type="text" name="total_transaksis" id="total_transaksi" class="form-control" required />
                                <input type="hidden" name="total_transaksi" id="total_transaksi_get" class="form-control" required />
                                <div class="text-danger" id="emailError"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Yang Mengerjakan</label>
                                <select class="form-control" name="id_admin">
                                    <?php echo getListAdmin($connect) ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="transaksi_id" id="transaksi_id" />
                    <input type="hidden" name="btn_action" id="btn_action" />
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
