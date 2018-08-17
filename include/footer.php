</section>
	</div>
	<footer class="main-footer">
	    <strong>Copyright &copy; 2018 <a href="javascript:void(0)">Billing System</a>.</strong> All rights
	    reserved.
	</footer>
</div>
<!-- jQuery 3 -->
<script src="../assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- date-range-picker -->
<script src="../assets/bower_components/moment/min/moment.min.js"></script>
<script src="../assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- InputMask -->
<script src="../assets/input-mask/jquery.inputmask.js"></script>
<script src="../assets/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../assets/input-mask/jquery.inputmask.extensions.js"></script>
<!-- AdminLTE App -->
<script src="../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../assets/dist/js/adminlte.min.js"></script>
<script src="../assets/js/app.js"></script>
<script src="../assets/js/index.js"></script>
<script src="../assets/js/jsCookie.js"></script>
<script src="../assets/js/AutoNumeric.js"></script>
<script type="text/javascript">
	$(function(){
		var url = window.location.pathname;
		$('ul.navbar-nav li a[href="' + url + '"]').parent().addClass('active');
	    $('li#link-sidebar a[href="' + url + '"]').parent().addClass('active');

		//Date picker
		$('#datepicker').datepicker({
		  	autoclose: true,
			format: 'yyyy-mm-dd',
			todayHighlight: true,
			toggleActive: true
		});
		$('#datepicker').datepicker('setDate',new Date());
		$('#datepicker').datepicker('update', '2018-08-08');

	})
</script>
</body>
</html>
