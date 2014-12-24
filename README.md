Index
	- Login
	- Signup for an account

Main
	- Navigate to different sections in the app

Profile
	- User information
	
Club
	- Update club information (manager)
	- Register team to tournament (manager)
	- Add / remove club (manager)

Team
	- Update team information (manager)
	- Add / remove team (manager)
	- Move players between teams (manager)

Tournament
	- dates and teams registered
	
WORKFLOW:

	- requirement: an account can only be part of one club
	- requirement: a team is created for each tournament
	
	*acl: club managers approve / reject / remove players from club
	*acl: club managers create / delete teams
	*acl: club managers move players between teams
	*acl: players update their own profile
	*acl: players can leave club
	
	First time:
	
		Club manager creates account
		Club manager creates club C				<-- club_admin_acl
	
		Player creates account
		Player fills in profile
		Player requests to join club
	
		Club manager accepts player's request to join club

	For each tournament:
	
		Club manager creates team T1, T2, T3			<-- team_admin_acl
		Club manager moves player between teams
