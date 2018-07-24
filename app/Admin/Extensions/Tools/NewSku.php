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
    protected function script()
    {
        return <<<EOT
$("button:new_sku").click(function(){

  console.log(1);
})
       
EOT;
    }
    public function render()
    {
       Admin::script($this->script());
       return view('admin.tools.new_sku')
    }
}