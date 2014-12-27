<html>
<head>
<title>Tournament | NACVA</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
<style>
body {
margin-bottom: 250px;
}
.container {
width: auto;
max-width: 680px;
padding: 10px;
}
table {
font-size:inherit;
}
</style>
</head>
<body>
<div class="container">
<div class="page-header">
  <h1>{{ @tournament.name }}</h1>
</div>

<div>
<dl class="dl-horizontal">
<dt>Location:</dt><dd>{{ @tournament.host_city }}</dd>
<dt>Date of tournament:</dt><dd>{{ date_create(@tournament.date_start)->format("F d") }} - {{ date_create(@tournament.date_end)->format("F d, Y") }}</dd>
<dt>Registration deadline:</dt><dd>{{ date_create(@tournament.deadline)->format("F d, Y") }}</dd>
</dl>
</div>

<br/><hr>

<div>
<h3>Registered teams ({{ count(@teams) }})</h3>
<ul>
<repeat group="{{ @teams }}" value="{{ @team }}">
<li><a href="/team/{{ @team.team_id }}">{{ @team.name }}</a></li>
</repeat>
</ul>
</div>

</div>

<nav class="navbar navbar-default navbar-fixed-bottom">
<div class="container">
<ul class="nav nav-pills nav-justified">
<li class="nav nav-pills nav-justified"><a href="/">Main</a></li>
<li class="nav nav-pills nav-justified"><a href="/profile">Profile</a></li>
<li class="nav nav-pills nav-justified"><a href="http://www.nacivt.com" target="_blank">Blog</a></li>
<li class="nav nav-pills nav-justified"><a href="/logout">Logout</a></li>
</ul>
</div>
</nav>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>
