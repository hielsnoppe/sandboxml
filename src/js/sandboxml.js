var tabs;
$(document).ready(function() {
	prettyPrint();

	//$(document.getElementById("preview").contentWindow.document).ready(function() {});

	$(".btn-split .dropdown-menu a").click(function() {
		$this = $(this);
		label = $this.html();
		value = $this.data("value");
		target = $this.data("target");
		if (target === undefined) target = "";

		$button = $this.parents(".btn-split").find("button:first");
		$button.val(value).html(label);

		if ($button.is("[type=submit]")) {
			$form = $button.parents("form");
			$form.attr("target", target);
		}
	});
	$("select").change(function() {
		$this = $(this);
		$.ajax({
			url: $(this).val(),
			data: null,
			success: function(data, textStatus, jqXHR) {
				$this.next("textarea").val(data);
			},
			dataType: "text" // xml, json, script, or html
		});
	});
	$("textarea").on({
		focus: function() {
			$(this).show().siblings("pre").hide();
		},
		blur: function() {
			//$(this).hide().siblings("pre").show();	// PRE not updated
		}
	});
	$("pre").on({
		dblclick: function() {
			$(this).hide().siblings("textarea").focus();
		}
	});
});
