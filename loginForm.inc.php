<!--
This form allows user to input login and pass

Included by:
login.inc.php
signin.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:
index.php?p=checklogin

-->

<script>
$(document).ready(function(){
$(".loginMsg").hide();
  $("#login").click(function(){
    $(".loginMsg").show();
  });
});
</script>

<center>
<div align="center" class="loginForm">
	<!--<p class="text-danger loginMsg"><strong><br /><img src="img/ajax-loader.gif" /> Connecting... This process can take a while...</strong></p>-->
	
	<h2 align="center">PEOPLE PORTAL</h2>
	
        <h4 class="form-signin-heading"><img src="img/lockS.png" /> Please sign in</h4>

      <form class="form-signin" role="form" action="index.php?p=checklogin" method="POST">
        <input type="login" class="form-control" placeholder="Username" name="username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <button class="btn btn-lg btn-primary btn-block usf" type="submit" id="login">Sign in</button>
      </form>
	  <p class="text-info">Your username is "firstname.lastname", without the @xxxx.xxx</p>
</div>
</center>
