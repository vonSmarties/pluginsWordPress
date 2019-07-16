var cornify_count = 0;
var idol_time = 5000;
cornify_add = function() {
	cornify_count += 1;
	var cornify_url = 'http://www.cornify.com/';
	var div = document.createElement('div');
	div.style.position = 'fixed';
	var numType = 'px';
	var heightRandom = Math.random()*.75;
	var windowHeight = 768;
	var windowWidth = 1024;
	var height = 0;
	var width = 0;
	var de = document.documentElement;
	if (typeof(window.innerHeight) == 'number') {
		windowHeight = window.innerHeight;
		windowWidth = window.innerWidth;
	} else if(de && de.clientHeight) {
		windowHeight = de.clientHeight;
		windowWidth = de.clientWidth;
	} else {
		numType = '%';
		height = Math.round( height*100 )+'%';
	}

	div.onclick = cornify_add;
	div.style.zIndex = 10;
	div.style.outline = 0;
	div.className = "cornify";

	if( cornify_count==15 ) {
		div.style.top = Math.max( 0, Math.round( (windowHeight-530)/2 ) )  + 'px';
		div.style.left = Math.round( (windowWidth-530)/2 ) + 'px';
		div.style.zIndex = 1000;
	} else {
		if( numType=='px' ) div.style.top = Math.round( windowHeight*heightRandom ) + numType;
		else div.style.top = height;
		div.style.left = Math.round( Math.random()*90 ) + '%';
	}

	var img = document.createElement('img');
	var currentTime = new Date();
	var submitTime = currentTime.getTime();
	if( cornify_count==15 ) submitTime = 0;
	img.setAttribute('src',cornify_url+'getacorn.php?r=' + submitTime + '&url='+document.location.href);
	var ease = "all .1s linear";
	//div.style['-webkit-transition'] = ease;
	//div.style.webkitTransition = ease;
	div.style.WebkitTransition = ease;
	div.style.WebkitTransform = "rotate(1deg) scale(1.01,1.01)";
	//div.style.MozTransition = "all .1s linear";
	div.style.transition = "all .1s linear";
	div.onmouseover = function() {
		var size = 1+Math.round(Math.random()*10)/100;
		var angle = Math.round(Math.random()*20-10);
		var result = "rotate("+angle+"deg) scale("+size+","+size+")";
		this.style.transform = result;
		//this.style['-webkit-transform'] = result;
		//this.style.webkitTransform = result;
		this.style.WebkitTransform = result;
		//this.style.MozTransform = result;
		//alert(this + ' | ' + result);
	}
	div.onmouseout = function() {
		var size = .9+Math.round(Math.random()*10)/100;
		var angle = Math.round(Math.random()*6-3);
		var result = "rotate("+angle+"deg) scale("+size+","+size+")";
		this.style.transform = result;
		//this.style['-webkit-transform'] = result;
		//this.style.webkitTransform = result;
		this.style.WebkitTransform = result;
		//this.style.MozTransform = result;
	}
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(div);
	div.appendChild(img);

	// Add stylesheet.
	if (cornify_count == 5) {
		var cssExisting = document.getElementById('__cornify_css');
		if (!cssExisting) {
			var head = document.getElementsByTagName("head")[0];
			var css = document.createElement('link');
			css.id = '__cornify_css';
			css.type = 'text/css';
			css.rel = 'stylesheet';
			css.href = 'http://www.cornify.com/css/cornify.css';
			css.media = 'screen';
			head.appendChild(css);
		}
		cornify_replace();
	}
}

cornify_replace = function() {
	// Replace text.
	var hc = 6;
	var hs;
	var h;
	var k;
	var words = ['Happy','Sparkly','Glittery','Fun','Magical','Lovely','Cute','Charming','Amazing','Wonderful'];
	while(hc >= 1) {
		hs = document.getElementsByTagName('h' + hc);
		for (k = 0; k < hs.length; k++) {
			h = hs[k];
			h.innerHTML = '<span class ="cornify">' + words[Math.floor(Math.random()*words.length)] + '</span>' + ' ' + h.innerHTML ;
			//h.className = "cornify_text";

		}
		hc-=1;
	}
}

/*
 * Adapted from http://www.snaptortoise.com/konami-js/
 */
var cornami = {
	input:"",
	pattern:"38384040373937396665",
	clear:setTimeout('cornami.clear_input()',5000),
	load: function() {
		window.document.onkeydown = function(e) {
			if (cornami.input == cornami.pattern) {
				cornify_add();
				clearTimeout(cornami.clear);
				return;
			}
			else {
				cornami.input += e ? e.keyCode : event.keyCode;
				if (cornami.input == cornami.pattern) cornify_add();
				clearTimeout(cornami.clear);
				cornami.clear = setTimeout("cornami.clear_input()", 5000);
			}
		}
	},
	clear_input: function() {
		cornami.input="";
		clearTimeout(cornami.clear);
	}
}
cornami.load();


/*
 * from http://www.javascriptkit.com/javatutors/loadjavascriptcss2.shtml
 */

function removejscssfile(filename, filetype){
 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
 var allsuspects=document.getElementsByTagName(targetelement)
 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
   allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
 }
}


/*
 * from https://github.com/jasonmcleod/jquery.idle
 */


(function ($) {
	$.fn.idle = function (onidle, onactive, options) {
		return this.each(function () {
			var isidle   = false,
			    hasMoved = false,
			    lastMove = (new Date()).getTime(),
			    opts;

			if ($.isPlainObject(onactive)) {
				options = onactive;
			}

			if (!$.isFunction(onactive)) {
				onactive = $.noop;
			}

			opts = $.extend({}, $.fn.idle.defaults, options);

			$(this).bind("mousemove", function () {
				hasMoved = true;
				lastMove = (new Date()).getTime();
				if (isidle) {
					onactive.call(this);
					isidle = false;
				}
			});

			window.setInterval(function () {
				if ((new Date()).getTime() - lastMove > opts.after) {
					if (hasMoved) {
						onidle.call(this);
					}
					lastMove = (new Date()).getTime();
					isidle = true;
				}
			}, opts.interval);
		});
	};

	// Set outside so they can be overrided
	// globally before being called on
	// an item
	$.fn.idle.defaults = {
		after: 5000,
		interval: 100
	};
}(jQuery));


	jQuery(function($){
  $("body").idle(
    function() {
      // When idle
      	   	cornify_add();
      		if(cornify_count == 5){
      		var cssExisting = document.getElementById('__cornify_css');
			if (!cssExisting) {
				cornify_replace();
			}
		}
     },
    function() {
      // When active again
      $('.cornify').remove();
      removejscssfile("http://www.cornify.com/css/cornify.css", "css")
      cornify_count = 0;

    },
    { after: idol_time }
  );
});
