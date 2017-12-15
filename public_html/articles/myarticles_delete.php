<?php if(!defined('ARTICLES_DELETE')) die('ERROR');
if(isset($_GET['delete'], $_GET['deleteArticle']) && !empty($_GET['delete'])){
	if($article = $articles->getItem('articles',$_GET['delete'])){
		if(file_exists($article['image'])) unlink($article['image']);
		if($articles->deleteArticle($_GET['delete'])){
			echo "<p class='cfe-text-center success-message'>Article was deleted</p>";
		}
	}else{
		echo "<p class='cfe-text-center error-message'>Article doesn't exist</p>";
	}
}else if(isset($_GET['delete']) && !empty($_GET['delete'])) {
	if ( $article = $articles->getItem( 'articles', $_GET['delete'] ) ) {
		?>
		<table>
			<thead>
			<tr>
				<th>Name</th>
				<th>Price</th>
				<th>Currency</th>
				<th>Image</th>
				<th>Category</th>
				<th>Published</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			</thead>
			<tbody>
			<?php
			echo "<tr>";
			echo "<td>" . $article['name'] . "</td>";
			echo "<td>" . $article['price'] . "</td>";
			echo "<td>" . $article['val'] . "</td>";
			echo "<td><img style='object-fit: cover;width: 80px;height: 80px;' src='" . $article['image'] . "'</td>";
			echo "<td>" . $articles->getCategoryById( $article['category'] ) . "</td>";
			echo "<td>" . date( 'd.m.Y \i\\n H:i', strtotime( $article['date'] ) ) . "</td>";
			echo '<td><a href="index.php?sel=articles&c=myarticles&edit=' . $article['article_id'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
			echo '<td><a href="index.php?sel=articles&c=myarticles&delete=' . $article['article_id'] . '"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';
			echo "</tr>";
			?>
			</tbody>
		</table>
		<div class="cfe-m-b-20"></div>
		<p class="cfe-text-center">Are you sure you want to delete this article?</p>
		<div class="cfe-extra-class-2 cfe-text-center">
			<a class="cfe-list-btn-standard" href="index.php?sel=articles&c=myarticles&delete=<?php echo $article['article_id'] ?>&deleteArticle">Yes</a>
			<a class="cfe-list-btn-standard" href="index.php?sel=articles&c=myarticles">No</a>
		</div>
		<?php
	}else{
		echo "<p class='cfe-text-center error-message'>Article doesn't exist</p>";
	}
}
?>