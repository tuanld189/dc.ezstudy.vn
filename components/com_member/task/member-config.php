<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$err=$username=$password='';
?>
<div id='config-grade' class="vertical-center" style='width:100%;'>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var url = "<?php echo ROOTHOST;?>ajaxs/member/member-config-grade.php";
	$.get(url,function(req) {
		$("#config-grade").html(req);
	})
})
</script>