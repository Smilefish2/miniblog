<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>

    <script language="javascript" src="<?php echo base_url('/static/xheditor/jquery/jquery-1.4.4.min.js');?>"></script>
	<script language="javascript" src="<?php echo base_url('/static/xheditor/xheditor-1.2.1.min.js');?>"></script>
	<script language="javascript" src="<?php echo base_url('/static/xheditor/xheditor_lang/zh-cn.js');?>"></script>
	<script language="javascript">
		$(pageInit);
		function pageInit(){
			$('#elm1').xheditor({urlType:'abs',internalScript:true,inlineScript:true,emotPath:'<?php echo base_url('themes/admin/xheditor/xheditor_emot/');?>',html5Upload:false,upImgUrl:"<?php echo base_url(config_item('admin_url') . '/upload');?>",upImgExt:"jpg,jpeg,gif,png",onUpload:insertUpload});
		}
		function insertUpload(arrMsg){
			var i,msg;
			for(i=0;i<arrMsg.length;i++){
				msg=arrMsg[i];
				$("#uploadList").append('<option value="'+msg.id+'">'+msg.localname+'</option>');
			}
		}
	</script>
    <textarea id="elm1" name="content" style="width: 860px; height: 250px; display: none; "></textarea>
</body>
</html>