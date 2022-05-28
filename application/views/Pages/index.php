<?php 
	$this->ci =& get_instance();
	$user = $this->session->userdata('system.user');
	$is_head_office = $user['is_head_office']; 
	$branch_type = $user['branch_type']; 
        $module_name = $this->session->userdata('module_name');
	if(!($module_name)){
		$module_name = 'MIS';
	}        
?>
<script src="<?php echo base_url();?>media/js/jquery.highcharts.js"></script>
<script src="<?php echo base_url();?>media/js/jquery.highcharts.exporting.js"></script>

<script>
    
    $(document).ready(function() {        
        var refreshId = null;
        var i = 1;
        var is_head_office = '<?php echo $is_head_office ?>';
        var branch_type = '<?php echo $branch_type ?>';        
        if((is_head_office==1) || (branch_type=='A') || (branch_type=='Z') || (branch_type=='R')){
                       <?php if(empty($scheduled_tasks)){ ?> 
                               getNewDataForReport();
                       <?php } ?> 
        }
        
       
        // this is used for dashboard tabs  
        $(".update_date").hide();
        $(".tab_content").hide(); 	//Hide all content
        
        var sitename = '<?php echo SITE_NAME; ?>';
        if(sitename == 'cdip'){
            $("ul.tabs li").removeClass("active"); 
            $(".tab0").hide();
            $(".tab7").addClass("active").show(); 
            $("ul.tabs li:last").addClass("active").show(); //Activate first tab
            $(".tab_content:last").show(); //Show first tab content
            $("#dashboard_top_right").hide();
            $("#dashboard_top_left").css("width","100%");
            $(".update_date").show();
        }else{
            $("ul.tabs li:first").addClass("active").show(); //Activate first tab
            $(".tab_content:first").show(); //Show first tab content
        }
       
        
        // load page selected defualt slide-report; if session have value then load that sessioned-report or load first report
        // with selected tab on active
        var sessionReportData = <?php echo JSON_encode($this->session->userdata('graphicalReport')); ?>
        
        var reportIdArr = new Array(
                                    //'componentwise_total_borrower','componentwise_savings_info',                                    
                                    'componentwise_loans_info', 
                                    'branchwise_total_active_member',
                                    'branchwise_total_deposit',
                                    'branchwise_total_refund',
                                    'branchwise_total_borrower',
                                    'branchwise_total_disbursement',                                    
                                    'branchwise_total_recovery',
                                    'branchwise_total_due',                                    
                                    'branchwise_overdue',                                    
                                    'branchwise_current_due',                                    
                                    'branchwise_total_outstanding'
                                    );
        
         if((is_head_office==1) || (branch_type=='A') || (branch_type=='Z') || (branch_type=='R')){
                     $("#graph_report_switcher_link>li>a").removeClass('selected');
                if(sessionReportData == false || sessionReportData == ''){
                     $('#0>a').addClass('selected');
                    write_report_name_on_php_session(reportIdArr[0],0);
                    load_graphical_report(reportIdArr[0],0);
                }else{
                    $('#'+sessionReportData.reportTitleId+' >a').addClass('selected');
                    load_graphical_report(sessionReportData.reportNameId,sessionReportData.reportTitleId);
                    
                }
         }
           //load & run report-slide on body loading
           if((is_head_office==1) || (branch_type=='A') || (branch_type=='Z') || (branch_type=='R')){
                runReportSlide(i);
          }  
            // when li click is triggered then call this function and load the report with 'active' the tab menu
            
            $("#graph_report_switcher_link li").click(function() {
                $("#graph_report_switcher_link>li>a").removeClass('selected');
                var id = $(this).attr('id');
                $('#'+id+'>a').addClass('selected');
                write_report_name_on_php_session(reportIdArr[id],id);
                load_graphical_report(reportIdArr[id],id);
            });
            
             // this function is used for loading graphical-report
            function load_graphical_report(loadedReportName,report_title_id){
//                       alert(loadedReportName+', '+report_label_id);
                <?php if (($is_head_office == 1) || ($branch_type=='A') || ($branch_type=='Z') || ($branch_type=='R')): ?>
                    $("#graph_report_container").html('<div style="height:260px;vertical-align:middle;width:auto;"><img src="<?php echo base_url() ?>media/images/dashboard_images/ajax-loader.gif" alt="Loading..." border="0" style="padding: 85px 80px 25px;" /><br/>Please wait ... Report is generating.</div>');
                    var no_data_message = '<div style="padding:80px;color:indianred;font-weight:bold;font-size:15px;">No data found.<br/>This graphical report will be available when related data is available.</div>';
                    //                  
                    $.post('<?php echo site_url(); ?>/dashboard_graphical_reports/'+'ajax_'+loadedReportName, {max_branch_no:10,head_title:report_title_id}, function(data){
                        if(data.status == 'failure'){
                            $("#graph_report_container").html(no_data_message);
                            $("#see_all").html('');
                        }else{
                            $("#graph_report_container").html(data.graph_report);
                            if(loadedReportName == 'componentwise_total_disbursement' || loadedReportName == 'componentwise_total_borrower'){
                                $("#see_all").html('');
                            }else if((loadedReportName == 'componentwise_loans_info') || (loadedReportName == 'componentwise_savings_info')){
                                var all_branch = '<?php echo $this->lang->line('label_see_all_branch'); ?>'
                                $("#see_all").html('<a href="<?php echo site_url(); ?>/dashboard_graphical_reports/display_report/'+loadedReportName+'/'+report_title_id+'" target="_blank">See All Component</a>');
                            }else{
                                var all_branch = '<?php echo $this->lang->line('label_see_all_branch'); ?>'
                                $("#see_all").html('<a href="<?php echo site_url(); ?>/dashboard_graphical_reports/display_report/'+loadedReportName+'/'+report_title_id+'" target="_blank">'+all_branch+'</a>');
                            }    
                        }
                    },'json');
                <?php endif; ?>
        }
            
                        
             // On Click Event on dashboard tab-menu then happens - add active class
           $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active"); 	//Remove any "active" class
            $(this).addClass("active"); 	//Add "active" class to selected tab
            $(".tab_content").hide(); 	//Hide all tab content
            var activeTab = $(this).find("a").attr("href"); 	//Find the href attribute value to identify the active tab + content
            if(activeTab == '#tab7'){
                $("#dashboard_top_right").hide();
                $("#dashboard_top_left").css("width","100%");
                $(".update_date").show();
            }else{
                $("#dashboard_top_right").show();
                $("#dashboard_top_left").css("width","74%");
                $("#global_print_menu").css("display","none");
                $(".update_date").hide();
            }
            $(activeTab).fadeIn(); 	//Fade in the active ID content
            return false;
        }); 
            //when mouse-over sliding is stopped
            $('#graph_report_switcher_link >li >a').mouseover(function() {
                clearInterval(refreshId);
            });
            
            //when mouse-out; sliding is start again from that point 
            $('#graph_report_switcher_link >li >a').mouseout(function() {
                runReportSlide(i);
            });
            
            
            if($("#report_synchronize_option").val() == 1) {
                    $("#report_synchronize").html('<div style="height:260px;vertical-align:middle;width:auto;"><img src="<?php echo base_url() ?>media/images/dashboard_images/ajax-loader.gif" alt="Loading..." border="0" style="padding: 85px 80px 25px;" /></div>');
                    $.post("<?php echo site_url('scheduled_tasks'); ?>"+"/ajax_general_dashboard_report/", {module:'MIS'}, function(data){
                                    if(data.status == 'success' ){
                                        var url = "<?php echo base_url(); ?>index.php/pages/index/MIS";  
                                        $(location).attr('href',url);
                                    }else{
                                        load_graphical_report(reportIdArr[0],0);
                                    }
                            },"json"
                    );
             }
            
                      
            
            //When page loads...
            //calling this function from various point
            function runReportSlide(i){
//                alert(i);
                refreshId = setInterval(function() {
                    if(i==9) i=0;
                    sleep(i++);
                   
                }, 20000);
            }   
            
             // on-mouse-over this sleep function is called; on-mouse-out after it rotates again from last point it was
            
        function sleep(cnt)
        {
            $("#graph_report_switcher_link>li>a").removeClass('selected');
            $('#'+cnt+'>a').addClass('selected');
            write_report_name_on_php_session(reportIdArr[cnt],cnt);
            load_graphical_report(reportIdArr[cnt],cnt);
        }
            
            function getNewDataForReport()
            {
                var t= 0;
                var current_time = '';
                var old_time = '';
                var timeDuration = 40000;	// 40/1000 = 40 second
                var pageRefreshTime = 40000;	// 40/1000 = 40 second
                refreshId = setInterval(function() {
                    $.post("<?php echo site_url('dashboard_graphical_reports'); ?>"+"/ajax_read_dashboard_table/", {},
                    function(data)
                    {
                        if(data.status == 'failure' ){
                            
                        }
                        else
                        {
                            current_time = data.current_time;
                            //var url = "<?php echo base_url(); ?>";    
                            //$(location).attr('href',url);
                            old_time = data.last_update;
                            timeDifference = current_time - old_time;
                            if(timeDifference >= timeDuration)
                            {
                                $.post("<?php echo site_url('scheduled_tasks'); ?>"+"/ajax_general_dashboard_report/", {},
                                                function(data)
                                                {
                                                        if(data.status == 'failure' )
                                                        {
                                
                                                        }
                                                        else
                                                        {
                                                                var url = "<?php echo base_url(); ?>";    
                                                                $(location).attr('href',url);
                                                        }
                                                },"json"
                                                );
                            }
                        }
                    },"json"
                );		
                    t++;
                }, pageRefreshTime);
            }
            
            // onclick report-name & report-index-no is write on session --- now it's not used
            function write_report_name_on_php_session(loadedReportName,report_title_id){
                $.post('<?php echo site_url(); ?>/dashboard_graphical_reports/ajax_graphical_report_status_session_write', {reportName:loadedReportName,report_title_id:report_title_id}, function(data){
                    if(data.status == 'failure') console.log(data.message);
                    else console.log(data.message);
                });
            }
           
        });
		//for notification
		function messege_show()
		{	
			load_modal_form();
		}
		function load_modal_form()
		{
	
			$("#ContentRelDiv").modal(
			{
				//minHeight:minHig,minWidth:minWid,height:Hei,
				onOpen: function (dialog) {
					dialog.overlay.fadeIn('fast', function () {
						dialog.container.fadeIn('fast', function () {
							dialog.data.fadeIn('fast');
							msg=$("#notification_messege").html();
							$("#ContentRelDiv").html(msg);
						});
					});
				},
				onClose: function (dialog) {
					dialog.data.fadeOut('fast', function () {
						dialog.container.fadeOut('fast', function () {
							dialog.overlay.fadeOut('fast', function () {
								$.modal.close(); // must call this!
								$("#ContentRelDiv").html("");	
												
							});
						});
					});
			
				}
			});
			$('#simplemodal-container').css('height', 'auto');
			$('#simplemodal-container').css('padding', '5px');
		}
		//end
</script>
<style type="text/css">
	.blink
	{
		cursor: pointer; 
		cursor: hand; 
	}
	#blink_span
	{
		margin:-6px 0 0 0; 
		float: left;
	}
	#blink_font
	{
		color:#000000;
		font-size:14px;
	}
        .widget_news{font-family: verdana;font-size: 13px;font-weight: normal;border-bottom:dashed 1px #858585;padding:6px 2px 6px 26px;margin:0px 0px 5px 0px;background:url('<?php echo base_url()?>/media/images/dashboard_images/news.png') no-repeat scroll 2px 2px transparent;color:#444444; height:17px;}
</style>

<div id="dashboard_wrapper">
	<div id="dashboard_top_left">
		<div id="dashboard_entity">
		<?php 
			if($user['role_id']==4 && SITE_NAME=='ecb'){
				echo '<h1 style="color:#0075C8;margin-bottom:25px;">Off-Site Audit Dashboard</h1>';
				//echo '<pre>';print_r($user);echo '</pre>'; 
			}
		
		// dashboard class
		class Dashboard_builder extends MY_Controller
		{
				private $resources = array();
				private $sesionValue = null;
				private $scheduledTasksValue = null;
				private $is_head_office = null;
				private $branch_type = null;
				private $ci = null;
				function setSesionValue($a=null)
				{
					$this->sesionValue = $a;
				}
				function setIsHeadOfficeValue($is_headoffice=null)
				{
					$this->is_head_office = $is_headoffice;
				}
				function setBranchTypeValue($branch_type=null)
				{
					$this->branch_type = $branch_type;
				}
				function setScheduleTaskValue($scheduleTask=null)
				{
					$this->scheduledTasksValue = $scheduleTask;
				}
				function setBranchStatusValue($branchStatusReport=null)
				{
					$this->branchStatusValue = $branchStatusReport;
				}				
				function setLastUpdatedDateValue($last_updated_date=null)
				{
					$this->lastUpdatedDateValue = $last_updated_date;
				}
				function getSesionValue(){
					//echo '<h2>------------'.$this->sesionValue.'---------------</h2>';
				}
				function add_resource($group_name,$subgroup_name,$controller_name,$action1='',$action2='',$title,$image_src)
				{
					$this->resources[$group_name][$subgroup_name][] = array($controller_name,$action1,$action2,$title,$image_src);
				}
				
				function load_branch_status_report()
				{
                                    $ci=&get_instance();
                                    $ci->load->model('Config_general');
                                    $config_general=$ci->Config_general->read('dashboard');
					if(!empty($this->branchStatusValue))
					{
						//echo 'load_branch_status_report....';
						?>
						<div id="tab7" class="tab_content">
						   <div id="tab_content_report_body ">
                                                       <div  id="report_container">
								<table cellspacing="0px" cellpadding="0px" border="0" class="dashboardTable" style="width:100%;">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('label_branch_name'); ?></th>	
                                                                                        <?php 
                                                                                        if(isset($config_general->opening_date) && $config_general->opening_date==1){ 
                                                                                         echo '<th style="text-align:center;" >Branch Opening Date</th>';
                                                                                        }
                                                                                        if(isset($config_general->start_date) && $config_general->start_date==1){ 
                                                                                            echo '<th style="text-align:center;">Software Start date</th>';                      
                                                                                        } 
                                                                                        if(isset($config_general->branch_date) && $config_general->branch_date==1){ 
                                                                                            echo '<th style="text-align:center;" >'.$this->lang->line('label_branch_date').'</th>';
                                                                                        }
                                                                                        if(isset($config_general->total_samity) && $config_general->total_samity==1){
                                                                                            echo '<th style="text-align:center;">'.$this->lang->line('label_total_samity').'</th>';
                                                                                        }
                                                                                        if(isset($config_general->total_member) && $config_general->total_member==1){
                                                                                            echo '<th style="text-align:center;">'.$this->lang->line('label_total_member').'</th>';
                                                                                        }
                                                                                        if(isset($config_general->saving_balance) && $config_general->saving_balance==1){
                                                                                            echo '<th style="text-align:center;">Saving Balance</th>';
                                                                                        }
                                                                                        if(isset($config_general->total_borrower) && $config_general->total_borrower==1){
                                                                                            echo '<th style="text-align:center;">Total Borrower</th>';
                                                                                        }
											if(isset($config_general->last_month_due) && $config_general->last_month_due==1){
                                                                                            echo '<th style="text-align:center;">Last Month<br /> Due (Pr.)</th>';
                                                                                        }
                                                                                        if(isset($config_general->last_month_due) && $config_general->last_month_due==1){
                                                                                            echo '<th style="text-align:center;">Today\'s Due (Pr.)</th>';
                                                                                        }
                                                                                        if(isset($config_general->last_month_due) && $config_general->last_month_due==1){
                                                                                            echo '<th style="text-align:center;">Total Due (Pr.)</th>';
                                                                                        }
                                                                                        if(isset($config_general->over_due) && $config_general->over_due==1){
                                                                                           echo  '<th style="text-align:center;">Overdue (Pr.)</th>';
                                                                                        }
                                                                                        if(isset($config_general->current_due) && $config_general->current_due==1){
                                                                                           echo  '<th style="text-align:center;">Current due (Pr.)</th>';
                                                                                        }
                                                                                        if(isset($config_general->outstanding_amount) && $config_general->outstanding_amount==1){
                                                                                            echo '<th style="text-align:center;">Total Outstanding (Pr.)</th>';
                                                                                        }
                                                                                        ?>										
											<th style="text-align:right;"><?php echo $this->lang->line('label_lag'); ?></th>
										</tr>
									</thead>
									<?php 
									$i = 0;
									// pipe table configuration
									$pipeDiv = 1;
									$pipeHeight = 15;
									$pipeWidth = 5;
									$pipeBgColor = '#00BFFF';
									$pipeBgColorMinus = '#e2e2e2';
                                                                        $total_borrower = 0;
                                                                        $total_member = 0;
                                                                        $total_outstanding = 0;
                                                                        $total_due = 0;
                                                                        $todays_due = 0;
                                                                        $total_last_month_due = 0;
                                                                        $total_samity = 0;
                                                                        $total_overdue = 0;
                                                                        $current_due = 0;
                                                                        $total_saving_balance = 0;
									//echo "<pre>";print_r($this->branchStatusValue); die;
									foreach($this->branchStatusValue as $key=>$value):
                                                                            //echo "<pre>";print_r($value);
                                                                            ?>
									<tr <?php echo ($i%2==0)?'class="evenrow"':'class="oddrow"';?>>
										<td width="20%"><?php echo $value['branch_name']; ?></td>
                                                                                <?php if(isset($config_general->opening_date) && $config_general->opening_date==1){ ?>
                                                                                    <td width="14%" align="center" ><?php echo $value['opening_date']; ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->start_date) && $config_general->start_date==1){ ?>
                                                                                    <td width="14%" align="center" ><?php echo $value['start_date']; ?></td>
                                                                                <?php } 
                                                                                if(isset($config_general->branch_date) && $config_general->branch_date==1){ ?>
                                                                                    <td width="14%" align="center"><?php echo $value['branch_date']; ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->total_samity) && $config_general->total_samity==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['total_samity'])?'-':$value['total_samity']; ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->total_member) && $config_general->total_member==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['total_member'])?'-':$value['total_member']; ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->saving_balance) && $config_general->saving_balance==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['saving_balance'])?'-':number_format($value['saving_balance'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->total_borrower) && $config_general->total_borrower==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['total_borrower'])?'-':$value['total_borrower']; ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->last_month_due) && $config_general->last_month_due==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['last_month_due'])?'-':number_format($value['last_month_due'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->todays_due) && $config_general->todays_due==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['todays_due'])?'-':number_format($value['todays_due'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->todays_due) && $config_general->todays_due==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['due_amount'])?'-':number_format($value['due_amount'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->over_due) && $config_general->over_due==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['overdue'])?'-':number_format($value['overdue'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->current_due) && $config_general->current_due==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo number_format($value['current_due'],0,'.',','); ?></td>
                                                                                <?php }
                                                                                if(isset($config_general->outstanding_amount) && $config_general->outstanding_amount==1){ ?>
                                                                                    <td width="12%" align="center"><?php echo empty($value['outstanding_amount'])?'-':number_format($value['outstanding_amount'],0,'.',','); ?></td>
                                                                                <?php } ?>
										<?php  if($value['lag_int']==1){  ?>
                                                                                <td width="8%" align="right" style="color:#4C1700"><?php echo $value['lag']; ?></td>
                                                                                <?php  }elseif($value['lag_int']>=2){ ?> 
                                                                                <td width="8%" align="right" style="color:red"><?php echo $value['lag']; ?></td>
                                                                                <?php }else{ ?>
                                                                                <td width="8%" align="right" style="color:green"><?php echo $value['lag']; ?></td>
                                                                                <?php } ?>										
										
									</tr>
										<?php 
                                                                                $i++;
                                                                                $total_samity +=  isset($value['total_samity'])?$value['total_samity']:0;
                                                                                $total_borrower +=  isset($value['total_borrower'])?$value['total_borrower']:0;
                                                                                $total_member += isset($value['total_member'])?$value['total_member']:0;
                                                                                $total_saving_balance += isset($value['saving_balance'])?$value['saving_balance']:0;
                                                                                $todays_due += isset($value['todays_due'])?$value['todays_due']:0;
                                                                                $total_due += isset($value['due_amount'])?$value['due_amount']:0;
                                                                                $total_last_month_due += isset($value['last_month_due'])?$value['last_month_due']:0;
                                                                                $total_outstanding += isset($value['outstanding_amount'])?$value['outstanding_amount']:0;
                                                                                $total_overdue += isset($value['overdue'])?$value['overdue']:0;
                                                                                $current_due += isset($value['current_due'])?$value['current_due']:0;
                                                                                ?>
									<?php endforeach; ?>
                                                                        <tr>
                                                                            <th>TOTAL</th>
                                                                            
                                                                            <?php if(isset($config_general->opening_date) && $config_general->opening_date==1){ ?>
                                                                                    <th>&nbsp;</th>
                                                                                <?php }
                                                                                if(isset($config_general->start_date) && $config_general->start_date==1){ ?>
                                                                                    <th>&nbsp;</th>
                                                                                <?php } 
                                                                                if(isset($config_general->branch_date) && $config_general->branch_date==1){ ?>
                                                                                    <th align="center" >&nbsp;</th>
                                                                                <?php }
                                                                                if(isset($config_general->total_samity) && $config_general->total_samity==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo $total_samity; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->total_member) && $config_general->total_member==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo $total_member; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->saving_balance) && $config_general->saving_balance==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($total_saving_balance)?number_format($total_saving_balance,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->total_borrower) && $config_general->total_borrower==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo $total_borrower; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->last_month_due) && $config_general->last_month_due==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($total_last_month_due)?number_format($total_last_month_due,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->todays_due) && $config_general->todays_due==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($todays_due)?number_format($todays_due,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->todays_due) && $config_general->todays_due==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($total_due)?number_format($total_due,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->over_due) && $config_general->over_due==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($total_overdue)?number_format($total_overdue,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->current_due) && $config_general->current_due==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($current_due)?number_format($current_due,0,'.',','):0; ?></th>
                                                                                <?php }
                                                                                if(isset($config_general->outstanding_amount) && $config_general->outstanding_amount==1){ ?>
                                                                                    <th style="text-align:center;"><?php echo !empty($total_outstanding)?number_format($total_outstanding,0,'.',','):0; ?></th>
                                                                                <?php } ?>                                                                                 
                                                                                <th style="text-align:center;">&nbsp;</th>
                                                                        </tr>
								</table>
                                                       </div>
							</div>
						</div>
						<?php
					}
				}
				
				function load_snapshot_dashboard_report()
				{
					//print_r($this->scheduledTasksValue);
					if(!empty($this->scheduledTasksValue))
					{
						echo '<div id="tab0" class="tab_content" style="float:left;">';
							echo '<div id="at_a_glance">';
								echo '<div id="inshort_info">';
									echo '<ul id="inshort_info_list">';
										foreach($this->scheduledTasksValue as $k1 => $v1)
										{
											if($k1 == 'general')
											{
												foreach($v1 as $k2 => $v2):
													if($k2=='branch_info'):
														echo '<li> 
														<div class="listing_big_image blue_bg" style="height:80px;"><img src="'.base_url().'media/images/dashboard_images/homesl-1.png" border="0" width="50px" height="50px" style="margin-top:15px;"/></div>
														<div class="listing_container" style="height:80px;">';
														foreach($v2 as $k3=>$v3):
															echo '<ul class="blue">';
															foreach($v3 as $k4=>$v4):
																echo ($k4=='total_branch')?'<li><span class="listing_head">'.$this->lang->line('label_branch').' :</span> <span class="listing_result">'.(($k4=='total_branch')?number_format($v4,0,'',','):'').'</span></li>':'';
																echo ($k4=='total_samity')?'<li><span class="listing_head">'.$this->lang->line('label_samity').' :</span> <span class="listing_result">'.(($k4=='total_samity')?number_format($v4,0,'',','):'').'</span></li>':'';
															endforeach;                                                                                                                        
															echo '</ul>';
														endforeach;
                                                                                                                echo '<span style="color: green;float: right; margin-top: -20px;">Last Update Date: '.date('d M, Y h:i A', strtotime($this->lastUpdatedDateValue)).'</span>';
													endif;
													if($k2=='member_info'):
														foreach($v2 as $k3=>$v3):
															echo '<ul class="blue">';
															foreach($v3 as $k4=>$v4):
                                                                                                                            if(($k4=='total_active_member') || ($k4=='total_active_male_member') || ($k4=='total_active_female_member')){
																echo ($k4=='total_active_member')?'<li><span class="listing_head">Active Members: Total:</span> <span class="listing_result">'.(($k4=='total_active_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
																echo ($k4=='total_active_male_member')?'<li><span class="listing_head">Male:</span> <span class="listing_result">'.(($k4=='total_active_male_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
																echo ($k4=='total_active_female_member')?'<li><span class="listing_head">Female:</span> <span class="listing_result">'.(($k4=='total_active_female_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
                                                                                                                            }
                                                                                                                            if(($k4=='total_inactive_member') || ($k4=='total_inactive_male_member') || ($k4=='total_inactive_female_member')){
                                                                                                                                echo ($k4=='total_inactive_member')?'<br/><li><span class="listing_head">Inactive Members: Total:</span> <span class="listing_result">'.(($k4=='total_inactive_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
                                                                                                                                echo ($k4=='total_inactive_male_member')?'<li><span class="listing_head">Male:</span> <span class="listing_result">'.(($k4=='total_inactive_male_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
																echo ($k4=='total_inactive_female_member')?'<li><span class="listing_head">Female:</span> <span class="listing_result">'.(($k4=='total_inactive_female_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
                                                                                                                            }
                                                                                                                            if(($k4=='total_active_inactive_member') || ($k4=='total_closed_member')){
                                                                                                                                echo ($k4=='total_active_inactive_member')?'<br/><li><span class="listing_head">Total Members:</span> <span class="listing_result">'.(($k4=='total_active_inactive_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
																echo ($k4=='total_closed_member')?'<li><span class="listing_head">Total Closed Members:</span> <span class="listing_result">'.(($k4=='total_closed_member')?number_format($v4,0,'',','):'0').'</span></li>':'';
                                                                                                                            }
															endforeach;
															echo '</ul>';
														endforeach;
														echo '</div>
														</li>';
													endif;
												endforeach;
												//savings info
												foreach($v1 as $k2=>$v2):
													if($k2=='savings_info')
													{
														
                                                                                                            // current savings info
                                                                                                            echo '<li>
                                                                                                                    <div class="listing_big_image orange_bg"><img src="'.base_url().'media/images/dashboard_images/savings_info.png" border="0" width="50px" height="50px"/></div>
                                                                                                                    <div class="listing_container">
                                                                                                                    ';
                                                                                                            foreach($v2 as $k3=>$v3){
                                                                                                                    echo '<ul class="orange" style="margin-top:15px;">';
                                                                                                                    foreach($v3 as $k4=>$v4){
                                                                                                                            echo ($k4=='todays_deposit')?'<li><span class="listing_head">Today&#39;s Deposit :</span> <span class="listing_result">'.(($k4=='todays_deposit')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                            echo ($k4=='todays_refund')?'<li><span class="listing_head">Today&#39;s Refund :</span> <span class="listing_result">'.(($k4=='todays_refund')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                            echo ($k4=='todays_refund')?'<br/>':'';
                                                                                                                            echo ($k4=='current_saving_balance')?'<li><span class="listing_head">Total Savings Balance :</span> <span class="listing_result">'.(($k4=='current_saving_balance')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                    }
                                                                                                                    echo '</ul>';
                                                                                                            }							
                                                                                                            echo '
                                                                                                                    </div>
                                                                                                            </li>';
                                                                                                            // cumulative savings info
//                                                                                                            echo '<li>
//																<div class="listing_big_image orange_bg"><img src="'.base_url().'media/images/dashboard_images/homesl-2.png" border="0" width="50px" height="50px"/></div>
//																<div class="listing_container">
//																';
//															foreach($v2 as $k3=>$v3){
//																echo '<ul class="orange" style="margin-top:15px;">';
//																foreach($v3 as $k4=>$v4){
//																	echo ($k4=='total_deposit')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_deposit').' :</span> <span class="listing_result">'.(($k4=='total_deposit')?number_format($v4,0,'',','):'').'</span></li>':'';
//																	echo ($k4=='total_interest')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_interest').' :</span> <span class="listing_result">'.(($k4=='total_interest')?number_format($v4,0,'',','):'').'</span></li>':'';
//																	echo ($k4=='total_interest')?'<br/>':'';
//                                                                                                                                        echo ($k4=='total_refund')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_refund').' :</span> <span class="listing_result">'.(($k4=='total_refund')?number_format($v4,0,'',','):'').'</span></li>':'';
//                                                                                                                                        echo ($k4=='saving_balance')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_saving_balance').' :</span> <span class="listing_result">'.(($k4=='saving_balance')?number_format($v4,0,'',','):'').'</span></li>':'';
//																}
//																echo '</ul>';
//															}							
//															echo '
//																</div>
//															</li>';
													}
												endforeach;
                                                                                                
                                                                                               //loan info current
												foreach($v1 as $k2=>$v2):
													if($k2=='loans_info') 
													{
														echo '<li>
                                                                                                                    <div class="listing_big_image green_bg" style="height: 85px;"><img src="'.base_url().'media/images/dashboard_images/loan-multi.png" border="0" width="50px" style="padding-top: 15px;"/></div>
                                                                                                                    <div class="listing_container" style="height: 85px;">';
                                                                                                                foreach($v2 as $k3=>$v3):
                                                                                                                    echo '<ul class="green">';
                                                                                                                    foreach($v3 as $k4=>$v4){
                                                                                                                        echo ($k4=='total_last_month_borrower')?'<li><span class="listing_head">Total Borrower :</span> <span class="listing_result">'.(($k4=='total_last_month_borrower')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='total_female_borrower')?'<li><span class="listing_head">Total Female :</span> <span class="listing_result">'.(($k4=='total_female_borrower')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='total_male_borrower')?'<li><span class="listing_head">Total Male :</span> <span class="listing_result">'.(($k4=='total_male_borrower')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='todays_disbursement')?'<br/>':'';
                                                                                                                        echo ($k4=='todays_disbursement')?'<li><span class="listing_head">Today&#39;s Disbursement :</span> <span class="listing_result">'.(($k4=='todays_disbursement')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='todays_recovery')?'<li><span class="listing_head">Today&#39;s Recovery :</span> <span class="listing_result">'.(($k4=='todays_recovery')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='todays_due')?'<br/>':'';
                                                                                                                        echo ($k4=='todays_due')?'<li><span class="listing_head">Today&#39;s Due :</span> <span class="listing_result">'.(($k4=='todays_due')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        
                                                                                                                        echo ($k4=='total_current_due')?'<li><span class="listing_head">Total Due :</span> <span class="listing_result">'.(($k4=='total_current_due')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='current_due')?'<li><span class="listing_head">Current&#39;s Due :</span> <span class="listing_result">'.(($k4=='current_due')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        
                                                                                                                        echo ($k4=='total_overdue')?'<br/>':'';                                                                                                                        
                                                                                                                        echo ($k4=='total_overdue')?'<li><span class="listing_head">Total Overdue :</span> <span class="listing_result">'.(($k4=='total_overdue')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        echo ($k4=='total_current_loan_outstanding')?'<li><span class="listing_head">Total Outstanding :</span> <span class="listing_result">'.(($k4=='total_current_loan_outstanding')?number_format($v4,0,'',','):'').'</span></li>':'';
                                                                                                                        
                                                                                                                    }
                                                                                                                    echo '</ul>';
                                                                                                                endforeach;                                                        						
                                                                                                                echo '
                                                                                                                    </div>
                                                                                                                </li>';
													}
												endforeach;
                                                                                                
												//loan info cumulative
//												foreach($v1 as $k2=>$v2):
//													if($k2=='loans_info')
//													{
//														echo '<li>
//                                                                                                                    <div class="listing_big_image green_bg"><img src="'.base_url().'media/images/dashboard_images/sav-report.png" border="0" width="50px"/></div>
//                                                                                                                    <div class="listing_container">';
//                                                                                                                foreach($v2 as $k3=>$v3):
//                                                                                                                    echo '<ul class="green">';
//                                                                                                                    foreach($v3 as $k4=>$v4){
//                                                                                                                        echo ($k4=='total_loans')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_borrower').' :</span> <span class="listing_result">'.(($k4=='total_loans')?number_format($v4,0,'',','):'').'</span></li>':'';
//                                                                                                                        echo ($k4=='total_loan_disburse_amount')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_disbursement').' :</span> <span class="listing_result">'.(($k4=='total_loan_disburse_amount')?number_format($v4,0,'',','):'').'</span></li>':'';
//                                                                                                                        echo ($k4=='total_loan_disburse_amount')?'<br/>':'';
//                                                                                                                        echo ($k4=='total_loan_recovery_amount')?'<li><span class="listing_head">'.$this->lang->line('label_cumulative_recovery').' :</span> <span class="listing_result">'.(($k4=='total_loan_recovery_amount')?number_format($v4,0,'',','):'').'</span></li>':'';
//                                                                                                                        echo ($k4=='total_loan_outstanding')?'<li><span class="listing_head">'.$this->lang->line('label_loan_outstanding').' :</span> <span class="listing_result">'.(($k4=='total_loan_outstanding')?number_format($v4,0,'',','):'').'</span></li>':'';
//                                                                                                                    }
//                                                                                                                    echo '</ul>';
//                                                                                                                endforeach;                                                        						
//                                                                                                                echo '
//                                                                                                                    </div>
//                                                                                                                </li>';
//													}
//												endforeach;
                                                                                                				
											}
										}
								echo '</ul>';
							echo '	</div>
								</div>';
						
						$graph_report_title = $this->lang->line('lebel_graph_report_title');
                                                $k = 0;
                                                if($this->is_head_office!=1){
                                                    unset($graph_report_title[0]);
                                                    $k = 1;
                                                }
						echo '<div id="graphTab">
							<div id="graph_report">
                                                            <div id="graph_report_switcher">                                                                <div id="graphical_report_title">'.$this->lang->line('label_graphical_report').'</div>
                                                                <ul id="graph_report_switcher_link">';
                                                                for ($i=$k;$i<count($graph_report_title);$i++ ){
                                                                    echo '<li id="'.$i.'"><a href="javascript:" id="'.$i. '" style="border:bottom:none;" class="" title="'.$graph_report_title[$i].'">' . $graph_report_title[$i] . '</a></li>';
                                                                   
                                                                }
                                            echo '</ul>
                                                </div>';
                                            echo '<div id="graph_report_container"></div>';
                                            echo '<div id="see_all" style="float:right;"></div>';
                                            echo '</div>
                                                  </div>';
                                            echo '<input type="hidden" value="2" id="report_synchronize_option" />';
                                            echo '</div>';
					}else{
						echo '<div id="tab0" class="tab_content" style="float:left;">';
							echo '<div id="at_a_glance">';
								echo '<div id="inshort_info">';
									echo 'Report is generating please wait ...';
										echo '<div id="report_synchronize"></div>';
									echo '<input type="hidden" value="1" id="report_synchronize_option" />';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					return true;
				}
				
				function generate_dashboard_builder()
				{
					$this->ci =& get_instance();
					$custom_img_width = '70px';$custom_img_height = '70px';
					$group_counter = 1;
					echo '<ul class="tabs">';
						if(($this->is_head_office==1) || ($this->branch_type=='A') || ($this->branch_type=='Z') || ($this->branch_type=='R'))
						{
							echo '<li class="tab0 active"><a href="#tab0">'.$this->lang->line('label_organization_status').'</a></li>';
							$group_counter = 1;
						}
						foreach( $this->resources as $k1=>$v1 ):
							echo '<li class="tab'.$group_counter.'"><a href="#tab'.$group_counter.'">'.$k1.'</a></li>';
							$group_counter++;
						endforeach;
						if(($this->is_head_office==1) || ($this->branch_type=='A') || ($this->branch_type=='Z') || ($this->branch_type=='R'))
						{
							echo '<li class="tab7"><a href="#tab7">'.$this->lang->line('label_branch_status').'</a></li>';
							$group_counter = 1;
						}                                        
					echo '</ul>';
                                        if(($this->is_head_office==1) || ($this->branch_type=='A') || ($this->branch_type=='Z') || ($this->branch_type=='R')){
                                            echo '<span class="update_date" style="color: #779221;float: right;font-size: 11px; margin-top: -32px;">Update Date:</span><br>';
                                            echo '<span class="update_date" style="color: green;float: right; margin-top: -18px;">'.date('d M, Y h:i A', strtotime(isset($this->lastUpdatedDateValue)?$this->lastUpdatedDateValue:'1970-12-12')).'</span>';
                                        }
					$subgroup_counter = 1;                                        
					echo '<div class="tab_container">';
					if(($this->is_head_office==1) || ($this->branch_type=='A') || ($this->branch_type=='Z') || ($this->branch_type=='R'))
					{
						$this->load_snapshot_dashboard_report();
					}
					foreach( $this->resources as $k1=>$v1 ):	
						echo '<div id="tab'.$subgroup_counter.'" class="tab_content" >';
							echo '<div class="tab_content_body">';
							foreach($v1 as $k2 => $v2)
							{
								echo (isset($k2) && $k2!='')?'<h3 class="tab_title">'.$k2.'</h3>':'';
								foreach($v2 as $k3 => $v3)
								{
									$controller_name = (isset($v3[0])&&$v3[0]!='')?$v3[0]:'';
									$action1_name = (isset($v3[1])&&$v3[1]!='')?$v3[1]:'';
									$action2_name = (isset($v3[2])&&$v3[2]!='')?$v3[2]:'';
									$action1 = 'index.php/'.$v3[0].'/'.$v3[1];
									$action2 = 'index.php/'.$v3[0].'/'.$v3[2];
									$title = (isset($v3[3])&&$v3[3]!='')?$v3[3]:'';
									$image_src = (isset($v3[4])&&$v3[4]!='')?$v3[4]:'media/images/dashboard_images/work_area.png';
									if($this->ci->is_action_permitted($controller_name,$action1_name))
									{
										echo '<div class="db_content">';
											if($this->ci->is_action_permitted($controller_name,$action2_name))
											{
												echo '<div class="float_tab">';
													echo '<a class="db_add" href="'.base_url().$action2.'">Add</a>';
												echo '</div>';
											}
											echo '<a href="'.base_url().$action1.'">';
											echo img(array('src'=>$image_src,'border'=>'0','width'=>$custom_img_width,'height'=>$custom_img_height,'alt'=>''));
											echo '</a><br/>';
											echo '<a href="'.base_url().$action1.'">'.$title.'</a>';
										echo '</div>';
									}
								}
							}
							echo '</div>';
						echo '</div>';	
						
						$subgroup_counter++;
					endforeach;
					if(($this->is_head_office==1) || ($this->branch_type=='A') || ($this->branch_type=='Z') || ($this->branch_type=='R'))
					{
						$this->load_branch_status_report();
					}
					echo '</div>';
				}
				
		}
		$sessionReportName = $this->session->userdata('grahicalReport.reportName');
		if(empty($sessionReportName)){
			$sessionReportName = 'componentwise_total_disbursement';
		}
                
                            
		$dashboard = new Dashboard_builder();
		if(($is_head_office == 1) || ($branch_type=='A') || ($branch_type=='Z') || ($branch_type=='R'))
		{
			$dashboard->setSesionValue($sessionReportName);
			if(!empty($scheduled_tasks))
			{
				$dashboard->setScheduleTaskValue($scheduled_tasks);
				$dashboard->setLastUpdatedDateValue($last_updated_date);
			}
			if(!empty($branch_status))
			{
				$dashboard->setBranchStatusValue($branch_status);
				//echo "<pre>";print_r($branch_status);echo "</pre>";
			}
			$dashboard->setIsHeadOfficeValue($is_head_office);
                        if(!empty($branch_type)){
                            $dashboard->setBranchTypeValue($branch_type);
                        }
		}                
		
		
				//$group_name='Reports';
				//$subgroup_name='Branch, Samity & Member (in Total)';
				//$dashboard->add_resource($group_name,$subgroup_name,'employees','index','add','Employee','media/images/dashboard_images/emp_user.png');

				$group_name=$this->lang->line('label_daily_operation');
				$subgroup_name=$this->lang->line('label_operation');
				$dashboard->add_resource($group_name,$subgroup_name,'employees','index','add',$this->lang->line('label_employees'),'media/images/dashboard_images/emp_user.png');
				$dashboard->add_resource($group_name,$subgroup_name,'members','index','add',$this->lang->line('label_members'),'media/images/dashboard_images/members.png');
				$dashboard->add_resource($group_name,$subgroup_name,'savings','index','add',$this->lang->line('label_savings'),'media/images/dashboard_images/savings.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loans','index','add',$this->lang->line('label_regular_loan_account'),'media/images/dashboard_images/loan.png');
				$dashboard->add_resource($group_name,$subgroup_name,'one_time_loan_accounts','index','add',$this->lang->line('label_one_time_loan_account'),'media/images/dashboard_images/loan-one.png');
				$subgroup_name=$this->lang->line('label_process');
				$dashboard->add_resource($group_name,$subgroup_name,'transactions','auto_process','',$this->lang->line('label_auto_process'),'media/images/dashboard_images/auto_process_big.png');
				$dashboard->add_resource($group_name,$subgroup_name,'transaction_authorizations','authorization_index','',$this->lang->line('label_transaction_authorization'),'media/images/dashboard_images/transaction_process.png');
				$dashboard->add_resource($group_name,$subgroup_name,'process_day_ends','index','',$this->lang->line('label_process_day_end'),'media/images/dashboard_images/day_end_process.png');
				$dashboard->add_resource($group_name,$subgroup_name,'process_month_ends','index','',$this->lang->line('label_process_month_end'),'media/images/dashboard_images/month_process.png');
				
				$subgroup_name=$this->lang->line('label_configuration');
				$subgroup_name=$this->lang->line('label_config_general');
				$dashboard->add_resource($group_name,$subgroup_name,'config_generals','index','',$this->lang->line('label_config_general'),'media/images/dashboard_images/gen_conf.png');
				$dashboard->add_resource($group_name,$subgroup_name,'config_auto_ids','index','',$this->lang->line('label_config_auto_id'),'media/images/dashboard_images/auto-config.png');
				$dashboard->add_resource($group_name,$subgroup_name,'config_holidays','index','',$this->lang->line('label_config_holiday'),'media/images/dashboard_images/calender.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_branches','index','add',$this->lang->line('label_branch_info'),'media/images/dashboard_images/branch_area.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_funding_organizations','index','add',$this->lang->line('label_funding_organization'),'media/images/dashboard_images/fund-org.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_areas','index','add',$this->lang->line('label_area'),'media/images/dashboard_images/area.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_zones','index','add',$this->lang->line('label_zone'),'media/images/dashboard_images/zone-map.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_regions','index','add',$this->lang->line('label_region'),'media/images/dashboard_images/region.png');
				$dashboard->add_resource($group_name,$subgroup_name,'samities','index','add',$this->lang->line('label_samity'),'media/images/dashboard_images/samity.png');
				$subgroup_name=$this->lang->line('label_address_configuration');
				$dashboard->add_resource($group_name,$subgroup_name,'po_divisions','index','add',$this->lang->line('label_division'),'media/images/dashboard_images/division-icon.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_districts','index','add',$this->lang->line('label_district'),'media/images/dashboard_images/district-icon.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_thanas','index','add',$this->lang->line('label_thana'),'media/images/dashboard_images/police-station.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_unions_or_wards','index','add',$this->lang->line('label_union_ward'),'media/images/dashboard_images/union.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_village_or_blocks','index','add',$this->lang->line('label_villages_block'),'media/images/dashboard_images/village.png');
				$dashboard->add_resource($group_name,$subgroup_name,'po_working_areas','index','add',$this->lang->line('label_working_area'),'media/images/dashboard_images/work_area.png');

				$group_name=$this->lang->line('label_admin');
				$subgroup_name='';
				$dashboard->add_resource($group_name,$subgroup_name,'users','index','',$this->lang->line('label_manage_user'),'media/images/dashboard_images/user.png');
				$dashboard->add_resource($group_name,$subgroup_name,'user_roles','index','',$this->lang->line('label_manage_user_role'),'media/images/dashboard_images/user-role.png');
				$dashboard->add_resource($group_name,$subgroup_name,'users','change_password','',$this->lang->line('label_change_password'),'media/images/dashboard_images/chg_pwd.png');
				$dashboard->add_resource($group_name,$subgroup_name,'user_audit_trails','index','',$this->lang->line('label_user_audit_trail'),'media/images/dashboard_images/audit_trail.png');
				$dashboard->add_resource($group_name,$subgroup_name,'user_access_logs','index','',$this->lang->line('label_system_log'),'media/images/dashboard_images/access_log.png');

				$group_name=$this->lang->line('label_loan_savings');
				$subgroup_name=$this->lang->line('label_loans');
				$dashboard->add_resource($group_name,$subgroup_name,'loan_product_categories','index','add',$this->lang->line('label_loan_product_category'),'media/images/dashboard_images/loan-category.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loan_purposes','index','add',$this->lang->line('label_loan_purpose'),'media/images/dashboard_images/loan-pur.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loan_products','index','add',$this->lang->line('label_loan_product'),'media/images/dashboard_images/loan-prod.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loans','index','add',$this->lang->line('label_regular_loan_account'),'media/images/dashboard_images/loan.png');
				$dashboard->add_resource($group_name,$subgroup_name,'one_time_loan_accounts','index','add',$this->lang->line('label_one_time_loan_account'),'media/images/dashboard_images/loan-one.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loan_reschedules','index','add',$this->lang->line('label_loan_reschedule'),'media/images/dashboard_images/loan-transaction.png');
				$dashboard->add_resource($group_name,$subgroup_name,'loan_transactions','index','add',$this->lang->line('label_loan_transaction'),'media/images/dashboard_images/loan-reschedule.png');
				$subgroup_name=$this->lang->line('label_savings');
				$dashboard->add_resource($group_name,$subgroup_name,'saving_products','index','add',$this->lang->line('label_saving_product'),'media/images/dashboard_images/savings-prod.png');
				$dashboard->add_resource($group_name,$subgroup_name,'savings','index','add',$this->lang->line('label_savings'),'media/images/dashboard_images/savings.png');
				$dashboard->add_resource($group_name,$subgroup_name,'saving_deposits','index','add',$this->lang->line('label_saving_deposit'),'media/images/dashboard_images/hand-penny.png');
				$dashboard->add_resource($group_name,$subgroup_name,'saving_withdraws','index','add',$this->lang->line('label_saving_withdraw'),'media/images/dashboard_images/saving-withdrawl.png');
				$dashboard->add_resource($group_name,$subgroup_name,'skt_collections','index','add',$this->lang->line('label_skt_collection'),'media/images/dashboard_images/savings2_040511.png');
				$dashboard->generate_dashboard_builder();
		?>
		</div>
	</div>

<script>
$(".float_tab").hide();
$('.db_content').hover(function() {
        $(this).find('.float_tab').show();
    },
    function () {
        $(this).find('.float_tab').hide();
    }
);
</script>
	<div id="dashboard_top_right">
		<div id="dailyReportViewer">
			<div id="daily_report_viewer_container">
				<div class="widget">
						<?php 
							if($user['is_head_office']==1 && !empty($notification_for_ho_user))
							{
						?>
							<!--notification-->
							<br />
							<div class="blink" onclick="messege_show();">
								<span id="blink_span"><img src="<?php echo base_url() ?>media/images/new-messages-red.png" style="height:40px; text-align:center;" ></span><font id="blink_font"><?php echo $notification_for_ho_user['title']; ?></font><br />
							</div>
							<div class="widget_messege" style="display:none" id="notification_messege">
								<p><?php echo $notification_for_ho_user['body'] ?></p>
							</div>
							<br/>
						<?php } ?>
						
						<!--end notifiation-->
						<?php 
							$allow_branch_level_apps = array('demo'=>'demo','demo2'=>'demo2','demo3'=>'demo3','demo4'=>'demo4','demo5'=>'demo5','demomob'=>'demomob',
							'cdip'=>'cdip','bnps'=>'bnps','asks'=>'asks','kks'=>'kks','gjus'=>'gjus','pbk'=>'pbk','setu'=>'setu','sdc'=>'sdc',
							'brdbiresppw'=>'brdbiresppw','osaca'=>'osaca', );							 
							if(($user['is_head_office']==1) || isset($allow_branch_level_apps[SITE_NAME])){ 
						?>
                                <div class="widget_mobile">
                                    <span style="float:left;"><a href="index.php/mobile_apps/index">Mobile Version</a></span>
                                    <span  style="margin:-6px 0 0 0; float: left;"><img src="<?php echo base_url() ?>media/images/dashboard_images/new.gif"></span>
                                </div>

                                <!--if number of news of related mfi is more than 0 then show news link-->
                                <?php if (isset($news->news_list) && $news->news_list > 0) { ?>
                                <div class="widget_news">
                                    <span style="float:left;"><a href="<?php echo site_url('news') ?>" target="_blank">News</a></span>
                                    <span  style="margin:-6px 0 0 0; float: left;"><img src="<?php echo base_url() ?>media/images/dashboard_images/new.gif"></span>
                                </div>
                                <?php } ?>
							
							<!--div class="widget_bills">
								<span style="float:left;"><a href="index.php/bills/index">Bills Information</a></span>
								<span  style="margin:-6px 0 0 0; float: left;"><img src="<?php echo base_url() ?>media/images/dashboard_images/new.gif"></span>
							</div-->
						<?php } ?>
					<h3 class="widget_h3"><?php echo $this->lang->line('label_home_page_left_menu_header'); ?></h3>
					<?php
						class Report_builder
						{ 
							private $resources=array();
							private $groups=array();
							private $ci=null;
							function add_group($name,$image)
							{
								$this->groups[$name]=$image;
							}
							function add_resource($group_name,$resource_name,$controller,$action='',$subgroup_name='')
							{
								$this->resources[$group_name][]=array($resource_name,$controller,$action,$subgroup_name);
							}
							function generate_menu_list()
							{
								echo "\n<ul>\n";
								$this->ci =& get_instance();
								foreach($this->groups as $group_name => $image)
								{
									$resources=$this->resources[$group_name];
									$buffer='';
									if(count($resources)>0)
									{
										$sub_group ="";					
										foreach($resources as $resource)
										{
											$action = $resource[2];
											if(empty($action)) $action='index';		
											if($this->ci->is_action_permitted($resource[1],$action))				
											{
												if (!empty($resource[3]))
												{
													if($sub_group!=$resource[3])
													{
														if(!empty($sub_group))
														{
															$buffer.= "</ul>";
														}
														$sub_group=$resource[3];
														$buffer.=  "<li><a href='#' onclick='return false;'>$sub_group</a>\n";
														$buffer.=  "<ul>";
													}
												}else{
													if (!empty($sub_group)){
														$buffer.= "</ul>\n";
														$sub_group='';
													}
												}
												$buffer.= "<li>".anchor("/$resource[1]/$resource[2]","$resource[0]")."</li>\n";
											}
										}
										if (!empty($sub_group) && !empty($buffer)){
											$buffer.=  "</ul>\n";
											$sub_group='';						
										}										
									}
									if(!empty($buffer)){
											$img=$this->groups[$group_name];
											if(!empty($img))
												echo "<li>&nbsp;$group_name&nbsp;\n";
											else
												echo "<li>&nbsp;$group_name&nbsp;\n";
											echo "<ul>\n";
											echo $buffer;
											echo "</ul>\n";
									}
								}
								echo "</ul>";
							}
						}
						$config_general = $this->ci->get_general_configuration();
						//echo "<pre>";print_r($config_general); die;
						$report = new Report_builder();
							$pksf_pomis="";
							$pksf_pomis_group="";
							if(!empty($config_general['name_before_pomis_reports']))
							{
							   $pksf_pomis=$config_general['name_before_pomis_reports']." "; 
							   $pksf_pomis_group=$config_general['name_before_pomis_reports']."-";
							}
							$group_name=$pksf_pomis_group.$this->lang->line('label_po_mis_report');
							$report->add_group($group_name, '');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_1_report'),'po_mis_reports','po_mis_1_index');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_2_report'),'po_mis_reports','po_mis_2_index');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_2A_report'),'po_mis_reports','po_mis_2A_index');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_3_report'),'po_mis_reports','po_mis_3_index');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_3A_report'),'po_mis_reports','po_mis_3A_index');
							$report->add_resource($group_name, $pksf_pomis.$this->lang->line('label_po_mis_5a_report'),'po_mis_reports','po_mis_5a_index');

							$group_name=$this->lang->line('label_register_report');
							$report->add_group($group_name, '');
							$report->add_resource($group_name, $this->lang->line('label_admission_register'),'admission_register_index');
							$report->add_resource($group_name, $this->lang->line('label_savings_refund_register'),'register_reports','savings_refund_register_report_index');
							$report->add_resource($group_name, $this->lang->line('label_loan_disbursement_register'),'register_reports','loan_disbursement_master_report_index');
							$report->add_resource($group_name, $this->lang->line('label_fully_paid_loan_register'),'register_reports','fully_paid_loan_register_index');
							$report->add_resource($group_name, $this->lang->line('label_member_cancellation_register'),'register_reports','member_cancellation_register_index');
							$report->add_resource($group_name, $this->lang->line('label_member_wise_subsidy_loan_saving_ledger'),'register_reports','member_wise_subsidy_loan_saving_ledger_index');

							$group_name=$this->lang->line('label_regular_and_general_report');
							$report->add_group($group_name, '');
							$report->add_resource($group_name, $this->lang->line('label_component_wise_daily_collection_report'),'regular_and_general_reports','component_wise_daily_collection_report_index');
							$report->add_resource($group_name, $this->lang->line('label_branch_manager_report'),'regular_and_general_reports','branch_manager_report_index');
							$report->add_resource($group_name, $this->lang->line('label_field_officers_report'),'regular_and_general_reports','field_worker_report_index');
							$report->add_resource($group_name, $this->lang->line('label_loan_report'),'regular_and_general_reports','loan_field_officer_wise_index');
							$report->add_resource($group_name, $this->lang->line('label_loan_classification_and_dmr'),'regular_and_general_reports','loan_classification_and_dmr_index');
							$report->add_resource($group_name, $this->lang->line('label_samity_wise_monthly_loan_and_savings_collection_sheet'),'regular_and_general_reports','samity_wise_monthly_loan_and_savings_collection_sheet_index');
							$report->add_resource($group_name, $this->lang->line('label_samity_wise_monthly_loan_and_savings_working_sheet'),'regular_and_general_reports','samity_wise_monthly_loan_and_savings_working_sheet_index');
							$report->add_resource($group_name, $this->lang->line('label_monthly_collection_sheet'),'collection_sheets','index');
							
							$group_name=$this->lang->line('label_others_report');
							$report->add_group($group_name, '');
							$report->add_resource($group_name, $this->lang->line('label_consolidated_balancing'),'consolidated_reports','consolidated_balancing_report_index');
							$report->add_resource($group_name, $this->lang->line('label_ratio_analysis_statement'),'additional_reports','ratio_analysis_statement_index');
							$report->add_resource($group_name, $this->lang->line('label_consolidated_ratio_analysis'),'consolidated_ratio_analysis_statement_index','ratio_analysis_statement_index');
							$report->add_resource($group_name, $this->lang->line('label_pass_book_report'),'pass_book_reports');
							$report->add_resource($group_name, $this->lang->line('label_branchwise_samity_list'),'branchwise_samity_reports');
							$report->add_resource($group_name, $this->lang->line('label_samitywise_member_list'),'samity_wise_member_reports');
							//$report->add_resource($group_name, 'Field Officer wise Loan Collection','regular_and_general_reports','field_officer_wise_loan_index');
						
						$report->generate_menu_list();
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
     $("#report_container").show();     
    });

</script>
<div id="fade" onclick="closeLightbox('light');"></div>
	<div id="light" style="left:0px;top:30px; width:auto;">
		<div class="white_innerContent1">
			 <div class="divTextHolder1" id ="tableHolder1" >
				<div id ="ContentRelDiv"></div>
			</div>
		</div>		
	</div>
</div>
