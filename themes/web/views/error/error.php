<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>错误：<?php echo $code; ?></title>
<style type="text/css">
body { background: #f7fbe9; font-family: "Lucida Grande", "Lucida Sans Unicode", Tahoma, Verdana; }
#error { background: #333; width: 600px; margin: 0 auto; margin-top: 100px; color: #fff; padding: 10px; -moz-border-radius-topleft: 4px; -moz-border-radius-topright: 4px; -moz-border-radius-bottomleft: 4px; -moz-border-radius-bottomright: 4px; -webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px; -webkit-border-bottom-left-radius: 4px; -webkit-border-bottom-right-radius: 4px; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; }
h1 { padding: 10px; margin: 0; font-size: 36px; }
p { padding: 0 20px 20px 20px; margin: 0; font-size: 12px; }
.message{padding: 0 20px 20px 10px; margin: 0; font-size: 14px;}
img { padding: 0 0 5px 260px; }
</style>
</head>
<body>
<div id="error">
  <h1><?php echo $code; ?></h1>
  <div class="message"><?php echo nl2br(CHtml::encode($message)); ?></div>
</div>
</body>
</html>