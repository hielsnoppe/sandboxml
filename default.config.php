<?php

function __autoload($class) {
	if (file_exists("src/php/$class.class.php")) {
		require_once("src/php/$class.class.php");
	} else {
		require_once("lib/php/$class.class.php");
	}
}

$config = array(
	"files" => array(
		"primary" => Util::ls("data/examples", "/(?:\.xml|\.xhtml|\.html)/"),
		"secondary" => array(
			"DTD" => Util::ls("data/examples", "/(?:\.dtd)/"),
			"XSD" => Util::ls("data/examples", "/(?:\.xsd)/"),
			"XSL" => Util::ls("data/examples", "/(?:\.xsl|\.xslt)/")
		)
	)
);

?>
