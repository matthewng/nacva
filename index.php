<?php

$f3 = require('../lib/base.php');

$f3->config('config.ini');

$classes = array(
	'Base'=>
		array(
			'hash',
			'json',
			'session'
		),
	'Cache'=>
		array(
			'apc',
			'memcache',
			'wincache',
			'xcache'
		),
	'DB\SQL'=>
		array(
			'pdo',
			'pdo_dblib',
			'pdo_mysql',
		),
	'Auth'=>
		array('ldap','pdo'),
	'Bcrypt'=>
		array(
			'mcrypt',
			'openssl'
		),
	'Image'=>
		array('gd'),
	'Lexicon'=>
		array('iconv'),
	'Web'=>
		array('curl','openssl','simplexml')
);
$f3->set('classes', $classes);

/** Authentication **/

$f3->route('GET /',
	function($f3) {
		if ($f3->exists('SESSION.accountId')) {
			$f3->reroute('/main');
		} else {
			$f3->reroute('/login');
		}
	}
);
$f3->route('GET /signup',
	function($f3) {
		echo View::instance()->render('signup.php');
	}
);
$f3->route('POST /signup',
	function($f3) {
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->email = $f3->get('POST.email');
		$account->password = md5($f3->get('POST.password'));
		$account->save();
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->account_id = $account->account_id;
		$profile->first_name = $f3->get('POST.first_name');
		$profile->last_name = $f3->get('POST.last_name');
		$profile->save();
		
		if(!$account->dry() && !$profile->dry()) {
			$f3->clear('SESSION');
			$f3->set('SESSION.accountId', $account->account_id);
		} else {
			// signup error!	
		}
		
		$f3->reroute('/');
	}
);
$f3->route('GET /login',
	function($f3) {
		echo View::instance()->render('login.php');
	}
);
$f3->route('POST /login',
	function($f3) {
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new \DB\SQL\Mapper($db, 'accounts');
		$auth = new \Auth($account, array('id'=>'email', 'pw'=>'password'));
		if($auth->login($f3->get('POST.email'), md5($f3->get('POST.password')))) {
			$f3->clear('SESSION');
			
			$account->load(array('email=?', $f3->get('POST.email')));
			$f3->set('SESSION.accountId', $account->account_id);
		}
		
		$f3->reroute('/');
	}
);
$f3->route('GET /logout',
	function($f3) {
		$f3->clear('SESSION');
		$f3->reroute('/');
	}
);

/** Application - navigation **/

$f3->route('GET /main',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$today = new DateTime();
		$tournaments = new DB\SQL\Mapper($db, 'tournaments');
		$list = $tournaments->find('deadline > "' . $today->format('Y-m-d') . '"');
		$f3->set('tournaments', $list);
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=? AND status IN (\'ACTIVE\', \'PENDING\')', 
			$account->account_id));
		
		if(!$club_member->dry()) {
			$club = new DB\SQL\Mapper($db, 'clubs');
			$club->load(array('club_id=?', $club_member->club_id));
			$f3->set('club', $club);
			
			$isClubManager = $club->creator_account_id == $account->account_id;
			$numPendingMembers = 0;
			if($isClubManager) {
				$pending_club_members = new DB\SQL\Mapper($db, 'club_members');
				$numPendingMembers = $pending_club_members->count('status = \'PENDING\'');
			}
			
			$f3->set('isActiveMember', $club_member->status == 'ACTIVE');
			$f3->set('isClubManager', $isClubManager);
			$f3->set('numPendingMembers', $numPendingMembers);

			$f3->set('clubs', NULL);
		} else {
			$f3->set('club', NULL);
			$f3->set('isClubManager', FALSE);
			$f3->set('numPendingMembers', 0);
			
			$clubs = new DB\SQL\Mapper($db, 'clubs');
			$list = $clubs->find();
			$f3->set('clubs', $list);			
		}
		
		$player = new DB\SQL\Mapper($db, 'players');
		$player->load(array('account_id=?', $account->account_id));
		$f3->set('player', $player);
		
		$teams = $db->exec(
			'SELECT t.*, tn.name as tournament_name
			 FROM teams t, tournaments tn, players p 
			 WHERE p.team_id = t.team_id
			 AND tn.tournament_id = t.tournament_id
			 AND p.account_id = ?
			 ORDER BY tn.date_start', $account->account_id);
		$f3->set('teams', $teams);
		
		echo Template::instance()->render('main.php');
	}
);

/** Application - profile management**/

$f3->route('GET /profile/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->load(array('profile_id=?', $f3->get('PARAMS.id')));
		$f3->set('profile', $profile);
		
		$f3->set('states', ['CA', 'MA', 'NY']);
		$f3->set('countries', ['United States', 'Canada']);
		$f3->set('education_levels', ['PRIMARY', 'HIGH_SCHOOL', 
			'SOME_COLLEGE', 'COLLEGE', 'GRADUATE', 'DECLINE']);
		
		// perms
		if($profile->account_id == $account->account_id) {
			$f3->set('canEditProfile', TRUE);
		} else {
			$f3->set('canEditProfile', FALSE);
		}
		
		echo Template::instance()->render('profile.php');
	}
);
$f3->route('GET /profile',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->load(array('account_id=?', $account->account_id));
		$f3->set('profile', $profile);
		
		$f3->reroute('/profile/' . $profile->profile_id);
	}
);
$f3->route('POST /profile/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);

		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->load(array('account_id=?', $account->account_id));
		$profile->copyFrom('POST');
		$profile->account_id = $account->account_id;
		if(empty($profile->dob)) {
			$profile->dob = NULL;
		}
		$profile->save();
		$f3->reroute('/profile');
	}
);

/** Application - tournament **/

$f3->route('GET /tournament/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$tournament = new DB\SQL\Mapper($db, 'tournaments');
		$tournament->load(array('tournament_id=?', $f3->get('PARAMS.id')));
		$f3->set('tournament', $tournament);
		
		$teams = new DB\SQL\Mapper($db, 'teams');
		$list = $teams->find(array('tournament_id=?', $tournament->tournament_id));
		$f3->set('teams', $list);
		
		echo Template::instance()->render('tournament.php');
	}
);

/** Application - club management **/

$f3->route('GET /club/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club = new DB\SQL\Mapper($db, 'clubs');
		$club->load(array('club_id=?', $f3->get('PARAMS.id')));
		$f3->set('club', $club);
		
		$today = new DateTime();
		$tournaments = new DB\SQL\Mapper($db, 'tournaments');
		$list = $tournaments->find('deadline > "' . $today->format('Y-m-d') . '"');
		$f3->set('tournaments', $list);
		
		$teams = $db->exec(
			'SELECT t.*, tn.name as tournament_name
			FROM teams t, tournaments tn
			WHERE t.tournament_id = tn.tournament_id
			AND t.club_id = ?', $club->club_id);
		
		$teamsPerTournament = array();
		foreach($teams as $team) {
			$teamsPerTournament[$team['tournament_name']][] = $team;
		}
		$f3->set('teamsPerTournament', $teamsPerTournament);
		
		$members = $db->exec(
			'SELECT pf.* 
			 FROM profiles pf, club_members cm 
			 WHERE pf.account_id = cm.account_id
			 AND cm.club_id = ?
			 AND status = \'ACTIVE\'', $club->club_id);
		$f3->set('members', $members);
		
		$isMember = FALSE;
		foreach($members as $member) {
			if($member['account_id'] == $account->account_id) {
				$isMember = TRUE;	
			}
		}
		
		$pendings = $db->exec(
			'SELECT pf.* 
			 FROM profiles pf, club_members cm 
			 WHERE pf.account_id = cm.account_id
			 AND cm.club_id = ?
			 AND status = \'PENDING\'', $club->club_id);
		$f3->set('pendings', $pendings);
		
		$isPendingMember = FALSE;
		foreach($pendings as $pending) {
			if($pending['account_id'] == $account->account_id) {
				$isPendingMember = TRUE;	
			}
		}
		
		$clubManager = new DB\SQL\Mapper($db, 'profiles');
		$clubManager->load(array('account_id=?', $club->creator_account_id));
		$f3->set('clubManager', $clubManager);
		
		// PERMS
		$f3->set('isClubMember', $isMember);
		$f3->set('isPendingMember', $isPendingMember);
		$f3->set('isClubManager', $club->creator_account_id == $account->account_id);

		echo Template::instance()->render('club.php');
	}
);
$f3->route('GET /club',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=? 
			AND status IN (\'ACTIVE\', \'PENDING\')', $account->account_id));

		if($club_member->dry()) {
			$f3->reroute('/');
		} else {		
			$f3->reroute('/club/' . $club_member->club_id);
		}
	}
);
$f3->route('POST /club',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');

		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);

		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club = new DB\SQL\Mapper($db, 'clubs');
		$club->copyFrom('POST');
		$club->creator_account_id = $account->account_id;
		$club->save();
		
		if(!$club->dry()) {
			$club_member = new DB\SQL\Mapper($db, 'club_members');
			$club_member->account_id = $account->account_id;
			$club_member->club_id = $club->club_id;
			$club_member->status = 'ACTIVE';
			$club_member->save();			
		}
		
		$f3->reroute('/club');
	}
);
$f3->route('DELETE /club',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');

		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$club = new DB\SQL\Mapper($db, 'clubs');
		$club->load(array('creator_account_id=?', $f3->get('SESSION.accountId')));
		
		if(!$club->dry()) {
			$db->begin();
			$db->exec('DELETE p, t FROM players p, teams t WHERE p.team_id = t.team_id AND t.club_id=?', $club->club_id);
			$db->exec('DELETE FROM clubs WHERE creator_account_id=?', $club->creator_account_id);
			$db->exec('DELETE FROM club_members WHERE club_id=?', $club->club_id);
			$db->commit();
		}
	}
);
$f3->route('POST /club/join',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);

		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=?', $account->account_id));
		if($club_member->dry()) {
			$club_member->account_id = $account->account_id;
			$club_member->club_id = $f3->get('POST.club_id');
			$club_member->status = 'PENDING';
			$club_member->save();
		} else if($club_member->status = 'CANCELED') {
			$club_member->status = 'PENDING';
			$club_member->save();
		}
		
		$f3->reroute('/club');
	}
);
$f3->route('POST /club/leave',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);

		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=? 
			AND status IN (\'ACTIVE\', \'PENDING\')', $account->account_id));
		if(!$club_member->dry()) {
			$db->begin();
			$db->exec('DELETE p FROM players p WHERE p.account_id=?', $account->account_id);
			$db->exec('UPDATE club_members SET status = \'CANCELED\' WHERE account_id=?', $account->account_id);
			$db->commit();
		}

		$f3->reroute('/club');
	}
);
$f3->route('POST /club/accept',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');

		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=? AND status IN (\'PENDING\')', 
			$f3->get('POST.account_id')));
		if(!$club_member->dry()) {
			$club_member->status = 'ACTIVE';
			$club_member->save();
		}

		$f3->reroute('/club');
	}
);
$f3->route('POST /club/reject',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');

		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club_member = new DB\SQL\Mapper($db, 'club_members');
		$club_member->load(array('account_id=? AND status IN (\'ACTIVE\', \'PENDING\')', 
			$f3->get('POST.account_id')));
		if(!$club_member->dry()) {
			$club_member->status = 'REJECTED';
			$club_member->save();
		}

		$f3->reroute('/club');
	}
);

/** Application - team management **/

$f3->route('GET /team/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$team = new DB\SQL\Mapper($db, 'teams');
		$team->load(array('team_id=?', $f3->get('PARAMS.id')));
		$f3->set('team', $team);
		
		$club = new DB\SQL\Mapper($db, 'clubs');
		$club->load(array('club_id=?', $team->club_id));
		$f3->set('club', $club);
		
		$tournament = new DB\SQL\Mapper($db, 'tournaments');
		$tournament->load(array('tournament_id=?', $team->tournament_id));
		$f3->set('tournament', $tournament);
		
		$players = $db->exec(
			'SELECT pf.*, s.team_id, s.name as team_name, s.tournament_id
			 FROM profiles pf
			 INNER JOIN club_members cm 
			 	ON pf.account_id = cm.account_id
			 INNER JOIN teams t 
			 	ON cm.club_id = t.club_id
			 LEFT JOIN (
			 	SELECT p.account_id, t2.* 
			 	FROM players p, teams t2 
			 	WHERE p.team_id = t2.team_id
				AND t2.tournament_id = ?) as s 
				ON pf.account_id = s.account_id
			 WHERE cm.status = "ACTIVE"
			 AND t.team_id = ?'
		, array(1=>$team->tournament_id, 2=>$team->team_id));
		
		$roster = array();
		$teamfull = array();
		$teamless = array();
		foreach($players as $player) {	
			if($player['team_id'] == NULL) {
				$teamless[] = $player;
			} elseif ($player['team_id'] == $team->team_id) {
				$roster[] = $player;
			} else {
				$teamfull[] = $player;
			}
		}
		$f3->set('roster', $roster);
		$f3->set('teamfull', $teamfull);
		$f3->set('teamless', $teamless);
		
		// PERMS
		$f3->set('canEditRoster', $account->account_id == $club->creator_account_id);
		
		echo Template::instance()->render('team.php');
	}
);
$f3->route('POST /team',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');

		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$account = new DB\SQL\Mapper($db, 'accounts');
		$account->load(array('account_id=?', $f3->get('SESSION.accountId')));
		$f3->set('account', $account);
		
		$club = new DB\SQL\Mapper($db, 'clubs');
		$club->load(array('creator_account_id=?', $account->account_id));
		$f3->set('club', $club);
		
		$team = new DB\SQL\Mapper($db, 'teams');
		$team->copyFrom('POST');
		$team->club_id = $club->club_id;
		$team->save();
		
		$f3->reroute('/team/' . $team->team_id);
	}
);
$f3->route('DELETE /team/@id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$team = new DB\SQL\Mapper($db, 'teams');
		$team->load(array('team_id=?', $f3->get('PARAMS.id')));		
		if(!$team->dry()) {
			$db->begin();
			$db->exec('DELETE p, t FROM players p, teams t WHERE p.team_id = t.team_id AND t.team_id=?', $team->team_id);
			$db->commit();
		}
	}
);
$f3->route('PUT /player/@team_id/@profile_id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$team = new DB\SQL\Mapper($db, 'teams');
		$team->load(array('team_id=?', $f3->get('PARAMS.team_id')));
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->load(array('profile_id=?', $f3->get('PARAMS.profile_id')));
		
		$player = new DB\SQL\Mapper($db, 'players');
		$player->team_id = $team->team_id;
		$player->account_id = $profile->account_id;
		$player->save();
	}
);
$f3->route('DELETE /player/@team_id/@profile_id',
	function($f3) {
		if (!$f3->exists('SESSION.accountId')) $f3->reroute('/');
		
		$db = new DB\SQL(
			'mysql:host=localhost;port=3306;dbname=nacva',
			'nacva',
			'admin'
		);
		
		$team = new DB\SQL\Mapper($db, 'teams');
		$team->load(array('team_id=?', $f3->get('PARAMS.team_id')));
		
		$profile = new DB\SQL\Mapper($db, 'profiles');
		$profile->load(array('profile_id=?', $f3->get('PARAMS.profile_id')));
		
		$player = new DB\SQL\Mapper($db, 'players');
		$player->load(array('team_id=? AND account_id=?', $team->team_id, $profile->account_id));
		$player->erase();
	}
);
$f3->run();
