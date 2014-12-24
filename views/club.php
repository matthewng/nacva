<html>
<head>
<title>Club | NACVA</title>
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
<h2>{{ @club.name }}</h2>
<div>Managed by <a href="/profile/{{ @clubManager.profile_id }}">{{ @clubManager.first_name . ' ' . @clubManager.last_name }}</a></div>

<br/><hr>

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
<check if="{{ @canRegisterTeam }}">
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
<table class="table">
<check if="{{ @isClubManager }}">
<repeat group="{{ @pendings }}" value="{{ @pending }}">
<tr>
<td><a href="/profile/{{ @pending.profile_id }}">{{ @pending.first_name . ' ' . @pending.last_name }}</a>
	<span class="glyphicon glyphicon-question-sign" aria-hidden="true" style="margin-left:10px;"></span></td> 
<check if="{{ @isClubManager }}">
<td><form action="/club/accept" method="POST">
<input type="hidden" name="account_id" value="{{ @pending.account_id }}" />
<input type="submit" value="Accept request" class="form-control" />
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

<!-- if member -->
<check if="{{ @isClubMember && !@isClubManager }}">
<br/><br/>
<div>
<form action="/club/leave" method="POST">
<input type="submit" value="Leave club" class="form-control btn-danger" />
</form>
</div>
</check>

<br/><br/>

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
