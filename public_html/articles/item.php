<?php
if(!defined('ARTICLES')) die('ERROR');

if(isset($_GET['id']) && (int)$_GET['id']){
	$status = 1;
	$id = trim($_GET['id']);
	if(empty($id)) $status = 0;
	if($id < 1) $status = 0;

	if(!$status) die("<p class='error-message cfe-text-center'>Wrong request!</p>");
	if($item = $articles->getItem('articles',$id)){
	  ?>
    <ul class="article-item">
			<?php
			echo "<li>";
			echo "<img src='".$item['image']."'>";
			echo "<p>" . $item['name'] . "</p>";
			echo "<p>" . $item['price'] . " " . $item['val'] . "</p>";
			echo "</li>";
			?>
    </ul>
    <div class="cfe-extra-class-2 cfe-text-center cfe-m-b-40 cfe-m-t-40">
      <a class="cfe-list-btn-standard" href="<?php echo $_SERVER['HTTP_REFERER'] ?>">GO BACK</a>
			<?php
			if($users->is_moderator() || $articles->isPublisher($item['article_id'])) {
				?>
        <a class="cfe-list-btn-standard" href="index.php?sel=articles&c=myarticles&edit=<?php echo $item['article_id'] ?>">EDIT</a>
				<?php
			}
			?>
    </div>
    <?php
  }else{
	  echo "<p class='cfe-text-center error-message'>Wrong request!</p>";
	  echo '<a class="cfe-list-btn-standard" href="'.$_SERVER['HTTP_REFERER'].'">GO BACK</a>';
  }
}else{
	echo "<p class='cfe-text-center error-message'>Wrong request!</p>";
	echo '<a class="cfe-list-btn-standard" href="'.$_SERVER['HTTP_REFERER'].'">GO BACK</a>';
}
?>