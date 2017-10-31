<?php
namespace app\index\controller;

class Create
{
    public function index()
    {
      return view('create/createFirst');
    }
    public function editfirst()
    {
      return view('create/editFirst');
    }
    public function popsecond(){
      return view('create/createSecond');
    } 
    public function popthird(){
      return view('create/createThird');
    }
    public function popfourth(){
      return view('create/createFourth');
    }
}

