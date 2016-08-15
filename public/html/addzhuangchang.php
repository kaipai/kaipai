<?php include'headPerson.php';?>
<!--main开始-->
<div class="kp_main">
    <div class="center " >
       <!-- left部分 -->
       <div class="kp_left">
          <div class="kp_biaot">
              <p class="kp_p">我是买家</p>
              <ul class="kp_ul mt10">
                 <a href=""><li>正在竞拍</li></a>
                <a href=""><li>已购拍品</li></a>
                <a href=""><li>关注拍品</li></a>
                <a href=""><li>关注商家</li></a>
                <a href=""><li>买家须知</li></a>
              </ul>
          </div>
          <div class="kp_biaot">
              <p class="kp_p kpdh2" >我是买家</p>
              <ul class="kp_ul mt10">
                <a href="fbPaiPin.html"><li>发布拍品</li></a>
                <a href="ppChangKu.html"><li >拍品仓库</li></a>
                <a href="glzhuanchang.html"><li>管理专场</li></a>
                <a href="ysPaiPin.html"><li class="kp_checked">已售拍品</li></a>
                <a href="sjSheZhi.html"><li >商家设置</li></a>
                <a href="index.html"><li>认证流程</li></a>
              </ul>
          </div>
       </div>
       <!-- right部分 -->
       <div class="kp_right">
           <div>
             <span class="gl_cjzc"><a href="">创建专场 +</a></span>
           </div>
            
           <!-- 定时上架 -->
           <div class="ind_jrzc  ">
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
                    <span class="kp_ml5"><input class="kp_input" type="text" placeholder="" value="3200"></span>
                    <span class="kp_ml8">时</span>
                    <span class="kp_ml5"><input class="kp_input" type="text" placeholder="" value="3200"></span>
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
                    <span class="kp_ml5"><input class="kp_input" type="text" placeholder="" value="3200"></span>
                    <span class="kp_ml8">时</span>
                    <span class="kp_ml5"><input class="kp_input" type="text" placeholder="" value="3200"></span>
                    <span class="kp_ml8">分</span>
                </div>
                <p class="kp_tsinfo1">注：专场拍卖时间固定为日场10:00-22:00，夜场22:00-次日10:00</p>

           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">所属类目</span> 
                    <select class="ind_dp1">
                      <option value ="volvo">城市</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">专场标题</span> 
                    <input class="gl_ppinput" type="text" placeholder="限20字以内" >
           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">专场海报</span> 
                    <input type="file" name="uploadFile"  class="gl_sctu"/> 尺寸：1900*300 图片容量不大于1M
           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">专场首焦</span> 
                    <input type="file" name="uploadFile" class="gl_sctu"/> 尺寸：1900*300 图片容量不大于1M
                    
           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">拍品数量</span> 
                    <input class="mt_input" type="text" placeholder="" > 个  *限制20-60个
           </div>
           <div class="mt20  ycsd">
                    <span class="ind_jrzz" style="float:left"><span class="bold">专场介绍 </span><br> <p class="gl_zyi">注：图片宽度限制860以内</p></span> 
                    <div style="width:540px; background:#fff; float:left">
                      <textarea id="qqq"  >
                      </textarea>
                    </div>  
           </div>
           <div class="ind_licj">立即创建</div>


           
           
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
<script type="text/javascript">
$(function(){
  //alert(1);

  $('#qqq').wysiwyg({
        controls:"bold,italic,|,undo,redo,image"
      });
})
</script>
</html>