<?php require 'inc/lib.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head><title>latest video</title>

</head>
<body>
<p id="player1"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</p>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">
    var so = new SWFObject('flvplayer.swf','single','500','400','7');
    so.addParam('allowfullscreen','true');
    so.addParam('allowscriptaccess','always');
    so.addVariable('file','video/Activities.flv');
    so.addVariable('height','400');
    so.addVariable('width','500');
    so.write('player1');
</script>
</body>
</html>