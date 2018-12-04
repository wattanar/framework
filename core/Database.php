<?php

namespace Core;

use Wattanar\Sqlsrv;

class Database {

	public static function connect($default = true, $connectionInfo = []) {
		if ($default === false) {

				return Sqlsrv::connect(
					$connectionInfo['hostname'], 
					$connectionInfo['username'], 
					$connectionInfo['password'], 
					$connectionInfo['database']
				);
		} else {
			return Sqlsrv::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}
	}

	public static function rows($conn, $query, $params = null) {
		return Sqlsrv::rows($conn, $query, $params);
	}

	public static function query($conn, $query, $params = null) {
		return Sqlsrv::query($conn, $query, $params);
	}

	public static function hasRows($conn, $query, $params = null) {
		return Sqlsrv::hasRows($conn, $query, $params);
	}
}