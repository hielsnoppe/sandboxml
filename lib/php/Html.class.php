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

class Html {

/**
 * simple example without groups:
 *
 * $data = array(
 *     "option1" => "description",
 *     "option2" => "description"
 * )
 *
 * example with groups:
 *
 * $data = array(
 *     "label1" => array(
 *         "option1" => "description",
 *         "option2" => "description"
 *     ),
 *     "label2" => array(
 *         "option3" => "description",
 *         "option4" => "description"
 *     )
 * )
 */
static function select($data) {
	$select = new SimpleXMLElement("<select />");

	if (is_array($data) && count($data)) {
		// only proceed if $data is a non-empty array
		foreach ($data as $label => $group) {
			if (is_array($group)) {
				// groups are used
				$optgroup = $select->addChild("optgroup");
				$optgroup->addAttribute("label", $label);
				foreach ($group as $value => $item) {
					$option = $optgroup->addChild("option", $item);
					if (is_int($value)) $value = $item;
					$option->addAttribute("value", $value);
				}
			} else {
				// no groups are used
				$option = $select->addChild("option", $group);
				if (is_int($label)) $label = $group;
				$option->addAttribute("value", $label);
			}
		}
	}
	return $select->asXML();
}

/**
 * Acts like htmlspecialchars but skips characters from $exclude
 */
public static function specialchars($html, $exclude = array('"', "'", "<", ">", "&", " ")) {
	$list = get_html_translation_table(HTML_ENTITIES);
	foreach ($exclude as $character) {
		unset($list[$character]);
	}
	return strtr($html, $list);
}

}

?>
