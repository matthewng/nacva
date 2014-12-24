<html>

<head>
<title>Profile | NACVA</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
<style>
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
.verified {
color:lightgreen;
padding:5px;
font-size:20px;
}
</style>
</head>

<body>
<div class="container">
<h2>Profile</h2>

<hr>

<check if="{{ @canEditProfile }}">
<true>
<form action="" method="POST" class="form-horizontal">
<div class="form-group"><label for="first_name" class="col-sm-3 control-label">First Name:</label><div class="col-sm-9"><input type="text" name="first_name" value="{{ @profile.first_name }}" class="form-control" /></div></div>
<div class="form-group"><label for="last_name" class="col-sm-3 control-label">Last Name:</label><div class="col-sm-9"><input type="text" name="last_name" value="{{ @profile.last_name }}" class="form-control" /></div></div>
<div class="form-group"><label for="email" class="col-sm-3 control-label">Email:</label><div class="col-sm-9"><input type="email" name="email" value="{{ @account.email }}" class="form-control" /></div></div>
<div class="form-group"><label for="gender" class="col-sm-3 control-label">Gender:</label><div class="col-sm-9">
	<label class="radio-inline"><input type="radio" name="gender" value="male" {{ (@profile.gender == 'MALE') ? 'checked' : '' }} />Male</label>
	<label class="radio-inline"><input type="radio" name="gender" value="female" {{ (@profile.gender == 'FEMALE') ? 'checked' : '' }} />Female</label>
</div></div>
<div class="form-group"><label for="address" class="col-sm-3 control-label">Address:</label><div class="col-sm-9"><input type="text" name="address" value="{{ @profile.address }}" class="form-control" /></div></div>
<div class="form-group"><label for="city" class="col-sm-3 control-label">City:</label><div class="col-sm-9"><input type="text" name="city" value="{{ @profile.city }}" class="form-control" /></div></div>
<div class="form-group"><label for="state" class="col-sm-3 control-label">State:</label><div class="col-sm-9">
	<select name="state" class="form-control">
		<repeat group="{{ @states }}" value="{{ @state }}">
		<option value="{{ @state }}" {{ (@profile.state == @state) ? 'selected' : '' }}>{{ @state }}</option>
		</repeat>
	</select>
</div></div>
<div class="form-group"><label for="zip" class="col-sm-3 control-label">Zip:</label><div class="col-sm-9"><input type="text" name="zip" value="{{ @profile.zip }}" class="form-control" /></div></div>
<div class="form-group"><label for="country" class="col-sm-3 control-label">Country:</label><div class="col-sm-9">
	<select name="country" class="form-control">
		<repeat group="{{ @countries }}" value="{{ @country }}">
		<option value="{{ @country }}" {{ (@profile.country == @country) ? 'selected' : '' }}>{{ @country }}</option>
		</repeat>
	</select>
</div></div>
<div class="form-group"><label for="phone_home" class="col-sm-3 control-label">Home phone:</label><div class="col-sm-9"><input type="tel" name="phone_home" value="{{ @profile.phone_home }}" class="form-control" /></div></div>
<div class="form-group"><label for="phone_mobile" class="col-sm-3 control-label">Mobile phone:</label><div class="col-sm-9"><input type="tel" name="phone_mobile" value="{{ @profile.phone_mobile }}" class="form-control" /></div></div>
<div class="form-group"><label for="dob" class="col-sm-3 control-label">Date of Birth:</label><div class="col-sm-9"><input type="date" name="dob" value="{{ @profile.dob }}" class="form-control" /></div></div>
<div class="form-group"><label for="school_name" class="col-sm-3 control-label">Current School:</label><div class="col-sm-9"><input type="text" name="school_name" value="{{ @profile.school_name }}" class="form-control" /></div></div>
<div class="form-group"><label for="education_level" class="col-sm-3 control-label">Level of Education:</label><div class="col-sm-9">
	<select name="education_level" class="form-control">
		<repeat group="{{ @education_levels }}" value="{{ @education_level }}">
		<option value="{{ @education_level }}" {{ (@profile.education_level == @education_level) ? 'selected' : '' }}>{{ @education_level }}</option>
		</repeat>
	</select>
</div></div>
<div class="form-group"><label for="occupation" class="col-sm-3 control-label">Occupation:</label><div class="col-sm-9"><input type="text" name="occupation" value="{{ @profile.occupation }}" class="form-control" /></div></div>
<div class="form-group"><label for="salary_range" class="col-sm-3 control-label">Salary Range:</label><div class="col-sm-9">
	<select name="salary_range" class="form-control" />
		<option value="50000" {{ (@profile.salary_range == '50000') ? 'selected' : '' }}>$0 - $50,000</option>
		<option value="100000" {{ (@profile.salary_range == '100000') ? 'selected' : '' }}>$50,000 - $100,000</option>
		<option value="150000" {{ (@profile.salary_range == '150000') ? 'selected' : '' }}>$100,000 - $150,000</option>
		<option value="150000_PLUS" {{ (@profile.salary_range == '150000_PLUS') ? 'selected' : '' }}>$150,000+</option>
		<option value="DECLINE" {{ (@profile.salary_range == 'DECLINE') ? 'selected' : '' }}>Prefer not to say</option>
	</select>
</div></div>
<div class="form-group">
<label for="ethnicity" class="col-sm-3 control-label">Ethnicity:</label>
<div class="col-sm-8"><input type="text" name="ethnicity" value="{{ @profile.ethnicity }}" class="form-control" /></div>
<div class="col-sm-1">
<check if="{{ @profile.ethnicity_verified }}">
<span class="glyphicon glyphicon-ok-sign verified" aria-hidden="true" title="The NACVA committee has verified this information"></span>
</check>
</div>
</div>
<div class="form-group"><label for="parent_name" class="col-sm-3 control-label">Parent/Guardian's Name:</label><div class="col-sm-9"><input type="text" name="parent_name" value="{{ @profile.parent_name }}" class="form-control" /></div></div>
<div class="form-group"><label for="parent_address" class="col-sm-3 control-label">Parent/Guardian's Address:</label><div class="col-sm-9"><input type="text" name="parent_address" value="{{ @profile.parent_address }}" class="form-control" /></div></div>
<div class="form-group"><label for="parent_phone" class="col-sm-3 control-label">Parent/Guardian's Phone:</label><div class="col-sm-9"><input type="tel" name="parent_phone" value="{{ @profile.parent_phone }}" class="form-control" /></div></div>
<div class="form-group"><label for="parent_email" class="col-sm-3 control-label">Parent/Guardian's Email:</label><div class="col-sm-9"><input type="email" name="parent_email" value="{{ @profile.parent_email }}" class="form-control" /></div></div>
<div class="form-group"><label for="medical" class="col-sm-3 control-label">Medical Conditions:</label><div class="col-sm-9"><textarea name="medical" class="form-control">{{ @profile.medical }}</textarea></div></div>
<div class="form-group"><label for="allergies" class="col-sm-3 control-label">Allergies:</label><div class="col-sm-9"><textarea name="allergies" class="form-control">{{ @profile.allergies }}</textarea></div></div>
<div class="form-group"><label for="emergency_name" class="col-sm-3 control-label">Emergency Contact's Name:</label><div class="col-sm-9"><input type="text" name="emergency_name" value="{{ @profile.emergency_name }}" class="form-control" /></div></div>
<div class="form-group"><label for="emergency_phone" class="col-sm-3 control-label">Emergency Contact's Phone:</label><div class="col-sm-9"><input type="tel" name="emergency_phone" value="{{ @profile.emergency_phone }}" class="form-control" /></div></div>
<div class="form-group"><label for="emergency_relationship" class="col-sm-3 control-label">Relation to Emergency Contact:</label><div class="col-sm-9"><input type="text" name="emergency_relationship" value="{{ @profile.emergency_relationship }}" class="form-control" /></div></div>
<div class="form-group"><div class="col-sm-offset-3 col-sm-9"><button type="submit" class="form-control btn-success">Submit</button></div></div>
</form>
</true>
<false>
<div>
<div>First name: {{ @profile.first_name }}</div>
<div>Last name: {{ @profile.last_name }}</div>
</div>
</false>
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

</body>
</html> 