<?php include'headPerson.php';?>
<!--main开始-->
<div class="kp_main">
    <div class="center " >
       <!-- left部分 -->
       <div class="kp_left">
          <div class="kp_biaot">
              <p class="kp_p">我是买家</p>
              <ul class="kp_ul mt10">
                <a href=""><li>我是买家</li></a>
                <a href=""><li>我是买家</li></a>
                <a href=""><li>我是买家</li></a>
                <a href=""><li>我是买家</li></a>
                <a href=""><li>我是买家</li></a>
              </ul>
          </div>
          <div class="kp_biaot">
              <p class="kp_p kpdh2" >我是买家</p>
              <ul class="kp_ul mt10">
                <a href="fbPaiPin.html"><li>发布拍品</li></a>
                <a href="ppChangKu.html"><li >拍品仓库</li></a>
                <a href="glzhuanchang.html"><li>管理专场</li></a>
                <a href="ysPaiPin.html"><li>已售拍品</li></a>
                <a href="sjSheZhi.html"><li class="kp_checked">商家设置</li></a>
                <a href="index.html"><li>认证流程</li></a>
              </ul>
          </div>
       </div>
       <!-- right部分 -->
       <div class="kp_right">

           <div class="mt20">
                    <div class="ind_jrzz bold">店铺标志</div> 
                    <div id="preview" class="dianpbai"><img src="images/img_03.jpg"></div>
                    <span><input type="file" onchange="previewImage(this)" /> </span>
           </div>
           <div class="mt20">
                    <div class="ind_jrzz bold">店铺抬头</div> 
                    <div id="preview1" class="dianpbai1"><img src="images/img_06.jpg"></div>
                    <span><input type="file" onchange="previewImage1(this)" /> </span>
           </div>
           <div class="mt20">
                    <span class="ind_jrzz bold">店铺签名</span> 
                    <input class="zz_inputqm" type="text" placeholder="" >
                    <br> <span class="ind_jrzz"></span> *限35字以内，显示在店铺名称下方   
           </div>
           <div class="mt20">
                    <div class="ind_jrzz bold">店铺推荐<br>栏位底图</div> 
                    <div id="preview2" class="dianpbai1"><img src="images/img_06.jpg"></div>
                    <span><input type="file" onchange="previewImage2(this)" /> </span>
           </div>
           <div class="mt20">
                    <div class="ind_jrzz bold">拍品分类</div> <input class="zz_inputqm1" type="text" placeholder="" value="分类1"><input class="zz_inputqm1" type="text" placeholder="" value="分类2">
                    <a href=""><span class="ind_jia"> &nbsp</span></a>
                    <span class="ind_jia1"><a href="">编辑 >></a></span>
                    
           </div>
           <div class="mt20  ycsd">
                    <span class="ind_jrzz bold" style="float:left">店铺自诉</span> 
                    <div> <span class="ind_fbxx">发布须知</span><span>不得发布反动，暴恐，色情信息</span></div>
                  
           </div>
            <div class="mt20  ycsd">
                    <span class="ind_jrzz" style="float:left"> &nbsp</span>
                    <div style="width:540px; background:#fff; float:left">

                      <textarea id="qqq" >
                      </textarea>
                    </div>  
           </div>

           <div class="ind_licj">确认提交</div>


           
           
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
<script type="text/javascript">
    //图片上传预览    IE是用了滤镜。
        function previewImage2(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview2');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead>';
              var img = document.getElementById('imghead');
              img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
              }
              var reader = new FileReader();
              reader.onload = function(evt){img.src = evt.target.result;}
              reader.readAsDataURL(file.files[0]);
          }
          else //兼容IE
          {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;"+sFilter+src+"\"'></div>";
          }
        } 

                //图片上传预览    IE是用了滤镜。
        function previewImage(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead>';
              var img = document.getElementById('imghead');
              img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
              }
              var reader = new FileReader();
              reader.onload = function(evt){img.src = evt.target.result;}
              reader.readAsDataURL(file.files[0]);
          }
          else //兼容IE
          {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;"+sFilter+src+"\"'></div>";
          }
        }
        //图片上传预览    IE是用了滤镜。
        function previewImage1(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview1');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead>';
              var img = document.getElementById('imghead');
              img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
              }
              var reader = new FileReader();
              reader.onload = function(evt){img.src = evt.target.result;}
              reader.readAsDataURL(file.files[0]);
          }
          else //兼容IE
          {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;"+sFilter+src+"\"'></div>";
          }
        }
      
        function clacImgZoomParam( maxWidth, maxHeight, width, height ){
            var param = {top:0, left:0, width:width, height:height};
            if( width>maxWidth || height>maxHeight )
            {
                rateWidth = width / maxWidth;
                rateHeight = height / maxHeight;
                 
                if( rateWidth > rateHeight )
                {
                    param.width =  maxWidth;
                    param.height = Math.round(height / rateWidth);
                }else
                {
                    param.width = Math.round(width / rateHeight);
                    param.height = maxHeight;
                }
            }
             
            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
            return param;
        }
</script>   
</html>