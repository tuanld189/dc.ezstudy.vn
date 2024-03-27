<?php
$type_user = getInfo('utype');
$user_login = getInfo('username');
if($type_user=='chame'){
	$item = $_CHILD_INFO;
	$this_grade = isset($item['grade']) ? $item['grade']:'';
	$this_version = isset($item['grade_version']) ? $item['grade_version']:'';
	$this_fullname = $item['fullname'];	

	$subject_list = $_CHILD_INFO['subject_list'];
	if($subject_list == 'N/a' || $subject_list == '') 
		$arr_subject = array();
	else 
		$arr_subject = explode(',',$subject_list);
}else{
	$this_version 	= getInfo('grade_version');
	$this_grade 	= getInfo('grade');	
	$this_fullname 	= getInfo('fullname');	

	$subject_list = getInfo('subject_list');
	if($subject_list == 'N/a' || $subject_list == '') 
		$arr_subject = array();
	else 
		$arr_subject = explode(',',$subject_list);
}

$sl_user_child = isset($_CHILD_INFO) && is_array($_CHILD_INFO) && count($_CHILD_INFO)>0 ? $_CHILD_INFO:'';// phụ huynh select xem bài
$error_smg = '';
$username = getInfo('username');
$gsub = isset($_GET['subject']) ? antiData($_GET['subject']) : '';
$arr = explode("_", $gsub);
$this_subject = $arr[1];
$grade_version = $this_version;
$_Subjects = api_get_subject($this_grade);

// echo '<pre>';
// var_dump($this_grade);
// var_dump($this_subject);
// echo '</pre>';

// chuyển đổi link khi chuyển lớp
if($gsub != $this_grade."_".$this_subject) { ?>
	<script> window.location ='<?php echo ROOTHOST;?>lession/<?php echo $this_grade."_".$this_subject;?>'</script>
<?php }

// Nếu lớp # lớp 1 & lớp 6 thì mặc định bộ sách cũ
if(!in_array($this_grade,array("K01","K06")) && ($grade_version == "" || $grade_version == "N/a") ) {
	//update grade version cho học sinh
	$arr = array();
	$arr['grade_version'] = $this_grade."_V01";
	$result1 = SysEdit("ez_member",$arr," username='$username'");
	setInfo('grade_version',$this_grade."_V01");
	$grade_version = getInfo('grade_version');
}

if($gsub == "") 
	$error_smg = '<div class="alert alert-danger">Không tìm thấy dữ liệu chương trình học</div>';

// get dữ liệu môn học
$arr_sub = isset($_Subjects[$gsub]) ? $_Subjects[$gsub] : array();
$subject = isset($arr_sub['subject']) ? $arr_sub['subject'] : '';
$subject_icon = isset($arr_sub['subject_icon']) ? $arr_sub['subject_icon'] : '';
$subject_name = isset($arr_sub['subject_name']) ? $arr_sub['subject_name'] : '';
$units = isset($arr_sub['units']) ? json_decode($arr_sub['units'],true) : array();

if(!isset($units[$grade_version]))  
	$error_smg = '<div class="alert alert-danger">Không tìm thấy dữ liệu</div>';
$arr_units = isset($units[$grade_version]) ? $units[$grade_version] : array();
?>
<div class=" lession-page">
	<div class="">
		<div class="lession-menu">
			<div class=""><div class="units-list">
				<?php 
				$unit_active = $unit_title = '';
				if(count($arr_units) == 0) echo "<div class='alert alert-danger'>Không có dữ liệu</div>";
				else {
					$i = 0;
					foreach($arr_units as $k=>$v) { 
						$unit_id = $v['id']; 
						$cls = ''; 
						if($i==0) {
							$cls = 'active';
							$unit_active = $unit_id;
							$unit_title  = $v['title'];
						}
						$i++;?>
						<div class="unit <?php echo $cls;?>" dataid="<?php echo $unit_id;?>" datatitle="<?php echo $v['title'];?>">
							<a href="javascript:void(0)"><?php echo $v['title'];?></a>
						</div>
					<?php } // end foreach 
				} // end if ?>
			</div>
		</div>
	</div>
	<?php 
		//------------------------ get bài học -----------------------------
	
	$arrLesson = array();
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = $this_grade;
	$json['subject'] = $subject;
	$json['version'] = $grade_version;
	$json['unit']    = $unit_active;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arrLesson = $rep['data'];
	}
	?>
	<div class="lession-main">
		<div class="lession-header">
			<h1 class="label-title pull-left">
				<i class="fa icon <?php echo $subject_icon;?>"></i> 
				<span class="text-mon">Môn <?php echo $subject_name;?></span>
			</h1>
			<select class="form-control pull-right" id="cbo_subject">
				<option>Chọn môn học</option>
				<?php 
				$_Subjects=api_get_subject($this_grade);
				foreach($_Subjects as $k=>$v) { 

					if($gsub == $k) continue; ?>
					<option value="<?php echo $k;?>"><?php echo $v['subject_name'];?></option>

				<?php } ?>
			</select>
			<div class="clearfix"></div>
		</div>
		<div class="card">
			<div class="card-block"> 
				<?php 
				if($error_smg != "" ) echo $error_smg;
				else { ?>
					<h2 class="title"><?php echo $unit_title;?></h2>
					<ul class="lession_list">
						<?php 
						if(is_array($arrLesson) && count($arrLesson)>0) {
							foreach($arrLesson as $a=>$b) { 
								$link = ROOTHOST."lession-detail/".$b['id'];
								$link_baitap = ROOTHOST."lession-exercise/".$b['id'];
								$link_video = ROOTHOST."lession-video/".$b['id'];
								//$link_luyentap = ROOTHOST."lession-practice/".$b['id'];
								?>
								<li>
									<div class='row'>
										<div class='col-md-8 col-xs-12 title'>
											<a href='<?php echo $link; ?>'><?php echo $b['title']; ?></a>
										</div>
										<div class='col-md-4 col-xs-12 func'>
											<a href='<?php echo $link; ?>' class='btn'>
												<img src="<?php echo ROOTHOST; ?>images/baihoc/icon_ly_thuyet.png" alt="Lý thuyết" title="Lý thuyết">
											</a>
											<a href='<?php echo $link_baitap; ?>' class='btn'>
												<img src="<?php echo ROOTHOST; ?>images/baihoc/icon_baitap.png" alt="Bài tập" title="Bài tập">
											</a>
											<a href='<?php echo $link_video; ?>' class='btn'>
												<img src="<?php echo ROOTHOST; ?>images/baihoc/icon_video.png" alt="Video" title="Video">
											</a>
										</div>
									</div>
								</li>
								<?php
							} 
						}?>
					</ul>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<input type="hidden" id="cur_grade" value="<?php echo $this_grade;?>"/>
	<input type="hidden" id="cur_subject" value="<?php echo $subject;?>"/>
</div>
</div>

<script>
	$("#cbo_subject").change(function(){
		var val=$(this).val();
		window.location.href="<?php echo ROOTHOST;?>lession/"+val;
		
	})
	$(".lession-menu .unit").click(function(){
		showLoading();
		var unit_id 	= $(this).attr('dataid');
		var unit_title 	= $(this).attr('datatitle');
		var cur_grade 	= $("#cur_grade").val();
		var cur_subject = $("#cur_subject").val();
		
		// change active
		$(".lession-menu .unit").removeClass('active');
		$(this).addClass('active');
		
		$(".lession-main .card-block").html("<div class='fa fa-spin'></div>");
		var url = "<?php echo ROOTHOST;?>ajaxs/lession/get_lession.php";
		$.post(url,{unit_id, unit_title, cur_grade, cur_subject},function(req) {
			hideLoading();
			$(".lession-main .card-block").html(req);
		})
	})
</script>