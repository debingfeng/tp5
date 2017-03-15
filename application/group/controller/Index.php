<?php
namespace app\group\controller;
use think\Controller;
use think\Request;

class Index extends Controller
{

    public function index()
    {
        return json(array(),200,['aa']);
    }
    public function test()
    {
        return json(array('code'=>200));
    }
}
