<hr>
<div class="card" style="margin-top: 25px;">
<h4 class="name name-report">Tiến độ hoàn thành</h4>
	<div class="row">
	<div class="col-sm-6 col-md-12">
	<div id="chart-work"></div>
	</div>
	<div class="col-sm-6 col-md-12">
	<div class="box-report-subject">
		<?php 
		global $_Nhiemvu;
		//echo '<pre>';
		//var_dump($_Nhiemvu);
		//echo '</pre>';
		function get_report_subject($mon){
			global $_Nhiemvu;
			$num_nowork=0;
			$num_work=0;
			if(isset($_Nhiemvu[$mon])){
				foreach($_Nhiemvu[$mon] as $key=>$item) { 
					$status  = $item['status'];
					if($status=='') $num_nowork++;
					if($status>=1) $num_work++;
				}
			}
			return array('num_nowork'=>$num_nowork,'num_work'=>$num_work);
		}
		$str='';
		$str_mon='';
		$i=0;
		$first_mon_work=0;
		$first_mon='';
		$first_work=0;
		foreach($_Conf_Subjects as $k=>$val) {
			$i++;
			$arr=get_report_subject($k);
			$num_work=$arr['num_work'];
			
				$num_nowork=$arr['num_nowork'];
				if($num_nowork==0) $value=0;
				else $value=ceil(($num_work/$num_nowork)*100);
				
				$mon=isset($_Conf_Subjects[$k]) ? $_Conf_Subjects[$k]['name']:"";
				$first_work+=$num_work;
				if($num_work>0){

					$str.=$value.",";
					$str_mon.="'".$mon."',";
				}
				?>
				<div class="box-report-item">
					<h4 class="name"><span class="ic ic<?php echo $i;?>"></span>Môn <?php echo $mon;?></h4>
					<div class="progress">
					  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $value;?>%" aria-valuenow="<?php echo $value;?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<ul class="list-inline">
					<li>Hoàn thành: <?php echo $num_work;?></li>
					<li>Chưa làm: <?php echo $num_nowork;?></li>
					</ul>
				</div>
				<?php 
			
		}
		$str=substr($str,0,-1);
		$str_mon=substr($str_mon,0,-1);
	
		?>
		
	</div>
	</div>
</div>
</div>
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  
  <script>
         var options = {
          series: [<?php echo $str;?>],
          chart: {
          height: 260,
          type: 'radialBar',
        },
        plotOptions: {
          radialBar: {
            dataLabels: {
              name: {
                fontSize: '22px',
              },
              value: {
                fontSize: '16px',
              },
              total: {
                show: true,
                label: 'Total',
                formatter: function (w) {
                  // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                  return <?php echo $first_work;?>
                }
              }
            }
          }
        },
        labels: [<?php echo $str_mon;?>],
        };

        var chart = new ApexCharts(document.querySelector("#chart-work"), options);
        chart.render();
      
      
    </script>
