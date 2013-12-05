<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>管理登录 - <?php echo config_item('site_name');?></title>
<style type="text/css">
* { font-family:"Microsoft YaHei",Arial; }
body { background:#f9f9f9; font-size:14px; }
#login_title { text-align:center; width:360px; margin:120px auto; margin-bottom:0px; font-size:32px; color:#333;  text-shadow: 0 2px 0 #FFFFFF;}
#login_form { width:360px; margin:0 auto; margin-top:20px; border:solid 1px #e0e0e0; background:#fff; border-radius:3px 3px 3px 3px;}
#login_form_box { padding:16px; }
#login_form .label { font-weight:bold; padding-bottom:6px; color:#333; }
#login_form .textbox input { border:none; padding:0; font-size:24px; width:312px; color:#333; }
#login_form .textbox { border:1px solid #e0e0e0; padding:6px; margin-bottom:20px; border-radius:3px 3px 3px 3px; }
#login_form .bottom { text-align:right; }
#login_form .button { padding:8px 16px; font-size:14px; }
</style>
</head>
<body>
<form action="" method="post">
  <div id="login_title">登录后台</div>
  <div id="login_form">
    <div id="login_form_box">
      <div class="label">帐号</div>
      <div class="textbox"><input name="user" type="text" /></div>
      <div class="label">密码</div>
      <div class="textbox"><input name="pass" type="password" /></div>
      <div class="bottom"><input name="login" type="submit" value="登录" class="button" /></div>
    </div>
  </div>
</form>
</body>
</html>