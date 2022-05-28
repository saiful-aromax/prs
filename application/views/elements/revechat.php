<?php 
if (SITE_NAME == "demo") { 
	$user = $this->session->userdata('system.user');
?>
<!-- Start of REVE Chat Script-->
<script type='text/javascript'>
 var _revechat_group_id="1";
 window.__revechat_account = window.__revechat_account || {};
 window.__revechat_account = '18730';
 (function() { 
   var rc = document.createElement('script'); rc.type = 'text/javascript'; rc.async = true;
   rc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'static.revechat.com/widget/scripts/new-livechat.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(rc, s);
})();
</script>
<!-- End of REVE Chat Script -->
<?php } ?>