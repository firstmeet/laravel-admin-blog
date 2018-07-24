<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 17:08
 */

namespace App\Admin\Extensions\Tools;


use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class NewSku extends AbstractTool
{
    protected $goods_id;
    public function __construct($goods_id)
    {
        $this->goods_id=$goods_id;
    }

    protected function script()
    {
        return <<<EOT
$(".new_sku").click(function(){
         $.ajax({
        method: 'post',
        url: '/admin/sku/task/new_sku',
        data: {
            _token:LA.token,
            goods_id:{$this->goods_id}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
})
       
EOT;
    }
    public function render()
    {
       Admin::script($this->script());
       return view('admin.tools.new_sku');
    }
}