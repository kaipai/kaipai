<!DOCTYPE html>
<html lang="en" ng-controller='baseController'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title ng-bind="globalTit"></title>
    <meta name="keywords" content="开拍网拍卖会,在线竞拍,在线拍卖,网上拍卖,古玩在线拍卖,艺术品拍卖,中国开拍网在线竞拍" />
    <meta name="description" content="中国开拍网拍卖会是中国最大的专业艺术品在线竞拍网站,为用户提供一个在线艺术品拍卖、古玩拍卖、古董拍卖、钱币拍卖、玉器拍卖、紫砂拍卖、书画拍卖、瓷器拍卖等藏品的拍卖网站,用户可以在线竞拍最新藏品,最新艺术品。" />
    <link rel="stylesheet" href="/plugins/layer/skin/layer.css">
    <link rel="stylesheet" href="/plugins/jquery-ui/jquery.ui.datepicker.css">
    <link rel="stylesheet" href="/dist/styles/common/common.css">
    <link rel="stylesheet" type="text/css" href="/dist/styles/main/account.css">

<body >

    <div class="toper">
        <div class="center">
            <div class="mlinknav">
                <a href="/">开拍网首页</a>
            </div>
            <div class="uinfonav"  ng-if="hasLogin">
                <div id="logorout">
                    <span>您好!</span>
                    <div class="user_name">
                        <a class="nameA" ui-sref="indexPersonal"  >用户名</a>
                        <div id="toperNav">
                            <a ui-sref="listOrderPersonal" >我的订单</a>
                            <a ui-sref="collectionAuctionlistPersonal" >我的收藏</a>
                            <a  ui-sref="mybalanceCustomerPersonal" >我的余额</a>
                            <a ui-sref="feedbackListPersonal" >意见反馈</a>
                            <a href="javascript:" ng-click="loginOut()">安全退出</a>
                        </div>
                    </div>

                </div>

                <a class="footprint" href="">收藏本站</a>

            </div>

            <div class="uinfonav" style="display: none;">
                <span id="logorout" style="font-size:14px">
                    <a class="register" rel="nofollow" ui-sref="userRegister">免费注册</a>
                    <a target="_self" rel="nofollow" ui-sref="userLogin">用户登录</a>
                    <!-- <a href="#">商家中心</a>
                -->
            </span>
            |
            <a class="footprint" href="">收藏本站</a>
        </div>

    </div>
</div>
<div class="header">
    <div class="center">
        <a href="#" class="logonav">
            <img src="/dist/img/common/logo.png" />
        </a>
        <div class="searchnav">
            <form style=" height:35px;" action="/paimai/search/all/">
                <input type="text" class="ipt" name="t" id="txtKw" value="输入关键词/名称" />
                <a href="javascript:;" class="btn paimaisearchbtn">搜索</a>
            </form>
            <div class="hotlink">
                <a href="#" >和田玉</a>
                <a href="#" >和田玉</a>
                <a href="#" >和田玉</a>
                <a href="#" >和田玉</a>
                <a href="#" >和田玉</a>
            </div>
        </div>
    </div>
</div>
<div class="menuer">
    <div class="center">
        <div class="mlist">
            <a href="/" >首页</a>
            <a ui-sref="goodList" >所有自由拍</a>
            |
            <a ui-sref="goodList" >国画</a>
            ·
            <a ui-sref="goodList" >书法</a>
            ·
            <a ui-sref="goodList" >金石篆刻</a>
            ·
            <a ui-sref="goodList" >古玩杂项</a>
            ·
            <a ui-sref="goodList" >古典陶瓷</a>
            ·
            <a ui-sref="goodList" >宗教文化</a>
            ·
            <a ui-sref="goodList" >西画雕塑</a>
            ·
            <a ui-sref="goodList" >奢侈品</a>
            ·
            <a ui-sref="goodList" >宝石翡翠</a>
        </div>
    </div>
</div>
<!--End头部-->



    <div class="logcont">

        <div id="masker"></div>
        <div id="agreement-content" class="comm-jump-column">
            <div class="title">
                <h3>中国艺术网用户注册协议</h3>
                <span class="close">╳</span>
            </div>
            <div class="jump-content"></div>
            <div class="red-btn close">同意并继续</div>
        </div>

        <div id="reg_begin" class="findPassword reg-bodybg" style=" display:block;">
            <div class="w1000 clearfix">
                <form id="reg_beginForm" method="post" novalidate="novalidate">
                    <div class="xtj-cloum">
                        <p>
                            <span>昵称：</span>
                            <div class="inputBox"> <i class="name"></i> <em class="name"></em>
                                <input autocomplete="off" id="Nick" name="Nick" placeholder="输入昵称" type="text" value="" />    
                            </div>
                        </p>
                    </div>
                    <div class="xtj-cloum">
                        <p>
                            <span>手机号码：</span>
                            <div class="inputBox"> <i class="phone"></i> <em class="phone"></em>
                                <input autocomplete="off" ng-model="curObj.mobile" name="UserName" placeholder="手机号码,港澳台号码请加上区号" type="text" value="" />    
                            </div>
                        </p>
                    </div>
                    <div class="xtj-cloum">
                        <p>
                            <span>验证码：</span>
                            <div class="inputBox" style=" border: none;">
                                <input autocomplete="off"  name="ImageValidateCode" placeholder="请输入验证码" style="width: 142px; border:solid 1px #ddd; margin-right:5px;" type="text" ng-model="curObj.picVerifyCode" />    
                                <img alt="点击切换"  style="width: 100px; height:32px; cursor:pointer; float:left; border:solid 1px #ddd;" onclick="this.src='http://114.55.36.109/api/v1/login/get-pic-verify-code?rnd=' + Math.random();"  src="http://114.55.36.109/api/v1/login/get-pic-verify-code"></div>
                        </p>
                    </div>
                    <div class="xtj-cloum">
                <p id="mcode">
                    <span>短信验证码：</span>

                    <input autocomplete="off" class="input" id="ValidateCode" name="ValidateCode" placeholder="输入验证码" style="margin-left:-4px" type="text" value="" />
                    <a class="btn_getcode" id="btn_getcode" ng-click="checkSendFun()"  href="javascript:;" >发送验证码</a>
                </p>
            </div>

                    <div class="xtj-cloum">
                        <p>
                            <span>登录密码：</span>
                            <div class="inputBox">
                                <i class="key"></i>
                                <em class="key"></em>
                                <input autocomplete="off" id="Password" name="Password" placeholder="输入密码" type="password" />    
                            </div>
                        </p>
                    </div>
                    <div class="xtj-cloum">
                        <p class="small">
                            <span>&nbsp;</span>
                            <label>
                                <input id="check" type="checkbox">    
                                我已经阅读并同意
                                <a href="javascript:;">《开拍网用户服务条款》</a>
                                
                            </label>
                        </p>
                    </div>
                    <div class="xtj-cloum">
                        <p>
                            <span>&nbsp;</span>
                            <input type="button" id="regeter" class="submit" value="立即注册"></p>
                    </div>
                    <div class="xtj-cloum">
                        <p style="line-height:40px">
                            <span>&nbsp;</span>
                            已有账号？
                            <a class="blue" ui-sref="userLogin">立即登录>></a>
                        </p>
                    </div>
                   
                </form>
                  
            </div>
        </div>
        <div id="reg_success" class="findPassword reg-bodybg" style="display:none;">
            <div class="w1000 clearfix">
                <!-- 注册成功start -->    
                <div class="registerSuc">
                    <div class="sucImg">
                        <img src="/dist/img/account/success.png" />    
                    </div>
                    <div class="sucFont">恭喜您注册成功！</div>
                    <div class="sucTip">
                        <p>
                            100元现金券24小时内到帐
                            <a href="/personal/assets/mybalance/">我的资产</a>
                            ，每天上万件1元起拍的
                        </p>
                        <p>
                            商品等着你&nbsp;&nbsp;&nbsp;
                            <a href="http://www.yishu.com/paimai/" target="_blank">去逛逛>></a>
                        </p>
                    </div>
                    <div class="QR">
                        <div class="qrCode">
                            <img src="/dist/img/common/QRcode.jpg" />    
                        </div>
                        <div class="qrFont">
                            <p>【艺术网服务号】</p>
                            <p>关注微信服务号，获取实时出价通知，中意宝贝不容错过～</p>
                        </div>
                    </div>
                </div>
                <!-- 注册成功end --> 
                </div>
        </div>
        
    </div>
 

<?php include'footer.php';?></body>
</html>