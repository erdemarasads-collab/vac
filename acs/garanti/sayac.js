var second = 0;  
var minute = 2;   

function countDown() {
  second--;
  if (second == -01) {
    second = 59;
    minute = minute - 1;
  }
if (second<=9) { second = "0" + second; }
  time = (minute <=9 ? "0" + minute : minute) + ":" + second ;
if (document.getElementById){ 
	document.getElementById('count').innerHTML = time; 
	}
  SD=window.setTimeout("countDown();", 1000);
if (minute == '00' && second == '00') { second = "00"; window.clearTimeout(SD); }
}

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  countDown();
});
