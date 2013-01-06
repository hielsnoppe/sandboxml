<?php

define("APP_TITLE", "SandboXML");
define("APP_VERSION", "0.0.1");

class SandboXML extends App {

	private static $default = array(
		"path" => array(
			"css" => "lib/css",
			"js" => "lib/js"
		)
	);
	private static $errors = array();

	public static function init($config = array()) {
		error_reporting(-1);
		libxml_use_internal_errors(true);
		parent::init(array_replace_recursive(self::$default, $config));
	}

	public static function action($action, $input, $options) {
		if (isset($options["namedEntities"])) {
			$input["primary"] = namedEntities($input["primary"]);
		}
		if (isset($options["numericEntities"])) {
			$input["primary"] = numericEntities($input["primary"]);
		}
		if (isset($options["emogrify"])) {
			$input["primary"] = emogrify($input["primary"]);
		}

		switch ($action) {
		case "preview":
			exit($input["tertiary"]);
		case "transform":
			$xmlDom = new DOMDocument();
			$xslDom = new DOMDocument();
			self::loadXML($xmlDom, $input["primary"]);
			self::loadXML($xslDom, $input["secondary"]);
			$result["output"] = self::transform($xmlDom, $xslDom);
			break;
		case "validate":
			$xmlDom = new DOMDocument();
			self::loadXML($xmlDom, $input["primary"]);
			$result["output"] = self::validate($xmlDom, $input["secondary"]) ? "valid" : "not valid";
			break;
		case "emogrify":
			$result["output"] = self::emogrify($input["primary"]);
			break;
		case "namedEntities":
			$result["output"] = self::namedEntities($input["primary"]);
			break;
		case "numericEntities":
			$result["output"] = self::numericEntities($input["primary"]);
			break;
		default:
			$result["output"] = "Sorry, not implemented.";
		}

		$result["errors"] = self::$errors;
		return $result;
	}

// /////////////////////////////////////////////////////////////////////////////

public static function loadXML(&$dom, $xml) {
	libxml_clear_errors();
	$success = $dom->loadXML($xml);
/*
	// XML laden
	$xmlDom = new DOMDocument();
	if (!self::loadXML($xmlDom, $xml)) {
		$xmlDom->loadHTML($xml);
	}
	$xmlDom->xinclude();
*/
	self::$errors[] = libxml_get_errors();
	libxml_clear_errors();
	return $success;
}

public static function emogrify($html) {
	$emo = new Emogrifier($html);
	return $emo->emogrify();
}

public static function validate($xmlDom, $xsd = null) {
	libxml_clear_errors();
	if ($xsd) {
		$output = $xmlDom->schemaValidateSource($xsd) ? "valid" : "not valid";
	} else {
		$output = $xmlDom->validate() ? "valid" : "not valid";
	}
	self::$errors[] = libxml_get_errors();
	libxml_clear_errors();

	return $output;
}

public static function transform($xmlDom, $xslDom) {
	libxml_clear_errors();

	$xsl = new XsltProcessor();						// create XSLT processor
	if ($xsl->importStylesheet($xslDom)) {			// load stylesheet
		$output = $xsl->transformToXML($xmlDom);	// transformation returns XML

		self::$errors[] = libxml_get_errors();
		libxml_clear_errors();
	} else $output = "";
	self::$errors[] = libxml_get_errors();
	libxml_clear_errors();

	return $output;
}

public static function numericEntities($html) {
	return Util::numericEntities($html);
}

public static function namedEntities($html, $exclude = array('"', "'", "<", ">", "&", " ")) {
	return Html::specialchars($html, $exclude);
}

}

?>
