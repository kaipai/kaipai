<!DOCTYPE html>
<html lang="en" ng-controller='baseController'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title ng-bind="globalTit"></title>
    <meta name="keywords" content="开拍网拍卖会,在线竞拍,在线拍卖,网上拍卖,古玩在线拍卖,艺术品拍卖,中国开拍网在线竞拍" />
    <meta name="description" content="中国开拍网拍卖会是中国最大的专业艺术品在线竞拍网站,为用户提供一个在线艺术品拍卖、古玩拍卖、古董拍卖、钱币拍卖、玉器拍卖、紫砂拍卖、书画拍卖、瓷器拍卖等藏品的拍卖网站,用户可以在线竞拍最新藏品,最新艺术品。" />

    <link rel="stylesheet" href="/plugins/layer/skin/layer.css">
    <link rel="stylesheet" href="/dist/styles/common/common.css">
    <link rel="stylesheet" href="/dist/styles/main/personal.css">

    </head>
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
                    <a class="nameA" ui-sref="indexPersonal"  ng-bind="nickName"></a>
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
               <!-- <a href="#">商家中心</a> -->
         
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
        <a ui-sref="goodList" >我的开拍</a>
        |
        <a ui-sref="goodList" >站内提醒</a>
      
        <a ui-sref="goodList" >帐户设置</a>
        
        <a ui-sref="goodList" >个人空间</a>
     
    </div>
</div>
</div>
<!--End头部-->