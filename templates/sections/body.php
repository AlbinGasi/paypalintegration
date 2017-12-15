<?php if(!defined('LOADED')) die('ERROR'); ?>
<div id="admin-panel">
	<div class="row">
		<div class="col-12 menu">
			<ul>
      <?php
      $_GET['sel'] = (isset($_GET['sel']))? $_GET['sel'] : "";
        foreach ($MENU as $item => $v) {
          $class = "";
          if($_GET['sel'] == substr($v['url'],14)){
            $class = " current-menu-item";
          }
          echo '<li'.$v['class'].'><a class="menu-item'.$class.'" href="'.$v['url'].'" title="'.$v['title'].'">'.$v['name'].'</a></li>';
        }
     ?>
      </ul>
		</div>
    <div class="cfe-clear-both"></div>
<?php if(count($SUBMENU) > 0) subMenu(); ?>