$(document).ready(function(){
	if ($(".shortArchives li.open").length == 0) {
		$(".shortArchives li:first").addClass('open');
	}
	$(".shortArchives li:not(.open) ul").hide();

	$(".shortArchives li span").each( function () {
		var txt = $(this).text();
		$(this).replaceWith('<a href="" class="archives-year">' + txt + '<\/a>') ;
	});

	$(".shortArchives li a.archives-year").click(function(){
		if ($(this).next(".shortArchives li ul:visible").length != 0) {
			$(".shortArchives li ul:visible").slideUp("normal", function () { $(this).parent().removeClass("open") });
			$(".shortArchives li:first").removeClass('open');
		} else {
			$(".shortArchives li ul").slideUp("normal", function () { $(this).parent().removeClass("open") });
			$(this).next("ul").slideDown("normal", function () { $(this).parent().addClass("open") });
		}
		return false;
	});
});
