<?php if(!defined('LOADED')) die('ERROR'); ?>
<style>
  body{
    background:#3498db;
    margin: 0 auto 0 auto;
    width: 100%;
    text-align:center;
    margin: 20px 0px 20px 0px;
  }
</style>
<div id="login-page">
  <form method="post" action="">
    <div class="box">
      <h1>LOGIN</h1>

      <input type="text" name="username" class="login-input-custom" placeholder="Username..." required>
      <input type="password" name="password" class="login-input-custom" placeholder="Password..." required>
      <input type="submit" name="btn_submit" value="Sign in" class="btn">
    </div>
  </form>

<?php
if(isset($_POST['btn_submit'])){
  echo '<div class="box-mes">';
  $username = strip_tags(trim($_POST['username']));
  $password = strip_tags(trim($_POST['password']));

  if($users->userLogin($username,$password)){
    echo '<h3>Success</h3>';
    echo "<noscript><a href='".ABSPATH."/index.php'>Go to home page</a> </noscript>";
	  echo "<script>setTimeout(function(){window.location.href=abspath+'/index.php' },1500);</script>";
  }else{
    echo '<h3>Data error!</h3>';
  }
  echo "</div>";
}
?>
</div>