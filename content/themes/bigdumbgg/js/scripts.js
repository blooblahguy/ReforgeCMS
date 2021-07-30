$(document).ready(function() {
	// console.log("ready")
})

$(".stream_nav a").on("click", function(ev) {
	var el = $(this)
	var hash = el.attr("id")

	$(".stream_nav a.active").removeClass("active")
	el.addClass("active")

	$("#twitch-embed").html("")
	$("#twitch-chat-embed").html("<iframe id=\"twitch-chat-embed\" src=\"https://www.twitch.tv/embed/"+hash+"/chat?parent=bigdumb.gg&parent=localhost&darkpopout\" width=\"200\"></iframe>")

	var embed = new Twitch.Embed("twitch-embed", {
		height: 460,
		channel: hash,
		layout: "video",
		autoplay: false,
		muted: true,
		parent: "bigdumb.gg"
	});
	
	embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
		var player = embed.getPlayer();
		player.play();
	});

	ev.preventDefault()
	return false
})