<?php

/**
 * Description of MySQLDB
 *
 * @author kenneth
 */
class MySQLDatabase extends mysqli {

  private static $host = 'localhost';
  private static $user = 'root';
  private static $pass = 'root';
  private static $db = 'phphelper';

  public function __construct() {
    parent::__construct(self::$host, self::$user, self::$pass, self::$db);
    if ($this->connect_error) {
      die(__METHOD__ . '::[' . $this->errno . ']::' . $this->connect_error);
    }
  }

}
