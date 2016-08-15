<?php include'head.php';?>
<link rel="stylesheet" type="text/css" href="/dist/styles/main/account.css">


    <div class="logcont">

        <div class="w1000 clearfix">
            <div class="leftImg fl">
                <img src="/dist/img/account/logimg.jpg">


            </div>
            <div class="login fr">
                <h2>账户登录</h2>
                <form  method="post">
                    <span style="color: red;" >
                        
                    </span>
                    <div class="inputBox"> <i class="phone"></i>
                        <input name="mobile" ng-model="curObj.mobile" placeholder="手机号码" type="text" value="" />    
                    </div>
                    
                    <div class="inputBox"> <i class="key"></i>
                        <input name="Password" ng-model="curObj.password" placeholder="输入密码" type="password" />    
                    </div>
                    
                    <p>
                        <label>
                            <input data-val="true"  name="RememberMe" type="checkbox" value="true" />    
                           
                            下次自动登录
                        </label>
                        <a ui-sref="userForgetpwd">忘记密码？</a>
                    </p>
                    <input id="loginForm" type="submit" ng-click="doLogin()" class="submit" value="登录">   
                </form>
                <h5>
                    没有账号？
                    <a ui-sref="userRegister">免费注册>></a>
                </h5>
                <div class="xtj_hzzh">
                    <p>使用合作网站账号登录：</p>
                    <div>
                        <a class="i_01" href="/personal/account/qqlogin/">QQ</a>

                        <a class="i_03" href="/personal/account/alipaylogin/">微信</a>
                        <a class="i_04" style="border-right:none;" href="/personal/account/weibologin/">新浪微博</a>

                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

<?php include'footer.php';?></body>
</html>