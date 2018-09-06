<?php

namespace Core;

use Wattanar\Sqlsrv;

class Database {
	public static function connect($engine = DB_ENGINE) {
		switch ($engine) {
			case 'sqlsrv':
				return Sqlsrv::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				break;

			default:
				return null;
				break;
		}
	}

	public static function rows($conn, $query, $params = null, $engine = DB_ENGINE) {
		switch ($engine) {
			case 'sqlsrv':
				return Sqlsrv::rows($conn, $query, $params);
				break;
			
			default:
				return null;
				break;
		}
	}

	public static function query($conn, $query, $params = null, $engine = DB_ENGINE) {
		switch ($engine) {
			case 'sqlsrv':
				return Sqlsrv::query($conn, $query, $params);
				break;
			
			default:
				return null;
				break;
		}
	}

	public static function hasRows($conn, $query, $params = null, $engine = DB_ENGINE) {
		switch ($engine) {
			case 'sqlsrv':
				return Sqlsrv::hasRows($conn, $query, $params);
				break;
			
			default:
				return null;
				break;
		}
	}
}