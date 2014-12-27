<html>
<head>
<title>Club | NACVA</title>
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
  <h1>{{ @club.name }}</h1>
  <small>Managed by <a href="/profile/{{ @clubManager.profile_id }}">{{ @clubManager.first_name . ' ' . @clubManager.last_name }}</a></small>
</div>

<br/>

<div>
<h3>Tournaments</h3>
<div>Registered in {{ count(@teamsPerTournament) }} tournament(s):</div>
<br/>
<repeat group="{{ @teamsPerTournament }}" key="{{ @tournament_name }}" value="{{ @teams }}">
<div><a href="/tournament/{{ @teams[0].tournament_id }}">{{ @tournament_name }}</div>
<ul>
<repeat group="{{ @teams }}" value="{{ @team }}">
<li><a href="/team/{{ @team.team_id }}">{{ @team.name }}</a></li>
</repeat>
</ul>
<br/>
</repeat>
</div>

<!-- if club manager -->
<check if="{{ @isClubManager }}">
<br />

<div class="row">
<div class="col-sm-8">
<form action="/team" method="POST">
<select name="tournament_id" class="form-control">
	<repeat group="{{ @tournaments }}" value="{{ @tournament }}">
	<option value="{{ @tournament['tournament_id'] }}">{{ @tournament.name }}</option>
	</repeat>
</select>
<input type="text" name="name" class="form-control" placeholder="Team Name" />
<button type="submit" class="form-control btn-primary">Register New Team</button>
</form>
</div>
<div class="col-sm-4"></div>
</div>
<br /><br />
</check>

<br/><hr>

<div>
<h3>Members <small>There are {{ count(@members) }} members<check if="{{ @isClubManager }}"> ({{ count(@pendings) }} pending)</check>:</small></h3>
<br/>
<div class="row">

<div class="col-sm-offset-1 col-sm-10">
<table class="table">
<check if="{{ @isClubManager }}">
<repeat group="{{ @pendings }}" value="{{ @pending }}">
<tr>
<td><a href="/profile/{{ @pending.profile_id }}">{{ @pending.first_name . ' ' . @pending.last_name }}</a></td> 
<check if="{{ @isClubManager }}">
<td><form action="/club/accept" method="POST">
<input type="hidden" name="account_id" value="{{ @pending.account_id }}" />
<input type="submit" value="Accept request" class="form-control btn-success" />
</form></td>
<td><form action="/club/reject" method="POST">
<input type="hidden" name="account_id" value="{{ @pending.account_id }}" />
<input type="submit" value="Reject request" class="form-control btn-danger" />
</form></td>
</check>
</tr>
</repeat>
</check>
<repeat group="{{ @members }}" value="{{ @member }}">
<tr>
<td><a href="/profile/{{ @member.profile_id }}">{{ @member.first_name . ' ' . @member.last_name }}</a></td>
<check if="{{ @isClubManager }}">
<td><form action="/club/reject" method="POST">
<input type="hidden" name="account_id" value="{{ @member.account_id }}" />
<input type="submit" value="Remove member" class="form-control btn-danger" />
</form></td>
<td></td>
</check>
</tr>
</repeat>
</table>
</div>
<div class="col-sm-1"></div>
</div>

<br/><br/>

<check if="{{ @isClubMember && !@isClubManager }}">
<!-- if member -->
<div class="row">
<div class="col-sm-offset-1 col-sm-6">
<form action="/club/leave" method="POST">
<input type="submit" value="Leave club" class="form-control btn-danger" />
</form>
</div>
<div class="col-sm-5"></div>
</div>
</check>

<check if="{{ @isPendingMember }}">
<!-- pending member -->
<div class="row">
<div class="col-sm-offset-1 col-sm-6"><em>Your membership request is still pending.</em></div>
<div class="col-sm-5"></div>
</div>
<div class="row">
<div class="col-sm-offset-1 col-sm-6">
<form action="/club/leave" method="POST">
<input type="submit" value="Cancel Request" class="form-control btn-danger" />
</form>
</div>
<div class="col-sm-5"></div>
</div>
</check>
</div>

<br/><hr>

<h3>Administration</h3>
<div class="row">
<div class="col-sm-offset-8 col-sm-1">
<button class="btn btn-danger" id="delete_club" type="button">DELETE CLUB</button></div>
</div>
<div class="col-sm-3"></div>
</check>
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
<script>
$('#delete_club').on('click', function() {
	$.ajax({
		url:'/club',
		method:'DELETE',
		success: function() {
			window.location = '/';
		}
	});
});
</script>
</body>
</html>
