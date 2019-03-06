<?php
/**
 * 智丰网络
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model\saas;
use think\Model;
class SaasModel extends Model {
// 设置当前模型的数据库连接
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '112.74.177.40',
        // 数据库名
        'database'    => 'facialMaskShop',
        // 数据库用户名
        'username'    => 'basestore',
        // 数据库密码
        'password'    => 'basestore',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'tp_',
        // 数据库调试模式
        'debug'       => false,
    ];
//    protected $connection = [
//        // 数据库类型
//        'type'        => 'mysql',
//        // 服务器地址
//        'hostname'    => '127.0.0.1',
//        // 数据库名
//        'database'    => 'saas',
//        // 数据库用户名
//        'username'    => 'demo',
//        // 数据库密码
//        'password'    => 'tpshop_demo',
//        // 数据库编码默认采用utf8
//        'charset'     => 'utf8',
//        // 数据库表前缀
//        'prefix'      => 'tp_',
//        // 数据库调试模式
//        'debug'       => false,
//    ];

}
