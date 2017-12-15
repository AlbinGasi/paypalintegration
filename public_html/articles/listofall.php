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
				<?php
				if($articles->is_moderator()){
					?>
					<th>Edit</th>
					<th>Delete</th>
				<?php
				}
				?>
			</tr>
			</thead>
			<tbody>
			<?php
			$txt = "";
			foreach ($articles->get('articles ORDER BY date DESC') as $article){
				$txt .= "<tr>";
				$txt .= "<td>" . $article['name'] . "</td>";
				$txt .= "<td>" . $article['price'] . "</td>";
				$txt .= "<td>" . $article['val'] . "</td>";
				$txt .= "<td><img style='object-fit: cover;width: 80px;height: 80px;' src='".$article['image']."'</td>";
				$txt .= "<td>" . $articles->getCategoryById($article['category']) . "</td>";
				$txt .= "<td>" . date('d.m.Y \i\\n H:i',strtotime($article['date'])) . "</td>";
				$txt .= '<td><a href="index.php?sel=articles&c=item&id='.$article['article_id'].'"><i class="fa fa-eye" aria-hidden="true"></i></a></td>';
				if($users->is_moderator()){
					$txt .= "<td>";
					$txt .= '<a href="index.php?sel=articles&c=myarticles&edit='.$article['article_id'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>';
					$txt .= "</td>";

					$txt .= "<td>";
					$txt .= '<a href="index.php?sel=articles&c=myarticles&delete='.$article['article_id'].'"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';
					$txt .= "</td>";
				}
				$txt .= "</tr>";
			}
			echo $txt;
			?>
			</tbody>
		</table>
	<?php } ?>
</div>
