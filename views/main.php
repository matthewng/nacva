<html>
<head>
<title>Welcome | NACVA</title>
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

<h1>NACVA</h1>

<div>
<h3>Upcoming Tournaments:</h3>
<repeat group="{{ @tournaments }}" value="{{ @tournament }}">
<div><a href="/tournament/{{ @tournament.tournament_id }}">{{ @tournament.name }}</a> ({{ date_format(date_create(@tournament.date_start), 'F d, Y') }})</div>
</repeat>
</div>

<br/><hr>

<h3>Your Profile:</h3>

<div><a href="/profile">Update your basic informtion</a></div>

<br/><hr>

<h3>Your Club and Teams:</h3>

<check if="{{ @club }}">
<!-- if member of a club -->
<true>
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
</true>

<false>
<div><em>You are not part of any club. Either:</em></div>

<br/>

<!-- if not a member of any club -->
<h2><small>Create a club</small></h2>
<div>
<form action="/club" method="POST" class="form-horizontal">
<div class="form-group"><label for="name" class="col-sm-2 control-label">Club name:</label><div class="col-sm-10"><input type="text" name="name" class="form-control" /></div></div>
<div class="form-group"><label for="name" class="col-sm-2 control-label">City:</label><div class="col-sm-10"><input type="text" name="city" class="form-control" /></div></div>
<div class="form-group"><div class="col-sm-offset-2 col-sm-10"><button type="submit" class="form-control btn-success">Create New Club</button></div></div>
</form>
</div>

<br/><br/>

<h2><small>Request to join a club</small></h2>
<div>
<form method="POST" action="/club/join" class="form-horizontal">
<div class="form-group"><label for="name" class="col-sm-2 control-label">Club name:</label><div class="col-sm-10">
<select name="club_id" class="form-control">
<repeat group="{{ @clubs }}" value="{{ @club }}">
<option value="{{ @club.club_id }}">{{ @club.name }}</option>
</repeat>
</select>
</div>
</div>
<div class="form-group"><div class="col-sm-offset-2 col-sm-10"><button type="submit" class="form-control btn-success">Request to join club</button></div></div>
</form>
</div>
</false>
</check>
</div>

<footer class="footer">
<div class="container menu">
<span class="menu-item"><a href="#">Main</a></span> |
<span class="menu-item"><a href="/profile">Profile</a></span> |
<span class="menu-item"><a href="/logout">Logout</a></span>
</div>
</footer>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</body>
</html>
