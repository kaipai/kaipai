<?php include'headPerson.php';?>
<!--main开始-->
<div class="kp_main">
  <div class="center " >
    <!-- left部分 -->
    <div class="kp_left">
      <div class="kp_biaot">
        <p class="kp_p">我是买家</p>
        <ul class="kp_ul mt10" id="kpdh1">
          <a href="">
            <li>正在竞拍</li>
          </a>
          <a href="">
            <li>已购拍品</li>
          </a>
          <a href="">
            <li>关注拍品</li>
          </a>
          <a href="">
            <li>关注商家</li>
          </a>
          <a href="">
            <li>买家须知</li>
          </a>
        </ul>
      </div>
      <div class="kp_biaot">
        <p class="kp_p kpdh2" >我是商家</p>
        <ul class="kp_ul mt10">
          <a href="fbPaiPin.html">
            <li class="kp_checked">发布拍品</li>
          </a>
          <a href="ppChangKu.html">
            <li >拍品仓库</li>
          </a>
          <a href="glzhuanchang.html">
            <li>管理专场</li>
          </a>
          <a href="ysPaiPin.html">
            <li >已售拍品</li>
          </a>
          <a href="sjSheZhi.html">
            <li >商家设置</li>
          </a>
          <a href="index.html">
            <li>认证流程</li>
          </a>
        </ul>
      </div>
    </div>
    <!-- right部分 -->
    <div class="kp_right">
      <div  class="kp_tit">
        <ul>
          <li>上架</li>
          <li>下架</li>
          <li>推荐</li>
          <li>设置拍卖时间</li>
          <li>删除</li>
        </ul>
        <span class="kp_zzpm">所有正在拍卖</span>
      </div>
      <p class="kp_tsinfo">*直接选择“上架”操作默认为立即上级，自上架时候24小时结束拍卖</p>
      <div class="kp_shop">
        <div class="kp_shop_left">
          <img src="">
          <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox  add_lefimg01"></div>
        <div class="kp_shop_center">
          <p class="kp_shop_centerp ">系列还涉  李萍《妙招自然》</p>
          <div class="kp_name">
            <span>
              起拍价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
            <span class="kp_bliu">
              保留价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
          </div>
        </div>
        <div class="kp_shop_right">
          <div class="kp_bj">
            <span>
              <a href="">编辑拍品</a>
            </span>
            <span class="kp_bjsc">
              <a href="">删除</a>
            </span>
          </div>
          <div class="kp_bj">
            <span>
              <a href="">上架</a>
            </span>
            <span class="kp_bjsc">
              <a href="">推荐</a>
            </span>
          </div>
          <div class="kp_bj">
            <a href="">选择拍卖时间>></a>
          </div>
        </div>
      </div>
      <!-- 定时上架 -->
      <div class="kp_time">
        <div class="kp_timeon">
          <span class="bold">拍卖时间</span>
          <select class="kp_year">
            <option value ="volvo">2015</option>
            <option value ="saab">2014</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">年</span>
          <select class="kp_ml9">
            <option value ="volvo">12</option>
            <option value ="saab">11</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">月</span>
          <select class="kp_ml9">
            <option value ="volvo">31</option>
            <option value ="saab">30</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">日</span>
          <span class="kp_ml5">
            <input class="kp_input" type="text" placeholder="" value="3200"></span>
          <span class="kp_ml8">时</span>
          <span class="kp_ml5">
            <input class="kp_input" type="text" placeholder="" value="3200"></span>
          <span class="kp_ml8">分</span>
        </div>

        <div class="kp_timeon">
          <span class="bold">拍卖时间</span>
          <select class="kp_year">
            <option value ="volvo">2015</option>
            <option value ="saab">2014</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">年</span>
          <select class="kp_ml9">
            <option value ="volvo">12</option>
            <option value ="saab">11</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">月</span>
          <select class="kp_ml9">
            <option value ="volvo">31</option>
            <option value ="saab">30</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
          <span class="kp_ml8">日</span>
          <span class="kp_ml5">
            <input class="kp_input" type="text" placeholder="" value="3200"></span>
          <span class="kp_ml8">时</span>
          <span class="kp_ml5">
            <input class="kp_input" type="text" placeholder="" value="3200"></span>
          <span class="kp_ml8">分</span>
          <span class="kp_dssj">
            <a href="">定时上架</a>
          </span>
        </div>
        <p class="kp_tsinfo1">注：自由拍卖时间不超过24小时</p>

      </div>

      <!-- 商品循环 -->
      <div class="kp_shop">
        <div class="kp_shop_left">
          <img src="">
          <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox  add_lefimg01"></div>
        <div class="kp_shop_center">
          <p class="kp_shop_centerp ">系列还涉  李萍《妙招自然》</p>
          <div class="kp_name">
            <span>
              起拍价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
            <span class="kp_bliu">
              保留价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
          </div>
        </div>
        <div class="kp_shop_right">
          <div class="kp_bj">
            <span>
              <a href="">编辑拍品</a>
            </span>
            <span class="kp_bjsc">
              <a href="">删除</a>
            </span>
          </div>
          <div class="kp_bj">
            <span>
              <a href="">上架</a>
            </span>
            <span class="kp_bjsc">
              <a href="">推荐</a>
            </span>
          </div>
          <div class="kp_bj">
            <a href="">选择拍卖时间>></a>
          </div>
        </div>
      </div>

      <div class="kp_shop">
        <div class="kp_shop_left">
          <img src="">
          <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox  add_lefimg01"></div>
        <div class="kp_shop_center">
          <p class="kp_shop_centerp">系列还涉  李萍《妙招自然》</p>
          <div class="kp_name">
            <span>
              起拍价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
            <span class="kp_bliu">
              保留价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
          </div>
        </div>
        <div class="kp_shop_right">
          <div class="kp_bj">
            <span>
              <a href="">编辑拍品</a>
            </span>
            <span class="kp_bjsc">
              <a href="">删除</a>
            </span>
          </div>
          <div class="kp_bj">
            <span>
              <a href="">上架</a>
            </span>
            <span class="kp_bjsc">
              <a href="">推荐</a>
            </span>
          </div>
          <div class="kp_bj">
            <a href="">选择拍卖时间>></a>
          </div>
        </div>
      </div>

      <div class="kp_shop">
        <div class="kp_shop_left">
          <img src="">
          <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox  add_lefimg01"></div>
        <div class="kp_shop_center">
          <p class="kp_shop_centerp">系列还涉  李萍《妙招自然》</p>
          <div class="kp_name">
            <span>
              起拍价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
            <span class="kp_bliu">
              保留价
              <input class="kp_input" type="text" placeholder="" value="3200">
              <span>修改</span>
            </span>
          </div>
        </div>
        <div class="kp_shop_right">
          <div class="kp_bj">
            <span>
              <a href="">编辑拍品</a>
            </span>
            <span class="kp_bjsc">
              <a href="">删除</a>
            </span>
          </div>
          <div class="kp_bj">
            <span>
              <a href="">上架</a>
            </span>
            <span class="kp_bjsc">
              <a href="">推荐</a>
            </span>
          </div>
          <div class="kp_bj">
            <a href="">选择拍卖时间>></a>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<?php include'footer.php';?></body>
<link rel="stylesheet" type="text/css" href="/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="/diyUpload/css/diyUpload.css">
<script type="text/javascript" src="/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="/diyUpload/js/diyUpload.js"></script>
<script type="text/javascript" src="/js/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="/js/default.js"></script>

<link rel="stylesheet" href="/style/jquery.wysiwyg.css">
<link rel="stylesheet" href="/style/default.css" type="text/css" media="screen" charset="utf-8" />

</html>