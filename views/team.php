<html>
<head>
<title>Team | NACVA</title>
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
table {
font-size:inherit;
}
</style>
</head>
<body>
<div class="container">
<h2>{{ @team.name }}</h2>

<dl class="dl-horizontal">
<dt>Tournament:</dt><dd><a href="/tournament/{{ @tournament.tournament_id }}">{{ @tournament.name }}</a></dd>
<dt>Club:</dt><dd><a href="/club/{{ @club.club_id}}">{{ @club.name }}</a></dd>
</dl>

<br/><br/>

<div>
<h3>Team Roster</h3>
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

<footer class="footer">
<div class="container menu">
<span class="menu-item"><a href="/">Main</a></span> |
<span class="menu-item"><a href="/profile">Profile</a></span> |
<span class="menu-item"><a href="/logout">Logout</a></span>
</div>
</footer>

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
