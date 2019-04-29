<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>马启蒙的小站</title>
    <meta name="description" content="马启蒙" />
    <meta name="keywords" content="马启蒙的小站" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="/Public/Css/public.css" rel="stylesheet" type="text/css">
    <link href="/Public/Css/loading.css" rel="stylesheet" type="text/css">
    <link href="/Public/Index/css/indexStyle.css" rel="stylesheet" type="text/css">
    <!--<link type="text/css" rel="stylesheet" media="screen" href="http://oss.rainman.me/rainman.me/js/sakura/jquery-sakura.css" />-->
    <style type="text/css">
        :root{ width: 100%; height: 100%;}
        ::selection{ background-color: #49C9DE; color: #fff;}
        body{ width: 100%; height: 100%; overflow-x: hidden; overflow-y: hidden;}
        /*@media screen and (max-width: 400px) {
        	html{
        		transform: rotate(90deg);
        	}
        }*/
    </style>

    <script type="text/javascript" src="https://cdn.webfont.youziku.com/wwwroot/js/wf/youziku.api.min.js"></script>
    <script type="text/javascript">
        $youziku.load("body", "e988496a1d1b4833ae19bbde23056248", "HanWangYenThin-Gb5");
        /*$youziku.load("#id1,.class1,h1", "e988496a1d1b4833ae19bbde23056248", "HanWangYenThin-Gb5");*/
        /*．．．*/
        $youziku.draw();
    </script>
</head>
<body oncontextmenu="doNothing()">
<div class="lingan_1" id="lingan_1">
    <a href="http://www.miitbeian.gov.cn/" target="_blank" id="ba">鲁ICP备17031392号</a>
    <img src="/Public/Index/images/logo.png" id="logo">
    <img src="/Public/Index/images/musicicon.png" id="music">
    <section class="wrap">
        <audio id="audio" src="/Public/Index/music/Changes.mp3" loop autoplay></audio>
        <ul id="nav">
            <li id="current1">
                <span>首页</span>
                <span>Home</span>
            </li>
            <li>
                <span>服务范围</span>
                <span>Specialty</span>
            </li>
            <li>
                <span>项目案例</span>
                <span>Works</span>
            </li>
            <li>
                <span>联系我们</span>
                <span>Contact</span>
            </li>
        </ul>
    </section>
    <span id="menu"></span>
    <section class="page" id="Home">
        <!--<h2 id="hello">欢迎访问马启蒙的小站，为了给您更好的用户体验请使用IE8以上版本浏览器访问！</h2>-->
        <div class="message">
            <h3>宝剑锋从磨砺出 梅花香自苦寒来</h3>
            <p>I am Ma Qimeng, from Linyi City, Shandong Province. My dream is to become a full stack engineer. My skills include web design, HTML5 + CSS3, JavaScript, jQuery, PHP, Thinkphp, MySQL, etc.</p>
            <a href="#" onclick="alert('非常抱歉，正在努力开发中:-)')">
                查看详情
                <span></span>
            </a>
        </div>
        <div class="imgwrap" id="imgwrap">
            <?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo["b_image"]); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <ul class="bannermenu" id="menu1">
            <?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="bannerscroll">
            <div id="goleft">
                <img src="/Public/Index/images/left.png">
            </div>
            <div id="goright">
                <img src="/Public/Index/images/left.png">
            </div>
        </div>
    </section>
    <section class="page">
        <h1 class="title">THREE BIG CORE BUSINESS</h1>
        <section class="content">
            <ul class="range">
                <li>
                    <span></span>
                    <p>WEB</p>
                </li>
                <li>
                    <span></span>
                    <p>APP</p>
                </li>
                <li>
                    <span></span>
                    <p>WECHAT</p>
                </li>
            </ul>
        </section>
    </section>
    <section class="page">
        <section class="details_wrap">
            <section class="details">
                <article class="details_img">
                    <img src="">
                </article>
                <section class="details_js">
                    <article class="present">
                        <h2>OKKO专题活动</h2>
                        <h3>time:</h3>
                        <a href="" target="_blank">查看网站</a>
                        <p>YIYU visual design studio, is a number of young people who love the design of the composition, they are mainly for electricity providers, advertising, creative visual solutions. Young, energetic, passionate, this is their main source.</p>
                    </article>
                </section>
            </section>
            <img src="/Public/Index/images/close.png" id="close">
        </section>
        <section class="project">
            <ul class="project_top">
                <li id="current">all<input type="hidden" value=""></li>
                <?php if(is_array($sel)): $i = 0; $__LIST__ = $sel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php echo ($vo["t_text"]); ?><input type="hidden" value="<?php echo ($vo["t_id"]); ?>"></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <article class="case_wrap">
                <ul class="case scrollbar" id="style-1">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li onclick="showdetails(this)">
                            <img src="<?php echo ($vo["m_minimg"]); ?>">
                            <span>
                                <img src="/Public/Index/images/big.png">
                            </span>
                            <input type="hidden" value="<?php echo ($vo["m_id"]); ?>">
                            <p><?php echo ($vo["m_title"]); ?></p>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </article>
        </section>
    </section>
    <section class="page">
        <section class="contact">
            <img src="/Public/Index/images/2.PNG">
            <h1>马启蒙的小站</h1>
            <p>网站:&nbsp;&nbsp;maqimeng.top</p>
            <p>电话:&nbsp;&nbsp;15165520973</p>
            <p>邮箱:&nbsp;&nbsp;2576229840@qq.com</p>
            <p>QQ:&nbsp;&nbsp;870158555</p>
            <p>地址:&nbsp;&nbsp;山东省临沂市</p>
        </section>
    </section>
    <!--预加载  start-->
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
        <div class="load_title">Loading...
            <span>V1.0</span></div>
    </div>
</div>
    <!--预加载   end-->

    <script src="/Public/Js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="/Public/Index/js/indexJs.js" type="text/javascript"></script>
    <!--<script src="http://oss.rainman.me/rainman.me/js/sakura/jquery-sakura.js"></script>-->
    <script type="text/javascript">
        // 等待所有加载
        $(function() {
            // $('body').sakura();
            $('body').addClass('loaded');
            $('#loader-wrapper .load_title').remove();
        });
        // $(window).load(function() {
        //     $('body').sakura();
        // });

        // $(window).load(function(){
        //
        // });
    </script>

    <script type="text/javascript" src="//www.maqimeng.com/php/app.php?widget-init.js"></script>
</body>
</html>