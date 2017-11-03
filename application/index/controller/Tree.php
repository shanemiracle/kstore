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
    public function recordone(){
      return view('tree/recordOne');
    }
    public function recordtwo(){
      return view('tree/recordTwo');
    }
    public function recordthree(){
      return view('tree/recordThree');
    }


}