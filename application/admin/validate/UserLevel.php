<?php
namespace app\admin\validate;
use think\Validate;
class UserLevel extends Validate
{
    // 验证规则
    protected $rule = [
        ['level', 'require|number|unique:user_level'],
        ['level_name', 'unique:user_level'],
        ['max_money','number'],
        ['remaining_money','number'],
        ['rate','require|between:0,100'],
    ];
    //错误信息
    protected $message  = [
        'level.require'         => '等级必填',
        'level.number'          => '等级必须是数字',
        'level.unique'          => '已存在相同等级',
        // 'level_name.require'    => '名称必填',
        'level_name.unique'     => '已存在相同等级名称',
        // 'max_money.require'        => '最大代理佣金必填',
        'max_money.number'         => '最大代理佣金必须是数字',
        // 'max_money.unique'         => '已存在相同最大代理佣金',
        // 'remaining_money'         => '代理拥金总和必填',
        'remaining_money.number'         => '代理拥金总和必须是数字',
        'remaining_money'         => '已存在相同代理拥金总和',
        'rate.require'      => '佣金占比必填',
        'rate.between'      => '佣金占比在0-100之间',
        // 'rate.unique'       => '已存在相同佣金占比',
    ];
    //验证场景
    protected $scene = [
        'edit'  =>  [
            'level'         =>'require|unique:user_level,level^level_id',
            'level_name'    =>'require|unique:user_level,level_name^level_id',
            'max_money'        =>'number',
            'remaining_money'        =>'number',
            'rate'    =>'require|between:0,100',
        ],
    ];
}