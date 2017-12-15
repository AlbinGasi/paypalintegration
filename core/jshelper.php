<?php if(!defined('LOADED')) die('ERROR'); ?>
<input type="hidden" id="abspath" value="<?php echo ABSPATH ?>">
<input type="hidden" id="ajax" value="<?php echo AJAX ?>">
<script>
	var abspath = document.getElementById('abspath').value;
	var ajax = document.getElementById('ajax').value;
</script>