<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!-- Website Design By: www.happyworm.com -->
<title>Demo : The jPlayerPlaylist Object</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="__PUBLIC__/js/jPlayer-2.9.0/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/js/jPlayer-2.9.0/lib/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jPlayer-2.9.0/dist/jplayer/jquery.jplayer.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jPlayer-2.9.0/dist/add-on/jplayer.playlist.js"></script>
<script type="text/javascript">
//<![CDATA[
/**
 * 载入LRC
 */
function loadLrc (playlistInstance, index)
{
	if(0 <= index && index < playlistInstance.playlist.length) {
		//window.console.debug(playlistInstance.playlist[index].id);
		var params = {id:index};
		$.getJSON('LoadLrcById.html', params, function (response) {
			$('#lrcInfoList').html(response);
		});
	}
}
/**
 * 播放时间改变响应时间
 */
function playtimeChangeTrigger (totalTime, leftTime)
{
    moveLrc(totalTime - leftTime);
}
/**
 * 移动LRC内容
 */
function moveLrc (playingTimePoint)
{
    window.console.debug(playingTimePoint);
}

$(document).ready(function(){

	var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_N",
		cssSelectorAncestor: "#jp_container_N"
	}, [
		{
			title:"Unit 01 Communication 1.1",
			artist:"EnglishDay.net",
			mp3:"__PUBLIC__/mp3/Unit 01 Communication 1.1.mp3",
			id : 123
		}
	], {
		playlistOptions: {
			enableRemoveControls: true,
			autoPlay : true,
			playEventTrigger : function (playlistObj, indexInPlaylist) {
				loadLrc(playlistObj, indexInPlaylist);
			}
		},
		swfPath: "__PUBLIC__/js/jPlayer-2.9.0/dist/jplayer",
		supplied: "mp3",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		audioFullScreen: true,
		playtimeChangeTrigger : playtimeChangeTrigger
	});

	// Miaow tracks

	$("#playlist-add-bubble").click(function() {
		myPlaylist.add({
			title:"Unit 01" + Math.random(),
			artist:"EnglishDay.net",
			mp3:"__PUBLIC__/mp3/Unit 01 Communication 1.1.mp3",
			id : 12
		});
	});

	// The remove commands

	$("#playlist-remove").click(function() {
		myPlaylist.remove();
	});

	// The shuffle commands

	$("#playlist-shuffle").click(function() {
		myPlaylist.shuffle();
	});

	// The next/previous commands

	$("#playlist-next").click(function() {
		myPlaylist.next();
	});
	$("#playlist-previous").click(function() {
		myPlaylist.previous();
	});

	// The play commands

	$("#playlist-play").click(function() {
		myPlaylist.play();
	});

	// The pause command

	$("#playlist-pause").click(function() {
		myPlaylist.pause();
	});

	// Changing the playlist options

	// Option: autoPlay

	$("#playlist-option-autoPlay-true").click(function() {
		myPlaylist.option("autoPlay", true);
	});

});
//]]>
</script>
</head>
<body>
<div id="jp_container_N" class="jp-video jp-video-270p" role="application" aria-label="media player">
	<div class="jp-type-playlist">
		<div id="jquery_jplayer_N" class="jp-jplayer"></div>
		<div class="jp-gui">
			<div class="jp-video-play">
				<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
			</div>
			<div class="jp-interface">
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-controls-holder">
					<div class="jp-controls">
						<button class="jp-previous" role="button" tabindex="0">previous</button>
						<button class="jp-play" role="button" tabindex="0">play</button>
						<button class="jp-next" role="button" tabindex="0">next</button>
						<button class="jp-stop" role="button" tabindex="0">stop</button>
					</div>
					<div class="jp-volume-controls">
						<button class="jp-mute" role="button" tabindex="0">mute</button>
						<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
						<div class="jp-volume-bar">
							<div class="jp-volume-bar-value"></div>
						</div>
					</div>
					<div class="jp-toggles">
						<button class="jp-repeat" role="button" tabindex="0">repeat</button>
						<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
						<button class="jp-full-screen" role="button" tabindex="0">full screen</button>
					</div>
				</div>
				<div class="jp-details">
					<div class="jp-title" aria-label="title">&nbsp;</div>
				</div>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<!-- The method Playlist.displayPlaylist() uses this unordered list -->
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
	<div id="lrcInfoList" class="jp-video-270p">
	</div>
</div>
			<p style="margin-top:1em;">
				Miaow audio: <code>add( <a href="javascript:;" id="playlist-add-bubble">添加新歌曲</a></code><br />
				<code><a href="javascript:;" id="playlist-remove">清空列表</a></code>

				<code><a href="javascript:;" id="playlist-next">next</a>()</code> | <code><a href="javascript:;" id="playlist-previous">previous</a>()</code><br />

				<code><a href="javascript:;" id="playlist-play">播放</a></code>
				| <code><a href="javascript:;" id="playlist-pause">暂停</a>()</code><br />

				<code>option( "autoPlay", <a href="javascript:;" id="playlist-option-autoPlay-false">false</a> | <a href="javascript:;" id="playlist-option-autoPlay-true">true</a> )</code> Default: false<br />

			</p>
			<p>
				Equivalent Effect: <code><a href="javascript:;" id="playlist-equivalent-1-a">添加并立即播放：add(Your Face, true)</a></code>
			</p>
</body>

</html>
