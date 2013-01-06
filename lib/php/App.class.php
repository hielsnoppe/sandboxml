<?php

/*
Copyright 2013 Niels Hoppe
http://github.com/hielsnoppe/misc

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

require_once("Config.class.php");

class App {

	private static $config = null;
	private static $default = array();

	public static function init($config = array()) {
		self::$config = new Config(array_replace_recursive(self::$default, $config));
	}

	public static function config($key, $value = null) {
		if (empty($value)) {
			// if no value is given, then
			// if an array is given replace the whole config
			if (is_array($key)) self::$config = new Config($key);
			// otherwise read from config
			else return self::$config->get($key);
		} else {
			// otherwise write to config
			self::$config->set($key, $value);
		}
	}

}

?>
