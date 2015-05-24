<?php

/**
 * Description of AutoLoader
 *
 * @author kenneth
 */
class AutoLoader {

  public static $loader;

  const CLASSES_EXTENSIONS = '.class.php';

  public static function init() {
    if (self::$loader == NULL) {
      self::$loader = new AutoLoader();
    }
    return self::$loader;
  }

  private function __construct() {
    set_include_path(__DIR__ . DIRECTORY_SEPARATOR);
    spl_autoload_register(array($this, 'classes'));
  }

  public function classes($class_name) {
    $filename = get_include_path() . $class_name . self::CLASSES_EXTENSIONS;
    if (file_exists($filename)) {
      require_once $filename;
    }
  }

}

// Invoke AutoLoader class.
AutoLoader::init();
