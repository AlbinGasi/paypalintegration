<?php
if(!defined('ARTICLES')) die('ERROR');
?>

<ul class="article-list">
<?php
foreach ($articles->get('articles') as $article){
	echo "<li>";
	echo "<img src='".$article['image']."'>";
	echo "<p>" . $article['name'] . "</p>";
	echo "<p>" . $article['price'] . " " . $article['val'] . "</p>";
	echo "<p><a href='index.php?sel=articles&c=item&id=".$article['article_id']."'>Details</a></p>";
	echo "</li>";
}
?>
</ul>