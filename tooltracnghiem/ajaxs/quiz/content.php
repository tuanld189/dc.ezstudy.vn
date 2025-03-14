<?php
session_start();
define('incl_path_par','../../../global/libs/');
define('incl_path','../../global/libs/');
require_once(incl_path.'config-tool.php');
require_once(incl_path_par.'gffunc.php');
$dataQuiz=isset($_POST['data_quiz'])? json_decode($_POST['data_quiz'],true):'';
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$quiz_id=isset($_POST['quiz'])? (int)$_POST['quiz']:0;
$type=isset($_POST['type'])? (int)$_POST['type']:1;			// 1 là nhiemvu(work1), 2 là nhiemvu(work), 3 là bài kiểm tra (exam);

$page=$quiz_id+1;

$knowledge='';
if(!isset($dataQuiz[$exam_id])){
	echo 'Not Found';
	die();
}

$item=$dataQuiz[$exam_id];
$time_start=(int)$dataQuiz['time_start'];
$totalSeconds=time()-$time_start;
$info_exam=$dataQuiz['info_exam'];

$time_len=isset($info_exam['time_len'])?(int)$info_exam['time_len']:2700;
$title_exam=$info_exam['title'].' (Thời gian làm bài: '.($time_len/60).' phút)';
$number_quiz=$info_exam['total_quiz'];
$isview=($info_exam['flag']=='L2')?1:0;
$data_ans=$info_exam['data_answer'];  //lấy kết quả đã trả lời

if(isset($item[$quiz_id])){
	$quession=$item[$quiz_id]['content'];
	$title=$item[$quiz_id]['title'];
	$answer=$item[$quiz_id]['answers'];
	
	$flag_know=$flag_guide=false;
	if(isset($item[$quiz_id]['guide']) && $item[$quiz_id]['guide']!='') $flag_guide=true;
	if(isset($item[$quiz_id]['knowledge']) && $item[$quiz_id]['knowledge']!='') $flag_know=true;
	
	$arr_answer=json_decode($answer, true);
	$guide=json_encode($item[$quiz_id]['guide']);
	?>
	<nav class="nav-top">
		<div class="container-fluid">
			<?php if((int)$isview!==1){?>
			<h3 id="timer"></h3>
			<?php }?>
			<h3 class="label-title"><?php echo $title_exam;?></h3>
			<?php if((int)$isview!==1){?>
			<div class="gr-box-btn">
				<button class="btn btn-success" onclick="getRS()">Hoàn thành nhiệm vụ</button>
				<span class="btn-send btn-default btn" onclick="exit()">Thoát</span>
			</div>
			<?php }else{ ?>
			<div class="gr-box-btn">
				<span class="btn btn-default btn" onclick="rework()">Làm lại</span>
			</div>	
			<?php }?>
		</div>
	</nav>
	
	<div id="main">
		<div class="page-exam">
			<div class="box-item info-test">
				
				<div class="info-quiz">
					<?php if((int)$type===1){ ?>
					<div class="group-btn">
						<?php if($flag_guide==true){?><span class="btn btn1 btn-success" onclick="showGuide('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Hướng dẫn</span><?php }?>
						<?php if($flag_know==true){?><span class="btn btn2 btn-danger" onclick="showDesc('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Lý thuyết</span><?php }?>
					</div>
					<?php }?>
					<div class="clearfix"></div>
				</div>
				<ul class="list-inline notic-answer"><li><span class="color red"></span> là đáp án đúng</li><li><span class="color black"></span> gạch chân là đáp án sai</li></ul>
			</div>
			<div class="item-question" id="list_quiz">Hello</div>
			<div class="box-guide" id="box_guide"></div>

			<form method="get" class="box-btn">
				<span class="btn btn-default"  onclick="gotopage('<?php echo $number_quiz?>',1)"><< Quay lại</span>
				<span class="btn btn-default "  onclick="gotopage('<?php echo $number_quiz?>',2)">Tiếp theo >></span>
				<input type="hidden" id="page_quiz" name="page" value="<?php echo $page;?>">
			</form>
		</div>

		<div class="box-action act-ctrl" id="act-ctrl">
			<div class="box-item info-test">
				<h4 class="title-test">Danh sách câu</h4>
			</div>
			<div class="item-row" id="data_frm_quiz">
				<?php
				$stt=0;
				foreach($item as $quiz=> $rows){
					$stt++;
					$active=$focus='';
					$quiz_idx=$quiz;
					$quiz_id=$rows['id'];
					if(isset($data_ans[$id])) $active='active';
					if($page==$stt)  $focus='focus';
					?>
					<div class="item-col">
						<span class="item-act frm-item-quiz<?php echo $quiz_idx;?> <?php echo $active.' '.$focus;?>" data_id='<?php echo $rows['id'];?>' onclick="gotopage(<?php echo $number_quiz;?>,3,<?php echo $stt;?>)">
							<span class="label"><?php echo $stt;?></span>
						</span>
					</div>
					<?php 
				}
				?>
				<div class="clearfix"></div>

				<div class="box-action-footer"></div>
				<div class="btn-rs clearfix" id="btn-submit">
					<span class="btn-send btn-primary btn" type="submit" onclick="getRS()">Hoàn thành nhiệm vụ</span>
					<span class="btn-send btn-default btn" onclick="exit()">Thoát</span>
				</div>

			</div>

		</div>
	</div>
</div>	
<div class="box-action-footer"></div>
<script>
	var _Timer=null;
	var totalSeconds =<?php echo $totalSeconds;?>;
	var roothost = '<?php echo ROOTHOST?>';
	var exam_id='<?php echo $exam_id;?>';
	var isview='<?php echo $isview;?>';
	
	var name_json='ezWork_'+exam_id;

	function countTimer() {
		totalSeconds++;
		var hour = Math.floor(totalSeconds /3600);
		var minute = Math.floor((totalSeconds - hour*3600)/60);
		var seconds = totalSeconds - (hour*3600 + minute*60);
		if(hour < 10)
			hour = "0"+hour;
		if(minute < 10)
			minute = "0"+minute;
		if(seconds < 10)
			seconds = "0"+seconds;
		document.getElementById("timer").innerHTML = hour + ":" + minute + ":" + seconds;
	}
	function againQuiz(){
		localStorage.removeItem(name_json);
	}
	function getRS(){//type_answer 1 là dạng tracnghiem, 2 là tuluan. Đối với đề tự luận sẽ không tích kết quả
		var data=JSON.parse(localStorage.getItem(name_json));
		if(data['info_exam']['flag']!='L2' || data['info_exam']['rework']=='yes'){ // chỉ trạng thái chưa làm
			if(!data['info_exam']['data_answer']) data['info_exam']['data_answer']=new Object();
			var data_ans = data['info_exam']['data_answer'];
			
			quizList=data[exam_id];
			number_quiz=quizList.length;
			var num=0; // số câu trả lời đúng
			for(var i in quizList) {// duyệt từng câu hỏi
				// lấy một câu quiz, check câu quiz là dạng gì, từ đó tính kết quả đúng sai
				var quiz_id=quizList[i].id;
				ans=quizList[i].answers;
				var answers=JSON.parse(ans);
				if(Object.keys(answers).length>0 && Object.keys(data_ans).length>0){
					var quizTrue=true;
					for(var j in answers) {
						var istrue=answers[j].is_true;
						var isAns=false;
						for(var m in data_ans[quiz_id]){
							if(data_ans[quiz_id][m]==j){
								isAns=true;
								break;
							}
						}
						if(istrue=='yes' && isAns==false){
							quizTrue=false; break;
						}
					}
					if(quizTrue) num++;
				}
				
			}
			var score=(num/number_quiz)*100;
			var number_false=number_quiz-num;
			var num_ans=Object.keys(data_ans).length;
			
			// lưu kết quả làm bài
			$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/save_rs.php', {data,num,exam_id}, function(rep){
				// console.log(rep);
				againQuiz();
				$('#myModal').modal('show');
				$('#myModal .modal-dialog').removeClass('modal-lg');
				$('#myModal .modal-dialog').addClass('modal-notic');
				$('#myModal .modal-title').html('Bảng kết quả');
				var rs='<div class="box-rs">';
				rs+='<table class="table tbl-modal">';
				rs+='<tr>';
				rs+='<td class="name icon-1">Số câu đã trả lời:</td> <td class="number color1">'+num_ans+'</td>';
				rs+='</tr>';
				rs+='<tr>';
				rs+='<td class="name icon-2">Tổng số câu:</td> <td class="number color2">'+number_quiz+'</td>';
				rs+='</tr>';
				rs+='<tr>';
				rs+='<td class="name icon-2">Số câu trả lời đúng:</td> <td class="number color2">'+num+'( đạt '+score.toFixed(2)+'%)</td>';
				rs+='</tr>';
				rs+='</table>';
				rs+='<h4 class="text-center">Gửi kết quả thành công!</h4>';
				rs+='<button class="btn btn-success" data-dismiss="modal">Hoàn thành</button>';
				rs+='</div>';
				$('#content-modal').html(rs);
			});
		}else{
			alert('Bạn đang xem lại bài làm!');
		}
	}
	function exit(){
		if(confirm('Bạn có chắc muốn thoát khỏi chương trình!')){
			againQuiz();
			setTimeout(function(){window.location.href=roothost;}, 500);
		}
		else return false;
	}
	function rework(){
		if(confirm('Bạn có chắc muốn làm lại nhiệm vụ này?')){
			alert('Hệ thống đang nâng cấp!');
		}
	}
	if(!isview) _Timer = setInterval(countTimer, 1000);
	$('#myModal').on('hidden.bs.modal', function () {
		setTimeout(function(){window.location.href=roothost;}, 500);
	});
</script>
<?php } ?>