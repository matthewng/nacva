Index
	- Login
	- Signup for an account

Main
	- Manage basic profile (player)
	- Manage teams / club (manager)
	
Club
	- Update club information (manager)
	- Add (manager) / remove team to club (manager)
	- Add / remove club (manager)

Team
	- Update team information (manager)
	- Add / remove team to club (manager)
	- Move players between teams (manager)
	
WORKFLOW:

	*requirement: an account can only be part of one club
	*requirement: a team is created for each tournament
	
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
