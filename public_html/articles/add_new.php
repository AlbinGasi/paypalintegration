<?php
if(!defined('ARTICLES')) die('ERROR');

if($users->is_premium()){
?>
<div class="col col-12">
  <div id="add-new-article">
    <form action="" method="post" enctype="multipart/form-data">
      <div>
        <input type="text" name="article_name" placeholder="Article name">
      </div>
      <div>
        <input type="text" name="article_price" class="article-price" placeholder="Article price">
        <?php echo currency(); ?>
      </div>
      <div>
        <select name="article_category">
          <option value="uncategorized">select category</option>
          <?php
          foreach ($articles->get('category') as $category){
            echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
          }
          ?>
        </select>
      </div>
      <div>
        <input type="file" id="article_image" class="custom-file-input" name="article_image" data-cont="Select article image" required="required">
      </div>
      <div>
        <input type="submit" class="cfe-list-btn-standard" name="article_submit" value="Add">
      </div>
    </form>


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

<?php
	if(isset($_POST['article_submit'])){

	  if($_FILES['article_image']['size'] == 0){
		  echo "<p class='error-message'>Select article image</p>";
    }else{
	    $image_file = uploadImage();
	    if($image_file['status'] == "success"){
		    $article_name = trim($_POST['article_name']);
		    $article_price = trim($_POST['article_price']);
		    $val = trim($_POST['val']);
		    $article_category = trim($_POST['article_category']);
		    if($article_category == "uncategorized") $article_category = "";

		    if(!empty($article_name) && !empty($article_price && !empty($article_category) && !empty($val))){
			    if($articles->addArticles($article_name,$article_price,$val,$image_file['message'],$article_category)){
				    echo "<p class='success-message'>Success</p>";
			    }
		    }else{
			    echo "<p class='error-message'>All fields are required</p>";
		    }
      }else{
        foreach ($image_file['message'] as $msg){
          echo "<p class='error-message'>" .$msg . "</p>";
        }
      }
    }
	}

?>
  </div>
</div>
<?php
}else{
	echo "<p class='info-message cfe-text-center'>If you want to add article, upgrade your profil.</p>";
	echo "<p class='cfe-text-center'><a class='cfe-list-btn-standard' href='index.php?sel=profile'>UPGRADE</a></p>";
}
?>

