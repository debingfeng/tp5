<?php
namespace app\group\validate;

use think\Validate;

class Note extends Validate
{
    protected $rule =   [
        'title'  => 'require|max:50',
        'content'   => 'max:10000',
        'type' => 'number|between:1,5',
    ];

    protected $message  =   [
        'title.require' => '标题必须填写',
        'title.max'     => '标题最多不能超过50个字符',
        'content.max'   => '内容最好不要超过10000字',
        'type.number'  => '年龄只能在1-5之间',
    ];
}