<style>
#access_denied{
    border: 1px solid #ccc;
    height: auto;
    margin: 0 auto;
    padding: 10px 0;
    width: 50%;	
}
#access_denied h1 {    border-bottom: 1px solid #CCCCCC;
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-size: 24px;
    font-weight: normal;
    margin: 0 auto;
    padding: 2px 0 10px 0;
    text-align: center;
    width: 100%;color:red;}
#access_denied_ul{}
#access_denied_ul a{color: #3B5998;font-weight:normal;}
#access_denied_ul a:hover{color: #0F3688;text-decoration:underline;}
</style>
<div id="access_denied">
	<h1>
		<!--<img src="<?php echo base_url(); ?>media/images/dashboard_images/access_denied_32.jpg" border="0" />-->
		&nbsp;&nbsp;
		Error: Access Denied</h1>
	<div style="width:100%;margin:0 auto;text-align:center;padding-top: 20px;font-family: Verdana,Arial,Helvetica,sans-serif;">
		<img src="<?php echo base_url(); ?>media/images/dashboard_images/access_denied_64" border="0" /><br/><br/>
		<p style="font-family: Verdana,Arial,Helvetica,sans-serif;font-size:13px;text-align:center;margin:0px;">You are not authorized to view this requested page. </p>
		<ul id="access_denied_ul">
			<li style="display:inline;font-size:12px;text-align:center;list-style-type: square;text-decoration: none;">Go back to <a href="<?php echo base_url(); ?>">Homepage</a>.</li>
			<li style="display:inline;font-size:12px;text-align:center;list-style-type: square;text-decoration: none;">  |  </li>
			<li style="display:inline;font-size:12px;text-align:center;list-style-type: square;text-decoration: none;">Return back to <a href="javascript:history.go(-1);">Previous page</a>.</li>
		</ul>
	</div>
</div>
