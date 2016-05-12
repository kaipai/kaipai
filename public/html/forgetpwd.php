<?php include'head.php';?>
<link rel="stylesheet" type="text/css" href="/dist/styles/main/account.css">

<div class="logcont">
    <div class="w1000 clearfix">
        <div class="findPassword">
            <h2>找回密码</h2>
            <form id="for_beginForm" method="post" >
                <div class="xtj-cloum">
                    <p>
                        <span> <b>*</b>
                            手机号码：
                        </span>
                        <div class="inputBox">
                            <input  name="UserName" ng-model="curObj.mobile" placeholder="请输入手机号码" type="text" />
                        </div>
                    </p>
                </div>
                <div class="xtj-cloum">
                    <p>
                        <span> <b>*</b>
                            验证码：
                        </span>
                        <div class="inputBox" style=" border: none;">
                            <input ng-model="curObj.ValidCode"  name="ImageValidateCode" placeholder="请输入验证码" style="width: 140px; border:solid 1px #ddd; margin-right:5px;" type="text" value="" />
                            <img alt="点击切换" onclick="this.src='http://114.55.36.109/api/v1/login/get-pic-verify-code?rnd=' + Math.random();"  style="width: 100px; height:32px; cursor:pointer; float:left; border:solid 1px #ddd;" src="http://114.55.36.109/api/v1/login/get-pic-verify-code"></div>
                    </p>
                </div>
                <div class="xtj-cloum">
                    <p>
                        <span>
                            <b>*</b>
                            短信验证码：
                        </span>

                        <input  class="input"   placeholder="输入验证码" style="margin-left:-4px" type="text" ng-model="curObj.ValidDataCode"/>
                        <a class="btn_getcode" id="btn_getcode" ng-click="checkSendFun()"  href="javascript:;" >发送验证码</a>

                    </p>
                </div>
                <div class="xtj-cloum">
                    <p>
                        <span>&nbsp;</span>
                        <input ng-click='submitFun()' type="button" class="submit" value="验证">
                        <a class="back" ui-sref="userLogin">返回登录>></a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include'footer.php';?></body>
</html>