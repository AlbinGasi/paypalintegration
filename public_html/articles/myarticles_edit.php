<?php if(!defined('ARTICLES_EDIT')) die('ERROR');

$article = $articles->getItem('articles',$_GET['edit']);
	if(isset($_POST['article_submit'])){
		$article_name = trim($_POST['article_name']);
		$article_price = trim($_POST['article_price']);
		$val = trim($_POST['val']);
		$article_category = trim($_POST['article_category']);
		if($article_category == "uncategorized") $article_category = "";

		if($_FILES['article_image']['size'] > 0){
			$image_file = uploadImage($article['image']);
			if($image_file['status'] == "success"){
			}else{
				foreach ($image_file['message'] as $msg){
					echo "<p class='error-message'>" .$msg . "</p>";
				}
			}
		}else{
			$image_file['message'] = $article['image'];
		}

		if(!empty($article_name) && !empty($article_price && !empty($article_category) && !empty($val))){
			if($articles->updateArticles($article_name,$article_price,$val,$image_file['message'],$article_category,$article['article_id'])){
				echo "<p class='success-message cfe-text-center cfe-m-b-20'>Success</p>";
			}
		}else{
			echo "<p class='error-message'>All fields are required</p>";
		}
	}
	$article = $articles->getItem('articles',$_GET['edit']);
?>
<div id="add-new-article">
	<form action="" method="post" enctype="multipart/form-data">
		<div>
			<input type="text" name="article_name" value="<?php echo $article['name'] ?>">
		</div>
		<div>
			<input type="text" name="article_price" class="article-price" value="<?php echo $article['price'] ?>">
			<?php echo currency($article['val']); ?>
		</div>
		<div>
			<select name="article_category">
				<option value="uncategorized">select category</option>
				<?php
				foreach ($articles->get('category') as $category){
					if($article['category'] == $category['category_id']){
						echo '<option value="'.$category['category_id'].'" selected>'.$category['category_name'].'</option>';
					}else{
						echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
					}
				}
				?>
			</select>
		</div>
		<div>
			<img src="<?php echo $article['image'] ?>">
		</div>
		<div>
			<input type="file" id="article_image" class="custom-file-input" name="article_image" data-cont="Edit article image">
		</div>
		<div>
			<input type="submit" class="cfe-list-btn-standard" name="article_submit" value="EDIT">
		</div>
	</form>
   <p class='cfe-text-center cfe-m-t-40'>
     <a class='cfe-list-btn-standard' href='index.php?sel=articles&c=myarticles'>Go back</a>
   </p>
	<script>
    function getFile(filePath) {
      return filePath.substr(filePath.lastIndexOf('\\') + 1);
    }
    function updated(event) {
      var count = 0;
      for ( i = 0; i < event.path.length; i++ ) {
        var tmpObj = event.path[i];
        if ( tmpObj.value !== undefined ) {
          count++;
        }
      }
      elementID = event.target.id;
      if(event.target.value != ''){
        document.getElementById('article_image').setAttribute('data-cont',getFile(event.target.value));
      }
    }
    document.getElementById('article_image').onchange = updated;
	</script>
</div>
