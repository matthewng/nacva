<html>
<head>
<title>Registration | NACVA</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
<style>
body {
padding-top: 40px;
padding-bottom: 40px;
background-color: #eee;
}
.signup {
max-width: 380px;
padding: 15px;
margin: 0 auto;	
}
.form-signin {
max-width: 380px;
padding: 15px;
margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
margin-bottom: 10px;
}
.form-signin .checkbox {
font-weight: normal;
}
.form-signin .form-control {
position: relative;
height: auto;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding: 10px;
font-size: 16px;
}
.form-signin .form-control:focus {
z-index: 2;
}
.form-signin input[type="email"] {
margin-bottom: -1px;
border-bottom-right-radius: 0;
border-bottom-left-radius: 0;
}
.form-signin #inputPassword {
margin-bottom: -1px;
border-top-left-radius: 0;
border-top-right-radius: 0;
border-bottom-right-radius: 0;
border-bottom-left-radius: 0;
}
.form-signin #inputConfirmPassword {
margin-bottom: 10px;
border-top-left-radius: 0;
border-top-right-radius: 0;
}
</style>
</head>

<body>
<div class="container">
<form class="form-signin" action="" method="POST">
<h2 class="form-signin-heading">Register For An Account</h2>
<label for="inputEmail" class="sr-only">Email address</label>
<input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus name="email" />
<label for="inputPassword" class="sr-only">Password</label>
<input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
<label for="inputPassword" class="sr-only">Confirm Password</label>
<input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm Password" required>
<button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
</form>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>
