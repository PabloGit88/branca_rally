<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Game;

class Team {
	const TEAM1 = 'Team1';
	const TEAM2 = 'Team2';
	
	public static $TEAMS = array(
		self::TEAM1 => 'Equipo Único',
		self::TEAM2 => 'Equipo Branca'
	);
	
	public static function getTeamName($teamKey)
	{
		return self::$TEAMS[$teamKey];
	}
	
	public static function getTeams()
	{
		return self::$TEAMS;
	}
}