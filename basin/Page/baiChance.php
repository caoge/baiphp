<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>BaiChance - 好运当头</title>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js"></script>
<style>
body {
	background-color: #cccccc;
}
.chance-head {
	position: fixed;
	z-index: 2;
	top: 10px;
	margin: 0;
	width: 100%;
	height: 30px;
	line-height: 30px;
	font-size: 20px;
	font-weight: bold;
	text-align: center;
	box-shadow: 0 0 6px #ffffff inset;
}
.chance-pick {
	position: fixed;
	z-index: 2;
	top: 40px;
	margin-right: 50%;
	right: 450px;
	padding: 6px;
	padding-left: 20px;
	min-width: 60px;
	min-height: 150px;
	color: #ffffff;
	overflow-y: hidden;
	box-shadow: 0 0 6px #f0f9f0 inset;
}
.chance-count {
	height: 2px;
	background: #ccffcc;
	width: 5px
}
.chance-control {
	position: fixed;
	z-index: 2;
	top: 40px;
	margin-left: 50%;
	left: 450px;
	padding: 6px;
	min-width: 60px;
	min-height: 150px;
	color: #ffffff;
	overflow-y: hidden;
	box-shadow: 0 0 6px #f9f0f0 inset;
}
.chance-pick>li, .chance-pick>div, .chance-control>li {
	height: 20px;
}
.chance-list {
	width: 800px;
	margin: 0 auto;
	padding: 40px;
	overflow-y: hidden;
	list-style-type: none;
}
.chance-list li {
	position: relative;
	z-index: 0;
	margin: 5px;
	padding: 0;
	float: left;
	width: 150px;
	height: 120px;
	line-height: 120px;
	text-align: center;
	font-size: 20px;
	cursor: pointer;
	opacity: 0.7;
	border-radius: 6px;
	box-shadow: 0 0 6px #ffffff inset;
}
.chance-list li.on {
	z-index: 1;
	opacity: 1;
	font-size: 40px;
	font-weight: bold;
	box-shadow: 0 0 30px 20px #ffffff, 0 5px 10px #ffffff inset;
}
.chance-list li.error {
	z-index: 1;
	opacity: 1;
	font-weight: bold;
	box-shadow: 0 0 30px 20px #cccccc inset, 0 5px 10px #ffffff inset;
}
</style>
<script type="text/javascript">
	var steps = 0;
	var pickList = {};
	var order = [];
	function aRgb() {
		var rgb = [ Math.floor(150 + Math.random() * 100),
				Math.floor(150 + Math.random() * 100),
				Math.floor(150 + Math.random() * 100) ];
		return 'rgb(' + rgb.join() + ')';
	}
	function aChoose() {
		var chanceList = $('.chance-list');
		if (chanceList.attr('on')) {
			return;
		}
		var mode = chanceList.attr('mode');
		var status = $(this).hasClass('on');
		if (mode == 'single') {
			chanceList.children('.on').removeClass('on');
			if (!status) {
				$(this).addClass('on');
				aPick(this.innerText);
			} else {
                $(this).css('background-color', aRgb());
			}
		} else if (mode == 'multi') {
			if (status) {
				$(this).removeClass('on');
                $(this).css('background-color', aRgb());
			} else {
				var max = Number($('input[name=max]').val());
				if (chanceList.children('.on').length >= max) {
					aError.call(this);
					return;
				}
				$(this).addClass('on');
				aPick(this.innerText);
			}
		} else if (mode == 'order') {
			if (!order.length) {
				order = $('input[name=order]').val().split('');
			}
			if (!order.length) {
				aError.call(this);
				return;
			}
			for ( var i = 0; i < order.length && order[i] == '*'; i++) {
			}
			;
			if (order[i] != this.innerText) {
				aError.call(this);
				return;
			}
			order[i] = '*';
			$(this).addClass('on');
			$(this).animate({
				'opacity' : 0.4,
			}, 600, function() {
				$(this).removeClass('on');
				$(this).css('opacity', '');
				$(this).css('background-color', aRgb());
			});
			aPick(this.innerText);
		} else if (mode == 'all') {
			if (status) {
				$(this).removeClass('on');
                $(this).css('background-color', aRgb());
			} else {
				$(this).addClass('on');
				aPick(this.innerText);
			}
		} else {
			aError.call(this);
		}
	}
	function aError() {
		$(this).addClass('error');
		$(this).animate({
			'opacity' : 0.4,
		}, 300, function() {
			$(this).removeClass('error');
			$(this).css('opacity', '');
		});
	}
	function aChance(index) {
		var chanceList = $('.chance-list');
		var chance = Math.floor(Math.random() * chanceList.children().length);
		if (!isNaN(index)) {
			chance = index;
		}
		var aItem = chanceList.children(':eq(' + chance + ')');
		aItem.addClass('on');
		aItem.animate({
			'opacity' : 0.4,
			'font-size' : '10px'
		}, 600, function() {
			if ($('.chance-list li.on').length > 1) {
				this.className = '';
			}
			$(this).css('opacity', '');
			$(this).css('font-size', '');
			$(this).css('background-color', aRgb());
		});
		if (steps-- > 0) {
			setTimeout('aChance();', 200 + Math.floor(Math.random() * 200));
		} else {
			aPick(aItem.text());
			chanceList.attr('on', '');
		}
	}
	function aPick(item) {
		var chancePick = $('.chance-pick');
		if (pickList[item]) {
			pickList[item]++;
			chancePick.children('#' + item).attr('title', pickList[item]);
			$('#' + item + ' .chance-count', chancePick).css('width',
					pickList[item] * 5);
		} else {
			pickList[item] = 1;
			chancePick.append('<li id="' + item + '" title="1">' + item
					+ '<div class="chance-count"></div></li>');
		}
	}
	function aTry() {
		var chanceList = $('.chance-list');
		if (chanceList.attr('on') || chanceList.attr('mode') != 'random') {
			return;
		}
		chanceList.attr('on', 1);
		chanceList.children('li.on').removeClass('on');
		steps = $('.chance-list li').length;
		steps += Math.floor(Math.random() * steps);
		aChance();
	}
	$(function() {
		var options = [ 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
				'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
				'X', 'Y', 'Z' ];
		var chanceList = $('.chance-list');
		chanceList.attr('mode', 'random');
		for (var i = 0; i < options.length; i++) {
			chanceList.append('<li>' + options[i] + '</li>');
		}
		chanceList.children('li').each(function(i) {
			this.style.backgroundColor = aRgb();
		});
		chanceList.children('li').click(aChoose);
		$('input[name=mode]').change(function() {
			chanceList.attr('mode', this.value);
			chanceList.children('li.on').removeClass('on');
			$('.chance-pick li').remove();
			pickList = {};
			order = [];
		});
		var order = [];
		for (var i = 0; i < 9; i++) {
			order[i] = String.fromCharCode(65 + Math.floor(Math.random() * 26));
		}
		$('input[name=order]').val(order.join(''));
	});
</script>
</head>
<body>
	<div class="chance-head">
		<div>BaiChance - 好运当头</div>
	</div>
	<ul class="chance-pick">
		<div>手气榜</div>
	</ul>
	<ul class="chance-control">
		<li><input name="mode" type="radio" value="random" checked />随机</li>
		<li><button onclick="aTry();">试试手气</button></li>
		<li><input name="mode" type="radio" value="single" />单选</li>
		<li><input name="mode" type="radio" value="multi" />多选<input name="max" type="text" value="4" size="2" />项</li>
		<li><input name="mode" type="radio" value="order" />顺序</li>
		<li><input name="order" type="text" value="BAICHANCE" readonly size="10"></input></li>
		<li><input name="mode" type="radio" value="all" />任意</li>
	</ul>
	<ul class="chance-list">
	</ul>
</body>
</html>
