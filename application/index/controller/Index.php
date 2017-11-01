<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
//        http://localhost:8080/api/apiQuaTree3Add?parent_id=3-200&cn_name=%E6%B5%81%E7%A8%8B%E9%A2%98%E6%9D%90%E6%96%87%E4%BB%B61&en_name=flows%20else%20file&address=htgsfg.doc&size=1000&remark=62173889237&depart=%E8%B4%A8%E7%AE%A1%E9%83%A8&create_user=xiaoj
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }

    public function test()
    {
        return "test";
    }
    // public function home()
    // {
    //     return "home";
    // }

    public function fileup()
    {
        return '<form action="/api/apiFileUp" enctype="multipart/form-data" method="post"> <input type="file" name="file"/> <br> <input type="submit" value="上传"/> </form> ';
    }
}
