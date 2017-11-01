<?php
namespace app\index\controller;

class Tree
{
    public function index()
    {
      return view('tree/createFirst');
    }
    public function editfirst()
    {
      return view('tree/editFirst');
    }
    public function popsecond(){
      return view('tree/createSecond');
    } 
    public function popthird(){
      return view('tree/createThird');
    }
    public function popfourth(){
      return view('tree/createFourth');
    }
}