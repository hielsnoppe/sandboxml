<?php

include("config.php");

SandboXML::init($config);

$action = isset ($_POST["action"]) ? $_POST["action"] : null;
$input = isset ($_POST["input"]) ? $_POST["input"] : array("primary" => "", "secondary" => "");
$options = isset ($_POST["options"]) ? $_POST["options"] : array();

$result = SandboXML::action($action, $input, $options);

$error_count = count($result["errors"]);
$error_count = array_reduce($result["errors"], function($a, $b) { return $a + count($b); }, 0);
$action_title = isset($action) ? $action : "choose action";
$action_value = isset($action) ? $action : "";
$input_active_class = !isset($action) ? " active" : "";
$error_active_class = isset($action) && $error_count > 0 ? " active" : " disabled";
$result_active_class = isset($action) && $error_count == 0 ? " active" : "";

echo('<?xml version="1.0"?>');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><? echo(APP_TITLE); ?> v<?php echo(APP_VERSION); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link href="<?php echo(App::config('path.css')); ?>/prettify.css" rel="stylesheet" />
	<link href="<?php echo(App::config('path.css')); ?>/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo(App::config('path.css')); ?>/bootstrap-responsive.min.css" rel="stylesheet" />
	<style>
body {
	padding-top: 60px;
}
textarea {
	width: 100%;
	height: 500px;
	font-family: monospace;
}
pre.pre-scrollable {
	height: 500px;
}
	</style>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<form action="" method="post">

	<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
	<div class="container">
	<div class="collapse nav-collapse">
		<ul class="nav">
			<li class="dropdown">
				<a href="#" class="brand" data-toggle="dropdown"><? echo(APP_TITLE); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#about" data-toggle="tab">About</a></li>
					<li><a href="#help" data-toggle="tab">Help</a></li>
				</ul>
			</li>
			<li class="<?php echo($input_active_class); ?>"><a href="#input-primary" data-toggle="tab">input A</a></li>
			<li><a href="#input-secondary" data-toggle="tab">input B</a></li>
			<?php if (isset($action)): ?>
			<li class="<?php echo($error_active_class); ?>"><a href="#error" data-toggle="tab"><?php echo $error_count; ?> errors</a></li>
			<li class="<?php echo($result_active_class); ?>"><a href="#result" data-toggle="tab">result</a></li>
			<?php endif; ?>
		</ul>
	</div><!--/.nav-collapse -->

	<a class="btn btn-navbar pull-right" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</a>

    <div class="btn-group pull-right btn-split">
		<button type="submit" name="action" value="<?php echo($action_value); ?>" class="btn btn-primary"><?php echo($action_title); ?></button>
		<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a href="#" data-value="transform">transform</a></li>
			<li><a href="#" data-value="validate">validate</a></li>
			<li><a href="#" data-value="emogrify">emogrify</a></li>
			<li><a href="#" data-value="namedEntities">namedEntities</a></li>
			<li><a href="#" data-value="numericEntities">numericEntities</a></li>
			<li><a href="#" data-value="preview" data-target="_blank">preview</a></li>
		</ul>
    </div>

	</div></div></div>

	<div class="container-fluid tab-content">

	<fieldset class="row-fluid tab-pane<?php echo($input_active_class); ?>" id="input-primary">
		<div class="span12">

			<?php echo Html::select(App::config("files.primary")); ?>

			<textarea name="input[primary]"><?php
				echo htmlspecialchars($input["primary"]);
			?></textarea>

		</div>
	</fieldset>

	<fieldset class="row-fluid tab-pane" id="input-secondary">
		<div class="span12">

			<?php echo Html::select(App::config("files.secondary")); ?>

			<textarea name="input[secondary]"><?php
				echo htmlspecialchars($input["secondary"]);
			?></textarea>

		</div>
	</fieldset>

	<fieldset class="row-fluid tab-pane<?php echo($error_active_class); ?>" id="error">
		<div class="span12">

		<table class="table table-condensed table-striped">
			<thead>
				<tr><th>line</th><th>column</th><th>message</th></tr>
			</thead>
			<tbody><?php
				foreach ($result["errors"] as $group) {
					foreach ($group as $error) {
						echo "<tr><td>", $error->line, "</td><td>", $error->column, "</td><td>", $error->message, "</td></tr>";
					}
				}
			?></tbody>
		</table>

		</div>
	</fieldset>

	<fieldset class="row-fluid tab-pane<?php echo($result_active_class); ?>" id="result">
		<div class="span12">

		<pre class="pre-scrollable prettyprint linenums tab-pane <?php echo ($error_count == 0) ? ' active' : ''; ?>"><?php
			echo htmlspecialchars($result["output"]);
		?></pre>

		<textarea name="input[tertiary]" class="tab-pane"><?php
			echo htmlspecialchars($result["output"]);
		?></textarea>

		</div>
	</fieldset>

	<article class="row-fluid tab-pane" id="about">
		<nav class="span3">
			<ul class="nav nav-list well">
				<li><a href="#about-contribute">How to contribute</a></li>
				<li><a href="#about-changelog">Changelog</a></li>
				<li><a href="#about-license">License</a></li>
			</ul>
		</nav>

		<div class="span9">

		<h1>About</h1>

		<section id="about-general">
		<p>
<em>SandboXML</em> is a tool for <strong>testing</strong> XML related technologies like
<strong>transformation</strong> with XSL,
<strong>validation</strong> with <strong>XML Schema</strong> or <strong>DTD</strong>,
<strong>evaluation</strong> of XPath expressions and other less XML related tasks like
<strong>bringing CSS inline</strong> using
<em><a href="http://www.pelagodesign.com/sidecar/emogrifier/" target="_blank">Emogrifier</a></em> or
<strong>converting special characters</strong> to <strong>named entities</strong> aswell as named to <strong>numeric entities</strong>.
		</p>
		</section>

		<section id="about-contribute">
		<h2>How to contribute</h2>
		<p>
Feel free to fork and contribute to the public repository at
<a href="http://github.com/hielsnoppe/sandboxml">
http://github.com/hielsnoppe/sandboxml</a>. All bug reports and fixes, feature
requests and contributions are welcome. Bear in mind though, that I build this
in my spare time and therefore will not always be able to respond promptly.
		</p>
		</section>

		<section id="about-changelog">
		<h2>Changelog</h2>
		<p>
Version numbers follow the system of
<em><a href="http://semver.org/" target="_blank">Semantic Versioning</a></em>.
		</p>
		<p>
<dl class="dl-horizontal">
	<dt>0.0.1</dt>
	<dd>First public version.</dd>
</dl>
		</p>
		</section>

		<section id="about-license">
		<h2>License</h2>
		<p>
Copyright &copy; 2012 <a href="http://www.nielshoppe.de" target="_blank">Niels
Hoppe</a> and other contributors. <em>SandboXML</em> is free software published
under the <a href="http://opensource.org/licenses/MIT" target="_blank">MIT
license</a>:
		</p>
		<p>
Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:
		</p>
		<p>
The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.
		</p>
		<p>
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
		</p>
		<p>
Please notice that <em>SandboXML</em> makes use of libraries some of which are
published under different licenses:
<dl class="dl-horizontal">
	<dt><a href="http://xmlsoft.org/" target="_blank">libxml</a></dt>
	<dd><a href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a></dd>

	<dt><a href="http://xmlsoft.org/XSLT" target="_blank">libxslt</a></dt>
	<dd><a href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a></dd>

	<dt><a href="http://www.pelagodesign.com/sidecar/emogrifier/" target="_blank">Emogrifier</a></dt>
	<dd><a href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a></dd>

	<dt><a href="http://jquery.com/" target="_blank">jQuery</a></dt>
	<dd><a href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a></dd>

	<dt><a href="http://twitter.github.com/bootstrap/" target="_blank">Bootstrap</a></dt>
	<dd><a href="http://opensource.org/licenses/Apache-2.0" target="_blank">Apace-2.0</a> with icons from <a href="http://glyphicons.com/" target="_blank">Glyphicons</a></dd>
</dl>
		</p>
		</section>

		</div>
	</article>

	<article class="row-fluid tab-pane" id="help">
		<nav class="span3">
			<ul class="nav nav-list well">
				<li><a href="#help-general">General</a></li>
			</ul>
		</nav>

		<div class="span9">

		<h1>Help</h1>

		<section id="help-general">
		<h2>General</2>
		</section>

		</div>
	</article>

	</div>

	</form>

	<script src="<?php echo(App::config('path.js')); ?>/jquery-1.8.3.min.js"></script>
	<script src="<?php echo(App::config('path.js')); ?>/prettify.js"></script>
	<script src="<?php echo(App::config('path.js')); ?>/bootstrap.min.js"></script>
	<script src="src/js/sandboxml.js"></script>
</body>
</html>
