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
 * @author: lhb
 */

namespace app\common\model\saas;

class MiniappTemplate extends ExtendBase
{

    public function app()
    {
        return $this->belongsTo('App', 'app_id', 'app_id');
    }
}