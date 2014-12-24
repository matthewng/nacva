<html>
<head>
<title>Tournament | NACVA</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
<style>
html {
position: relative;
min-height: 100%;
}
body {
margin-bottom: 60px;
}
.footer {
position: fixed;
bottom: 0;
width: 100%;
height: 60px;
background-color: #f5f5f5;
}
.container {
width: auto;
max-width: 680px;
padding: 20px 15px;
}
</style>
</head>
<body>
<div class="container">
<h2>{{ @tournament.name }}</h2>

<dl class="dl-horizontal">
<dt>Location:</dt><dd>{{ @tournament.host_city }}</dd>
<dt>Date of tournament:</dt><dd>{{ date_create(@tournament.date_start)->format("F d") }} - {{ date_create(@tournament.date_end)->format("F d, Y") }}</dd>
<dt>Registration deadline:</dt><dd>{{ date_create(@tournament.deadline)->format("F d, Y") }}</dd>
</dl>

<br/><hr>

<div>
<h3>Registered Teams</h3>
<ul>
<repeat group="{{ @teams }}" value="{{ @team }}">
<li><a href="/team/{{ @team.team_id }}">{{ @team.name }}</li>
</repeat>
</ul>
</div>
</div>

<footer class="footer">
<div class="container menu">
<span class="menu-item"><a href="/">Main</a></span> |
<span class="menu-item"><a href="/profile">Profile</a></span> |
<span class="menu-item"><a href="/logout">Logout</a></span>
</div>
</footer>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>
