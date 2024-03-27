<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
?>
<div class="container">
	<div class="login-form card-body rounded-0 text-center p-3 box_right" style='margin:auto; position:inherit'>
		<h2 class="caption">
			Cấu hình khối lớp
		</h2>
		<form id="frmlogin" name="frmlogin" class="frmlogin"  method="post" action="member-config" autocomplete="off">
			<div class="inner">
				<div class='err_mess form-group'></div>
				<div class="form-group row">
					<div class="col-md-12 col-xs-12">
						<select name='class' id='cbo_grade' namge='grade' class='form-control' required>
							<option value="">-- Chọn khối lớp* --</option>
							<?php 
							if(isset($_Grade)) { 
								foreach($_Grade as $k=>$v) { 
									echo '<option value="'.$k.'">'.$v.'</option>';
								} 
							} ?>
						</select>
					</div>
				</div>
				<div class="clearfix">
					<button type='button' id='btn-process-login' name='cmd_login' class='btn btn-primary  btn-login form-control'>TIẾP TỤC</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$('#btn-process-login').click(function(){
	if($('#cbo_grade').val()!=''){
		var _url='<?php ROOTHOST;?>ajaxs/member/member-config-subject.php';
		$.get(_url,{'grade':$('#cbo_grade').val()},function(req){
			$('#config-grade').html(req);
		});
	}else{
		$('.err_mess').html('Vui lòng chọn khối lớp!');
	}
});
</script>