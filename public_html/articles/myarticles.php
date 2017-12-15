<?php if(!defined('ARTICLES')) die('ERROR'); ?>
<div class="col col-12">
<?php
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    if($users->is_moderator() || $articles->isPublisher($_GET['edit'])){
      define('ARTICLES_EDIT', true);
      include_once VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '_edit.php';
    }else{
	    echo "<p class='error-message cfe-text-center'>You don't have privileges!</p>";
    }
  }else if(isset($_GET['delete'])){
    if($users->is_moderator() || $articles->isPublisher($_GET['delete'])){
      define('ARTICLES_DELETE', true);
      include_once VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '_delete.php';
    }else{
	    echo "<p class='error-message cfe-text-center'>You don't have privileges!</p>";
    }
  }else{
    if($data = $articles->getArticlesByUser()) {
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
          <th>View</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        </thead>
        <tbody>
		    <?php
		    foreach ( $data as $article ) {
			    echo "<tr>";
			    echo "<td>" . $article['name'] . "</td>";
			    echo "<td>" . $article['price'] . "</td>";
			    echo "<td>" . $article['val'] . "</td>";
			    echo "<td><img style='object-fit: cover;width: 80px;height: 80px;' src='" . $article['image'] . "'</td>";
			    echo "<td>" . $article['category_name'] . "</td>";
			    echo "<td>" . date( 'd.m.Y \i\\n H:i', strtotime( $article['date'] ) ) . "</td>";
			    if ( $articles->isPublisher( $article['article_id'] ) ) {
				    echo '<td><a href="index.php?sel=articles&c=item&id=' . $article['article_id'] . '"><i class="fa fa-eye" aria-hidden="true"></i></a></td>';
				    echo '<td><a href="index.php?sel=articles&c=myarticles&edit=' . $article['article_id'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
				    echo '<td><a href="index.php?sel=articles&c=myarticles&delete=' . $article['article_id'] . '"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';
			    }
			    echo "</tr>";
		    }
		    ?>
        </tbody>
      </table>
      <div class="cfe-m-t-40"></div>
	    <?php
    }else{
	    echo "<p class='info-message cfe-text-center'>You don't have published articles.</p>";
    }
    if(!$users->is_premium()){
      echo "<p class='info-message cfe-text-center'>If you want to add article, upgrade your profil.</p>";
      echo "<p class='cfe-text-center'><a class='cfe-list-btn-standard' href='index.php?sel=profile'>UPGRADE</a></p>";
    }
  }
?>
</div>
