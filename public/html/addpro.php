<?php include'headPerson.php';?>

<div class="kp_main">
    <div class="center " >
     


       <!-- left部分 -->
       <div class="kp_left">
          <div class="kp_biaot">
              <p class="kp_p" id="kpdh">我是买家</p>
              <ul class="kp_ul mt10" id="kpdh1">
                 <a href=""><li>正在竞拍</li></a>
                <a href=""><li>已购拍品</li></a>
                <a href=""><li>关注拍品</li></a>
                <a href=""><li>关注商家</li></a>
                <a href=""><li>买家须知</li></a>
              </ul>
          </div>
          <div class="kp_biaot">
              <p class="kp_p">我是商家</p>
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
          <div>
              <span class="bold">所属类目</span>
              <select class="kp_year1 ml20">
                      <option value ="volvo">国画</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
              </select>
              <select class="kp_year1">
                      <option value ="volvo">花鸟</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
              </select>
              <span class="ind_x">*</span>
              <span class="ml20"><a href="">类目说明 >></a></span>
          </div>
          <div class="ind_fg">
             <div><span class="ind_dx">单选</span><span class="ind_dx1">请仔细选择筛选项，以便有更大几率被搜索显示</span></div>
             <div class="ind_fge1">
                 <span class="ind_fge">风 格</span>
                 <input name="y01" type="radio"  class="ind_checkbox"/>写意
                 <input name="y01" type="radio"  class="ind_checkbox"/>工笔
             </div>
             <div class="ind_fge1">
                 <span class="ind_fge">高 度</span>
                 <input name="y02" type="radio"  class="ind_checkbox"/>  20-40cm
                 <input name="y02" type="radio"  class="ind_checkbox"/>  20-40cm
                 <input name="y02" type="radio"  class="ind_checkbox"/>  20-40cm
                 <input name="y02" type="radio"  class="ind_checkbox"/>  20-40cm
                 <input name="y02" type="radio"  class="ind_checkbox"/>  20-40cm
             </div>
             <div class="ind_fge1">
                 <span class="ind_fge">形 式</span>
                 <input name="y03" type="radio"  class="ind_checkbox"/>  立轴
                 <input name="y03" type="radio"  class="ind_checkbox"/> 镜片
                 <input name="y03" type="radio"  class="ind_checkbox"/>  镜心
                 <input name="y03" type="radio"  class="ind_checkbox"/>  扇面
                 <input name="y03" type="radio"  class="ind_checkbox"/>  写意
                  
             </div>
          </div>
          <div class="ind_zpinfo">
              <div class="ind_zpinfo_left ind_zpinfo_leftinfo bold">作品信息</div>
              <div class="ind_zpinfo_right">
                  <div class="ind_btm">作者 <input class="zz_input" type="text" placeholder="请输入作者名字" ></div>
                  <div class="ind_btm">创作时间/年代 <select class="ind_year">
                      <option value ="volvo">2015</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
                    <span class="ind_x">*</span>
                  </div>
                  <div class="ind_btm">题材 <select class="ind_year1">
                      <option value ="volvo">2015</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
                    <span class="ind_x">*</span>
                  </div>
                  <div class="ind_btm">尺寸（cm） <input class="zz_input2" type="text" placeholder="请输入尺寸" ><span class="ind_x"> *</span></div>
                  <div class="ind_btm">材质 <select class="ind_year1">
                      <option value ="volvo">2015</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
                  </div>
                  <div class="ind_btm">装裱 <select class="ind_year1">
                      <option value ="volvo">2015</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
                    <span class="ind_x">*</span>
                  </div>
              </div>
          </div>
          <div class="ind_ppbt "><span class="bold">拍品标题</span> <input class="ind_ppinput ind_uu" type="text" placeholder="请输入标题" > <span class="ind_x"> *</span></div>
       
          <div class="ind_zpinfo">
              <div class="ind_zpinfo_left ind_zpinfo_lefta"> <span class="bold">拍品图片 </span> <div class="gl_zyi">注：<br>不超过5张限550kb以内张</div></div>
             <div id="as" ></div>
      
         
                      
               
          </div>
          <div class="ind_zpinfo">
              <div class="ind_zpinfo_left"><span class="bold">拍品详情</span> <div class="gl_zyi">注：<br>图片宽度限860像素内。如不编辑手机版本，将在移动客户端默认显示缩电脑版。</div>
                <div class="ind_dnb"><a href="">电 脑 版</a></div>
                <div class="ind_dnb1"><a href="">手 机 版</a></div>
                <div class="ind_dnb2"><a href="">立即保存</a></div>
              </div>
              <div class="ind_zpinfo_right">
               <div style="width:700px;  background:#fff">
                <textarea id="qqq" >
                 
                  
                </textarea>
              </div> 

              </div>
          </div>
          <p class="kp_tsinfo1 ">注：【店内分类】与【专场分类】只能选择进入一个。【店铺分类】最多选择三项，不选择进入默认“未分类”</p>
          <div class="mt30 ind_dash">
            <div  class="">
               <span class="ind_jrzz bold">店内分类</span>
               <input name="Fruit" type="radio" class="ind_radio"/> 
               <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox"> 
               <select class="ind_dp1">
                      <option value ="volvo">显示店铺分类</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
               <input type="checkbox" name="checkbox1" value="checkbox"  class="ind_checkbox"> 
                    <select class="ind_dp1">
                      <option value ="volvo">显示店铺分类</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
                <input type="checkbox" name="checkbox1" value="checkbox"  class="ind_checkbox"> 
                    <select class="ind_dp1">
                      <option value ="volvo">显示店铺分类</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
            </div>
            <div class="ind_jrzc">
                  <span class="ind_jrzz bold">进入专场</span>  
                  <input name="Fruit" type="radio" class="ind_checkbox"/> 
                  <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox"> 
                  <select class="ind_dp1">
                      <option value ="volvo">显示专场名称</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
              
            </div>
          </div>
              
                 
          <div class="ind_jrzc">
             <div >
                    <span class="ind_jrzz bold">特色服务</span>  
                    <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">  有文物拍卖
                    <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">  有文物拍卖
                    <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox">  有文物拍卖
                    <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox">  有文物拍卖
                    <input type="checkbox" name="checkbox1" value="checkbox" class="ind_checkbox ">  有文物拍卖
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">起拍价</span>  
                    <input class="mt_input" type="text" placeholder="" > 元
             </div >
             <div class="mt20">
                    <span class="ind_jrzz bold">加价幅度</span>  
                    <input class="mt_input" type="text" placeholder="" > 元
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">佣金</span> 
                    <input name="yogjin" type="radio"  class="ind_checkbox"/>
                    <input class="mt_input2" type="text" placeholder="" > %  
                    <input name="yogjin" type="radio"  class="ind_checkbox wu"/> 无 
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">保留价</span> 
                    <input name="baoliu" type="radio"  class="ind_checkbox"/>
                    <input class="mt_input2" type="text" placeholder="" > 元  
                    <input name="baoliu" type="radio"  class="ind_checkbox  wu"/>  无 
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">延时周期</span> 
                    <input name="yogjins" type="radio"  class="ind_checkbox"/>5分钟/次  
                    <input name="yogjins" type="radio"  class="ind_checkbox wu"/>不延时
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">发货地区</span> 
                    <input name="fahuodq" type="radio"  class="ind_checkbox"/> 默认发货地区：中国 浙江 杭州  <a href="">编辑</a>
             </div>
             <div class="mt20">
                    <span class="ind_jrzz"></span> 
                    <input name="fahuodq" type="radio"  class="ind_checkbox"/>  <select class="ind_dp1">
                      <option value ="volvo">中国</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>

                    <select class="ind_dp1">
                      <option value ="volvo">省份</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>

                    <select class="ind_dp1">
                      <option value ="volvo">城市</option>
                      <option value ="saab">2014</option>
                      <option value="opel">Opel</option>
                      <option value="audi">Audi</option>
                    </select>
             </div>
             <div class="mt20">
                    <span class="ind_jrzz bold">运费</span> 
                    <input name="yunfei" type="radio"  class="ind_checkbox"/> 
                    <input class="mt_input2" type="text" placeholder="" > 元  
                    <input name="yunfei" type="radio"  class="ind_checkbox wu"/> 包邮
             </div>
            
          </div>

          <div class="ind_jrzc">
            <span class="ind_ymyl"><a href="">页面预览</a></span><span class="ind_ymyl ind_ymy2"><a href="">存入仓库</a></span>
          </div>
          <!-- 定时上架 -->
           <div class="ind_jrzc">
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
                <p class="kp_tsinfo1">注：自由拍卖时间不超过48小时,每日上线限5件</p>

           </div>

           <div class="ind_jrzc ind_botom">
            <span class="ind_ymy3"><a href="">立即发布</a></span>
            <p class="kp_tsinfo1">提示：多项可进入拍品仓库批量发布</p>
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
<script type="text/javascript">
$(function(){
	//alert(1);

	$('#qqq').wysiwyg({
				controls:"bold,italic,|,undo,redo,image"
			});
})
</script>
<script type="text/javascript">

/*
* 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
* 其他参数同WebUploader
*/

$('#test').diyUpload({
	url:'server/fileupload.php',
	success:function( data ) {
		console.info( data );
	},
	error:function( err ) {
		console.info( err );	
	}
});

$('#as').diyUpload({
	url:'server/fileupload.php',
	success:function( data ) {
		console.info( data );
	},
	error:function( err ) {
		console.info( err );	
	},
	buttonText : '选择文件',
	chunked:true,
	// 分片大小
	chunkSize:512 * 1024,
	//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
	fileNumLimit:4,
	fileSizeLimit:500000 * 1024,
	fileSingleSizeLimit:50000 * 1024,
	accept: {}
});
</script>
</html>