<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
include_once(incl_path."config_api.php");
require_once(libs_path.'cls.mysql.php');
$grade=isset($_GET['grade'])?addslashes($_GET['grade']):'';
$this_version 	= getInfo('grade_version'); 
$this_subject	= getInfo('subject_list');
$check_all=$active_all='';
if($this_subject == 'N/a' || $this_subject == ''){
	$arr_subject=array();
	$check_all='checked';
	$active_all='active';
}
else $arr_subject=explode(',',$this_subject);

$_Grade_version=api_get_version();
$_Subjects=api_get_subject($grade);
$count=count($_Grade_version);
?>
<div class="welcome-page">
	<div class="card">
		<div class="card-block">
		<form method="post" id="frm-subject" class="box-subject">
			<div class="inner">
				<div class='err_mess form-group'></div>
				<input type='hidden' name='grade' value='<?php echo $grade;?>'/>
				<?php
				if(is_array($_Grade_version) && count($_Grade_version)>0) {
					$count=count($_Grade_version);
					?>
					<p style="font-size: 16px">1 Chọn bộ sách</p>
					<div class="err-label" id="err_version"></div>
					<div class="grade_version text-center" id="">
						<?php 
						foreach($_Grade_version as $k=>$v) {
							if($v['grade']!=$grade) continue;
							$class=$checked='';
							echo "<div class='box' >
							<span class='item_link item ".$class."' data-id='".$k."' data-name='".$v['title']."'><input ".$checked." class='icon-act' type='checkbox' name='txt_version[]' value='".$v['id']."' id='txt_version".$k."'>
							<i class='fa fa-3x fa-book'></i> <div>".$v['title']."</div>
							</span>
							</div>";
						}?>
					</div>
				<?php } ?>
				<div class="grade_subject text-left" id="" style='padding:15px;'>
					<p  style="font-size: 16px"><?php echo $count>0? '2':'';?> Lựa chọn các môn học</p>
					<div class="err-label" id="err_subject"></div>
					<div class="item item-subject subject-all <?php echo $active_all;?>" id="all_item">
						<div class="item-all-name">
							<h4 class="name">Chọn tất cả</h4>
						</div>
					</div>
					<?php 
					$i=0;
					if(count($_Subjects)>0){ 			
						foreach($_Subjects as $k=>$v) {
							$class=$checked='';
							$i++; 
						?>
							<div class="item item-subject box-item subject<?php echo $i;?> <?php echo $class;?> <?php echo $active_all;?>" data-id="<?php echo $k;?>">
								<input <?php echo $checked;?> <?php echo $check_all;?> class="icon-act" type="checkbox" name="txt_subject[]" value="<?php echo $k;?>" id="txt_subject<?php echo $k;?>">
								<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
								<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
				<div class="clearfix">
					<div class='col-sm-4' style='margin:auto;'>
						<button type='button' id='save_action' name='cmd-save' class='btn btn-primary  btn-login form-control'>TIẾP TỤC</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	</div>
</div>
<style>
#save_action{
	font-weight: bold;
    line-height: 30px;
    border: none;
    margin-bottom: 10px;
    background: #E34214;
    border-radius: 12px;
}
</style>
<script>
var count='<?php echo $count;?>'
$(".grade_version .item").click(function(){
	$('#err_version').html('');
	$(".grade_version .item").removeClass('active');
	var id=$(this).attr('data-id');
	$(".grade_version .icon-act").prop("checked", false);
	document.getElementById('txt_version'+id).checked = true;
	$(this).addClass('active');
})
$("#all_item").click(function(){
	$(".grade_subject .item").addClass('active');
	$(".grade_subject .icon-act").prop("checked", true);
})

$('.box-item').click(function(){
	$('#err_subject').html('');
	$(this).addClass('active');
	var id=$(this).attr('data-id');
	var checked = $('#txt_subject'+id).is(':checked');
	if (checked===false){
		$('#txt_subject'+id).prop("checked", true); 
	}
	else{
		$('#txt_subject'+id).prop("checked", false); 
		$(this).removeClass('active');
	}
	$("#all_item").removeClass('active');
})

$("#save_action").click(function(){
	if(count>0){
		check_version = $('input[name="txt_version[]"]:checked').length;
		if(check_version==0){
			$('#err_version').html('Vui lòng chọn Version bộ sách');
			return false;
		}
	}
	var check_subject = $('input[name="txt_subject[]"]:checked').length;
	if(check_subject==0){
		$('#err_subject').html('Vui lòng chọn Các môn bạn muốn học');
		return false;
	}
	var form = $('#frm-subject');
	var postData = form.serializeArray();
	var url = "<?php echo ROOTHOST;?>ajaxs/member/member-config-process.php";
	$.post(url,postData,function(req){
		console.log(req);
		if(req == "success") {
			setTimeout(function(){window.location.reload();},1000);
		}else {
			showMess(req,"error");
		}
	})
})
</script>