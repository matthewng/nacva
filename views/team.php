<html>
<head>
<title>Team | NACVA</title>
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
  <h1>{{ @team.name }}</h1>
</div>

<dl class="dl-horizontal">
<dt>Tournament:</dt><dd><a href="/tournament/{{ @tournament.tournament_id }}">{{ @tournament.name }}</a></dd>
<dt>Club:</dt><dd><a href="/club/{{ @club.club_id}}">{{ @club.name }}</a></dd>
</dl>

<br/><br/>

<div>
<h3>Team roster</h3>
<check if="{{ empty(@roster) }}">
<true>
<div><em>(The roster is empty)</em></div>
</true>
<false>
<table class="table">
<repeat group="{{ @roster }}" value="{{ @profile }}">
<tr>
<td style="width:300px"><a href="/profile/{{ @profile.profile_id }}">{{ @profile.first_name . ' ' . @profile.last_name }}</a></td>
<check if="{{ @canEditRoster }}">
<td><button data-profile="{{ @profile.profile_id }}" class="remove_player btn btn-danger" type="button">Remove Player</button></td>
</check>
</tr>
</repeat>
</table>
</false>
</check>
</div>

<br/><br/><br/>

<check if="{{ @canEditRoster }}">
<div>
<h3>Add a club member to the roster</h3>
<check if="{{ empty(@teamless) }}">
<true>
<div><em>(No club members are teamless)</em></div>
</true>
<false>
<table class="table">
<repeat group="{{ @teamless }}" value="{{ @profile }}">
<tr>
<td style="width:300px"><a href="/profile/{{ @profile.profile_id }}">{{ @profile.first_name . ' ' . @profile.last_name }}</a></td>
<td><button data-profile="{{ @profile.profile_id }}" class="add_player btn btn-primary" type="button">Add Player</button></td>
</tr>
</repeat>
</table>
</false>
</check>
</div>

<br/><br/><br/>

<div>
<h3>Club members on another team</h3>
<check if="{{ empty(@teamfull) }}">
<true>
<div><em>(No club members on other teams)</em></div>
</true>
<false>
<table class="table">
<repeat group="{{ @teamfull }}" value="{{ @profile }}">
<tr>
<td><a href="/profile/{{ @profile.profile_id }}">{{ @profile.first_name . ' ' . @profile.last_name }}</a></td>
<td>(<a href="/team/{{ @profile.team_id }}">{{ @profile.team_name }}</a>)</td>
</tr>
</repeat>
</table>
</false>
</check>
</div>

<br/><hr>

<div><button class="btn btn-danger" id="delete_team" type="button">DELETE TEAM</button></div>
</check>
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
$('#delete_team').on('click', function() {
	$.ajax({
		url:'/team/{{ @PARAMS.id }}',
		method:'DELETE',
		success: function() {
			window.location = '/club';
		}
	});
});
$('.add_player').on('click', function() {
	var profile_id = $(this).data('profile');
	$.ajax({
		url:'/player/{{ @PARAMS.id }}/' + profile_id,
		method:'PUT',
		success: function() {
			window.location.reload();
		}
	});
});
$('.remove_player').on('click', function() {
	var profile_id = $(this).data('profile');
	$.ajax({
		url:'/player/{{ @PARAMS.id }}/' + profile_id,
		method:'DELETE',
		success: function() {
			window.location.reload();
		}
	});
});
</script>

</body>
</html>
