<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首次登录-绑定个人信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="/CES/Public/css/bindStyle.css">
    <script src="/CES/Public/js/jquery-3.1.1.min.js" type="text/javascript"></script>
</head>
<script type="text/javascript">
    function bindUserInfo() {

        var openid = $('#openid').text();
        var stuName = $('#stu_name').val();
        var stuNum = $('#stu_num').val();
        var stuPro = $('#stu_pro').val();
        if(stuName==''){
            alert('姓名不能为空！');
            return;
        }else if(stuNum==''){
            alert('学号不能为空！');
            return;
        }else if(stuPro==''){
            alert('专业不能为空！');
            return;
        }

//        this.data = new Array();
//        this.put = function(key,value){
//            this.data[key] = value;
//        };
//        data.put('stu_name',stuName);
//        data.put('stu_num',stuNum);
//        data.put('stu_pro',stuPro);
//        data.put('openid',openid);

//        alert("----" + stuNum + "----" + stuPro);

        $.ajax({
            type: "POST", //用POST方式传输
            url: "<?php echo U('Home/UserManager/bindUserInfo');?>", //目标地址.
            dataType: "JSON", //数据格式:JSON
            data: {openid:openid,stu_name:stuName,stu_num:stuNum,stu_pro:stuPro},
            success: function (result) {
                if (result.status == 'success') {
                    alert(result.hint);
                } else {
                    alert(result.hint);
                }
            }
        });
    }
</script>
<body>
<div class="addopenid_container">
    <span id="openid" style="display:none"><?php echo ($openid); ?></span>
    <div class="lbox_close wxapi_form">
        <h3 class='title' id="title_first">首次使用请绑定您的个人信息</h3>
        <div class="input_text">
            <span class="star"><sup>*</sup></span>
            <span class="desc">姓名:</span>
            <input class="edt edt_primary" placeholder="请输入您的姓名" id="stu_name"/>
        </div>
        <div class="input_text">
            <span class="star"><sup>*</sup></span>
            <span class="desc">学号:</span>
            <input class="edt edt_primary" placeholder="请输入您的学号" id="stu_num"/>
        </div>
        <div class="input_text">
            <span class="star"><sup>*</sup></span>
            <span class="desc">专业:</span>
            <select class="edt edt_primary" style="width:190px" id="stu_pro">
                <option value="1">1 - 计算机科学与技术</option>
                <option value="2">2 - 信息安全</option>
                <option value="3">3 - 信息与计算科学</option>
                <option value="4">4 - 计算机科学与技术（中加方向）</option>
                <option value="5">5 - 网络工程</option>
                <option value="6">6 - 物联网工程</option>
                <option value="7">7 - 通信工程</option>
            </select>
            <!--<input class="edt edt_primary" placeholder="请输入您所在的专业编号" id="stu_pro"/>-->
            <span class="hint"><span style="margin-left:-10px;">注意事项：</span><br/>1 - 计算机科学与技术<br/>2 - 信息安全<br/>3 - 信息与计算科学<br/>4 - 计算机科学与技术（中加方向）<br/>5 - 网络工程<br/>6 - 物联网工程<br/>7 - 通信工程</span>
        </div>
        <button class="btn btn_primary" id="bt" onclick="bindUserInfo();">确认绑定</button>
    </div>
</div>
</body>
</html>