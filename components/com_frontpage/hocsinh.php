<link rel='stylesheet' href='<?php echo ROOTHOST;?>global/char/chart.css'/>
<?php
$DayOfWeek=array('CN','T2','T3','T4','T5','T6','T7');
$thisweek=ceil((time()-$startDate)/(86400*7));
$this_grade 	= getInfo('grade');
$this_version 	= getInfo('grade_version'); 
$username = getInfo('username');
$packet_user = getInfo('packet');
$packet_status = getInfo('packet_status');

$_Subjects = api_get_subject();
if(isset($_POST['gen_nvkhung'])){
	$_SESSION['show_nv_khung']=1;
}
?>
<div class="row1 <?php if($packet_user==false) echo 'packet-free';?>">
	<div class="main-left">
		<?php 
		// lấy ngày trong tuần của work1
		$arr_date=getDateReport(2);
		$first_date=isset($arr_date['first'])? $arr_date['first']:'';
		$last_date=isset($arr_date['last'])? $arr_date['last']:'';
		$day=date('w');
		$dofw=$DayOfWeek[$day];
		$strwhere_nv="";
		$strwhere_nv.=" AND week='$thisweek'";
		if($day!=0) $strwhere_nv.=" AND dayofweek='$dofw'";
		//------------------------ Get nhiệm vụ học tập -----------------------------
		$_Nhiemvu=getDataNhiemVu($strwhere_nv,0);
		include_once('hocsinh/nhiemvu.php');
		?>
		<!--Môn học-->
		<div class="home-monhoc home-item">
			<h2 class="fw-400 font-lg d-block">Môn học</h2>
			<div  class="owl-carousel category-card owl-theme overflow-hidden overflow-visible-xl nav-none">
				<?php $i=0;
				foreach($_Subjects as $k=>$v) {
					$i++; 
					?>
					<div class="item">
						<a href="<?php echo ROOTHOST;?>lession/<?php echo $k;?>" class="item item-subject subject<?php echo $i;?>">
							<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
							<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php if(!$packet_user){ ?>
			<!-- Khóa học -->
			<div class="home-box-course">
				<h2 class="fw-400 font-lg d-block">Nâng cấp khóa học</h2>
				<div class="row">
					<div class="col-md-6">
						<div class="course-item">
							<h3 class="course-name ez1">Cơ bản</h3>
							<div class="content">
								<div class="box-price">
									<div class="left">
										<span class="price font-20">180.000đ</span>
										<span>/ tháng</span>
										<!-- <div class="month">x 12 tháng</div> -->
									</div>
									
								</div>

								<div class="desc">
									<ul>
										<li>
											<i class="fa fa-check-circle" aria-hidden="true"></i>Đăng ký tài khoản miễn phí</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho học liệu đa phương tiện theo chuẩn BGD & ĐT</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Bảng nhiệm vụ học tập hướng đối tượng</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho bài luyện tập đa dạng: 100.000+ câu hỏi trắc nghiệm, tự luận...</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>2000+ bài kiểm tra, đề luyện thi</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Báo cáo kết quả học tập cho phụ huynh</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Cố vấn học tập ảo</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Nhận điểm thưởng Sao & Kim cương với mỗi hoàn thành nhiệm vụ học tập</li>
									</ul>
								</div>

								<div class="wr-btn-regis text-center">
									<a href="javascript:void(0)" class="btn-regis" onclick="regis_packet('EZ1')">Đăng ký ngay</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="course-item">
							<h3 class="course-name ez2">Nâng cao</h3>
							<div class="content">
								<div class="box-price">
									<div class="left">
										<span class="price font-20">280.000đ</span>
										<span class="month">/ tháng</span>
									</div>
									
								</div>

								<div class="desc">
									<ul>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Đăng ký tài khoản miễn phí</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho học liệu đa phương tiện theo chuẩn BGD & ĐT</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Bảng nhiệm vụ học tập hướng đối tượng</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho bài luyện tập đa dạng: 100.000+ câu hỏi trắc nghiệm, tự luận...</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>2000+ bài kiểm tra, đề luyện thi</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Báo cáo kết quả học tập cho phụ huynh</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Cố vấn học tập ảo</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Nhận điểm thưởng Sao & Kim cương với mỗi hoàn thành nhiệm vụ học tập</li>
										<li ><i class="fa fa-check-circle" aria-hidden="true"></i>Giáo viên/ gia sư hướng dẫn học, điều chỉnh lộ trình học tập thích hợp cho từng học sinh, giải đáp thắc mắc, chấm bài và nhận xét kết quả học tập</li>
									</ul>
								</div>

								<div class="wr-btn-regis text-center">
									<a href="javascript:void(0)" class="btn-regis" onclick="regis_packet('EZ2')">Đăng ký ngay</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- check đã đăng ký hay chưa -->
				<?php 

				$json = array();
				$json['key'] = PIT_API_KEY;
				$json['member'] = $username;
				$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
				$rep = Curl_Post(API_CHECK_EXIST_ORDER_SERVICE, json_encode($post_data)); 
				$infor_order_service=isset($rep['data']['infor_order'])?$rep['data']['infor_order']:array();
				$infor_method_payment=isset($rep['data']['infor_method_payment'])?$rep['data']['infor_method_payment']:array();
				$status=isset($infor_order_service['status'])?$infor_order_service['status']:"";
				$method_payment=isset($infor_order_service['method_payment'])?$infor_order_service['method_payment']:"";
				if($status=='L0'){
					if($method_payment=="chuyen_khoan"){
					?>
					<div class="row">
						<p class="col-md-12"><i class="fa fa-exclamation-circle"></i> Bạn đã đăng ký thành công! Xin vui lòng chờ xác nhận từ admin. Nếu bạn chưa thanh toán thì vui lòng chuyển khoản với nội dung "<strong>EZ <?php echo $username; ?></strong>" vào một trong các tài khoản sau đây:</p>
						<div class="col-md-12">
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th>Chủ tài khoản</th>
									<th>Ngân hàng</th>
									<th>Số tài khoản</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if(isset($infor_method_payment[$method_payment]['list_bank'])){
									foreach ($infor_method_payment[$method_payment]['list_bank'] as $key_bank => $value_bank) {

										?>
										<tr>
											<td>
												<?php echo $value_bank['chutk']; ?>
											</td>
											<td>
												<?php echo $value_bank['bank']; ?>
											</td>
											<td>
												<?php echo $value_bank['sotk']; ?>
											</td>
										</tr>
										<?php
									}
								} 
								?>
							</tbody>
						</table>
						</div>
					</div>
				<?php
					}else if($method_payment=="kich_hoat"){
						?>
						<div class="row">
							<p class="col-md-12"><i class="fa fa-exclamation-circle"></i> Bạn đã đăng ký thành công! Vui lòng liên hệ với đại lý để thanh toán, hoặc liên hệ với hỗ trợ (hotline: <strong><?php echo HOTLINE; ?></strong>) để xử lý!
							</p>
						</div>
					<?php
					}else if($method_payment=="khac"){
						?>
						<div class="row">
							<p class="col-md-12"><i class="fa fa-exclamation-circle"></i> Bạn đã đăng ký thành công! Vui lòng liên hệ với hỗ trợ (hotline: <strong><?php echo HOTLINE; ?></strong>) để xử lý!
							</p>
						</div>
						
					<?php
					}
				}
				
				?>

				<!-- end check đã đăng ký hay chưa -->


			</div>
		<?php } ?>

		<?php $grade = getInfo('grade'); ?>

		<!--Báo cáo-->				
		<div class="home-baocao home-item">
			<h2 class="fw-400 font-lg d-block">Báo cáo học tập</h2>
			<?php include_once('hocsinh/char_week.php');?>
		</div>
	</div>

	<div class="main-right box-nav-right">
		<?php include_once('hocsinh/canhan.php');?>	
	</div>
</div>

<script>
	$(".nav-tabs-ctr a").click(function(){
		$(".nav-tabs-ctr a").removeClass('active');
		$(this).addClass('active');
	});

	// Đăng ký gói dịch vụ, nâng cấp gói dịch vụ
	function frm_packet(cur_packet=""){
		$('#myModalPopup .modal-dialog').removeClass('modal-lg');
		$('#myModalPopup .modal-title').html('Nâng cấp khóa học');
		
		var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_packet.php";
		$.post(url, {"cur_packet": cur_packet},function(req){
			$('#modal-content').html(req);
			$('#myModalPopup').modal('show');
		});
	}
</script>