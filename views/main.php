<html>
<head>
<title>Welcome | NACVA</title>
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
  <h1>NACVA</h1>
</div>

<br/>

<div class="media">
<span class="glyphicon glyphicon-flash media-left media-top" aria-hidden="true" style="color:navy;font-size:100px;padding:0px 60px 0px 20px;"></span>
<div class="media-body media-middle">
<h3>Upcoming Tournaments:</h3>
<repeat group="{{ @tournaments }}" value="{{ @tournament }}">
<div><a href="/tournament/{{ @tournament.tournament_id }}">{{ @tournament.name }}</a> ({{ date_format(date_create(@tournament.date_start), 'F d, Y') }})</div>
</repeat>
</div>
</div>

<br/><br/><hr><br/><br/>

<div class="media">
<span class="glyphicon glyphicon-user media-left media-middle" aria-hidden="true" style="color:navy;font-size:100px;padding:0px 60px 0px 20px;"></span>
<div class="media-body">
<h3 class="media-heading">Your Profile:</h3>
<div><a href="/profile">Update your basic informtion</a></div>
</div>
</div>

<br/><br/><hr><br/><br/>

<check if="{{ @club }}">
<true>
<div class="media">
<span class="glyphicon glyphicon-tower media-left media-middle" aria-hidden="true" style="color:navy;font-size:100px;padding:0px 60px 0px 20px;"></span>
<div class="media-body">
<h3>Your Club and Teams:</h3>
<div>
<check if="{{ @isActiveMember }}">
<true>
<div>You {{ @isClubManager?'manage':'are a member of' }} the <a href="/club/{{ @club.club_id }}">{{ @club.name }}</a> club.
<check if="{{ @numPendingMembers > 0 }}">
<strong>({{ @numPendingMembers }} members pending)</strong>
</check>
</div>
<br/>
<repeat group="{{ @teams }}" value="{{ @team }}">
<div>You are on <a href="/team/{{ @team.team_id }}">{{ @team.name }}</a> for the <a href="/tournament/{{ @team.tournament_id }}">{{ @team.tournament_name }}</a>.</div>
</repeat>
</div>
</true>
<false>
<div>Your request to <a href="/club/{{ @club.club_id }}">{{ @club.name }}</a> club is still pending.</div>
</false>
</check>
</div>
</div>
</div>
</true>

<false>
<div class="media">
<span class="glyphicon glyphicon-tower media-left media-middle" aria-hidden="true" style="color:navy;font-size:100px;padding:0px 60px 0px 20px;"></span>
<div class="media-body">
<h3>Your Club and Teams:</h3>
<div><em>You are not part of any club.</em></div>
</div>
</div>
<br/><br/>
<div class="row">
<div class="col-sm-6">
<h2><small>Either create a club</small></h2>
<div>
<form action="/club" method="POST" class="form-horizontal">
<div class="form-group"><label for="name" class="col-sm-4 control-label">Club name:</label><div class="col-sm-8"><input type="text" name="name" class="form-control" /></div></div>
<div class="form-group"><label for="name" class="col-sm-4 control-label">City:</label><div class="col-sm-8"><input type="text" name="city" class="form-control" /></div></div>
<div class="form-group"><div class="col-sm-offset-4 col-sm-8"><button type="submit" class="form-control btn-success">Create New Club</button></div></div>
</form>
</div>
</div>
<div class="col-sm-6">
<h2><small>Or request to join a club</small></h2>
<div>
<form method="POST" action="/club/join" class="form-horizontal">
<div class="form-group"><label for="name" class="col-sm-4 control-label">Club name:</label><div class="col-sm-8">
<select name="club_id" class="form-control">
<repeat group="{{ @clubs }}" value="{{ @club }}">
<option value="{{ @club.club_id }}">{{ @club.name }}</option>
</repeat>
</select>
</div>
</div>
<div class="form-group"><div class="col-sm-offset-4 col-sm-8"><button type="submit" class="form-control btn-success">Request to join club</button></div></div>
</form>
</div>
</div>
</div>
</false>
</check>
</div>

<nav class="navbar navbar-default navbar-fixed-bottom">
<div class="container">
<ul class="nav nav-pills nav-justified">
<li class="nav nav-pills nav-justified active"><a href="/">Main</a></li>
<li class="nav nav-pills nav-justified"><a href="/profile">Profile</a></li>
<li class="nav nav-pills nav-justified"><a href="http://www.nacivt.com" target="_blank">Blog</a></li>
<li class="nav nav-pills nav-justified"><a href="/logout">Logout</a></li>
</ul>
</div>
</nav>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</body>
</html>
