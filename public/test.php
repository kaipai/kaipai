<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>通用</title>
    <link rel="stylesheet" href="/dist/styles/common/common.css">
    <link rel="stylesheet" href="/dist/styles/main/index.css">
    <link rel="stylesheet" href="style/css1.css"></head>
<body>

<div class="toper">
    <div class="center">
        <div class="mlinknav">
            <a ui-sref="main">开拍网首页</a>
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

        <div class="uinfonav" ng-if="!hasLogin">
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
            <img src="images/logo.png" />
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
            <a ui-sref="main" >首页</a>
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

<!--广告 start-->
<div class="center">
    <div class="main-ad-box">

        <div class="mainad left">

            <div class="slideBox">
                <div class="hd">
                    <ul>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>2</li>
                        <li>3</li>
                    </ul>
                </div>
                <div class="bd">
                    <ul>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad1.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad2.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad1.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad2.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad3.jpg" />
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                <a class="prev" href="javascript:void(0)"></a>
                <a class="next" href="javascript:void(0)"></a>

            </div>

        </div>
        <div class="mainad right">

            <div class="slideBox">
                <div class="hd">
                    <ul>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>2</li>
                        <li>3</li>
                    </ul>
                </div>
                <div class="bd">
                    <ul>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad3.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad1.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad1.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad2.jpg" />
                            </a>
                        </li>
                        <li>
                            <a href="/" target="_blank">
                                <img src="/dist/img/upload/ad2.jpg" />
                            </a>
                        </li>
                    </ul>
                </div>

                <a class="prev" href="javascript:void(0)"></a>
                <a class="next" href="javascript:void(0)"></a>

            </div>
        </div>
    </div>
</div>
<!--广告 end-->
<!--main开始-->
<div class="kp_main">
    <div class="center " >

        <!--分类-->
        <div>
            <div class="fl_liebie">分类拍品>国画</div>
            <div class="fl_shaixuan">
                <div class="fl_shaixuan_left">筛选></div>
                <div class="fl_shaixuan_right">
                    <ul>
                        <li class="fl_tc">
                            <a href=""></a>
                            题 材
                            </a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">山水</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">人物</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">花鸟</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">草虫</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">走兽</a>
                        </li>
                    </ul>
                    <ul>
                        <li class="fl_tc">
                            <a href=""></a>
                            风 格
                            </a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">写意</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">工笔</a>
                        </li>
                    </ul>
                    <ul>
                        <li class="fl_tc">
                            <a href="">作品年代</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">当代</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">近现代</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">清代（1840以前）</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">明代及以前</a>
                        </li>
                    </ul>
                    <ul>
                        <li class="fl_tc">
                            <a href=""></a>
                            高度
                            </a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">30-33cm</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">60-69cm</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">133-138cm</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">其他尺寸</a>
                        </li>
                    </ul>
                    <ul>
                        <li class="fl_tc">
                            <a href=""></a>
                            形式
                            </a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">立轴</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">镜片</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">镜心</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">成扇</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">手卷</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">册页</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">中堂</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">斗方</a>
                        </li>
                        <li class="fl_li_other">
                            <a href="">横批、条幅</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="fl_zhuangtai">
                <ul>
                    <li>状态></li>
                    <li >
                        <a href="" >全部</a>
                    </li>
                    <li>
                        <a href="">正在进行</a>
                    </li>
                    <li>
                        <a href="">即将开始</a>
                    </li>
                </ul>
            </div>
            <div class="sf_jiage">
                价格区间
                <input class="mt_input" type="text" placeholder="">
                —
                <input class="mt_input" type="text" placeholder="">
<span class="sf_btn">
  <a href="">确定</a>
</span>
                <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">
                有文物拍卖资质
                <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">
                包邮
                <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">
                3天包退
                <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">
                鉴定证书
                <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">作者授权</div>
            <div class="sf_paixu">
                <ul>
                    <li>
                        <a href="">默认</a>
                    </li>
                    <li>
                        <a href="">
                            街拍时间
                            <img src="images/ss_03.jpg"></a>
                    </li>
                    <li>
                        <a href="">
                            价格
                            <img src="images/ss_03.jpg"></a>
                    </li>
                    <li>
                        <a href="">关注度</a>
                    </li>

                    <li class="tpli">
                        <a href="">
                            显示图片
                            <img src="images/ss_05.jpg"></a>
                    </li>
                    <li>
                        <a href="">
                            列表显示
                            <img src="images/ss_07.jpg"></a>
                    </li>
                    <li>
                        <a href="">合并店铺</a>
                    </li>
                    <li class="ym ym1">页码</li>
                    <li class="ym">
                        <a href="">
                            <img src="images/a_03.jpg"></a>
                    </li>
                    <li class="ym">1/23</li>
                    <li class="ym">
                        <a href="">
                            <img src="images/a_05.jpg"></a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="center mt20">
        <div class="index-box clearfix">
            <div class="indexBox-left fl">
                <dl class="proTextList">
                    <dt>
                        <span class="item1">类别</span>
                        <span class="item2">拍品</span>
                        <span class="item3">当前价</span>
                        <span class="item4">拍卖时间</span>
                    </dt>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>
                    <dd>
                        <span class="item1">类别</span>
                        <span class="item2">拍品名称名称拍品名称名称拍品名称名称拍品名称名称</span>
                        <span class="item3">￥159</span>
                        <span class="item4">距结束：<em>24小时27分14粉</em></span>
                    </dd>


                </dl>
            </div>
            <div class="indexBox-right fr">
                <h2 class="leftBox-tit">店铺一言坊</h2>
                <ul class="indexBox-right-list">
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                    <li>
                        <h3>艺珍阁  ></h3>
                        <p class="indexBox-right-info">青年实力名家、国展获奖专业户徐右冰最新精品授权展销。</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="botNav">
    <div class="center">
        <div class="botnav-item botnav-item1">
            <h4>
                <span>诚实信用</span> <em>对拍品的如实描述，卖家的承诺服务</em>
            </h4>
            <dl>
                <dt>竞买者保障</dt>
                <dd>
                    <a href="#">·关于拍品</a>
                </dd>
                <dd>
                    <a href="#">·关于拍卖</a>
                </dd>
                <dd>
                    <a href="#">·关于售后</a>
                </dd>
                <dd>
                    <a href="#">·其它保障</a>
                </dd>

            </dl>
        </div>
        <div class="erweima fl">
            <p>开拍网公众号</p>
            <span>“开拍网”</span>
        </div>
        <div class="botnav-item botnav-item2">
            <h4>
                <span>双向交流</span> <em>专业服务，阳光竞拍</em>
            </h4>
            <dl>
                <dt>个人 | 商家入驻</dt>
                <dd>
                    <a href="#">·入驻规则</a>
                </dd>
                <dd>
                    <a href="#">·保证金问题</a>
                </dd>
                <dd>
                    <a href="#">·诚信问题</a>
                </dd>
                <dd>
                    <a href="#">·其它</a>
                </dd>

            </dl>
        </div>
        <div class="botnav-item botnav-item3">
            <h4>
                <span>售后无忧</span>
                <em>明白承诺，周到服务</em>
            </h4>
            <dl>
                <dt>关于我们</dt>
                <dd>
                    <a href="#">·免责声明</a>
                </dd>
                <dd>
                    <a href="#">·版权声明</a>
                </dd>
                <dd>
                    <a href="#">·联系我们</a>
                </dd>
                <dd>
                    <a href="#">·关于开拍</a>
                </dd>

            </dl>
        </div>
    </div>
</div>
<div class="footer">
    <div class="footernav center">
        <a href="#">首页</a>
        <em>|</em>
        <a href="#">国画</a>
        <em>|</em>
        <a href="#">书法</a>
        <em>|</em>
        <a href="#">金石篆刻</a>
        <em>|</em>
        <a href="#">古玩杂项</a>
        <em>|</em>
        <a href="#">古典陶瓷</a>
        <em>|</em>
        <a href="#">西画雕塑</a>
        <em>|</em>
        <a href="#">宗教文化</a>
        <em>|</em>
        <a href="#">奢侈品</a>
        <em>|</em>
        <a href="#">宝石翡翠</a>
        <em>|</em>
        <a href="#">联系我们</a>
        <p>
            公司地址: 浙江省杭州市下城区朝晖路179号嘉汇大厦2号楼5层  电话: 0571-85819321 18867538398   邮箱: kaipai123@sina.com
            <br>
            版权所有 © 杭州易开拍网络科技有限公司  浙ICP备13004819号-1   网址: www.kaipai123.com
        </p>
    </div>
</div>
<script src="/plugins/jquery/jquery.js"></script>
<script src="/plugins/superSlide/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript">
    $(function(){
        $(".slideBox").slide({
            mainCell: ".bd ul",
            autoPlay: true,
            effect: "left",
            delayTime: 700
        });
    })
</script>
</body>
</html>