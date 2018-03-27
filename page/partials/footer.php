</section>
	</div>
	<footer class="main-footer">
	    <strong>Copyright &copy; 2014-2016 <a href="javascript:void(0)">TK SINAR PRIMA</a>.</strong> All rights
	    reserved.
	</footer>
</div>
<!-- jQuery 3 -->
<script src="<?php __DIR__ ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php __DIR__ ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php __DIR__ ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php __DIR__ ?>/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php __DIR__ ?>/assets/dist/js/adminlte.min.js"></script>
<script type="text/javascript">
	$(function(){
		var url = window.location.pathname;
		console.log(url)
		$('ul.navbar-nav li a[href="' + url + '"]').parent().addClass('active');
	    $('li#link-sidebar a[href="' + url + '"]').parent().addClass('active');

	})
</script>
</body>
</html>