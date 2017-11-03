<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
      return view('home/home');
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
