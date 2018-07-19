<?php

namespace App\Admin\Controllers;

use App\GroupBuyingSub;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Http\Request;

class GroupBuyingSubController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Request $request)
    {
        $group_id=$request->get('group_id');
        return Admin::content(function (Content $content)use($group_id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid($group_id));
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($group_id=null)
    {
        return Admin::grid(GroupBuyingSub::class, function (Grid $grid)use($group_id) {
            if ($group_id){
                $grid->model()->where('group_id','=',$group_id);
            }
            $grid->id('ID')->sortable();
            $grid->group_id("团编码");
            $grid->stocks_name("规格");
            $grid->province_str("省份");
            $grid->city_str('城市');
            $grid->county_str('区');
            $grid->address('详细地址');
            $grid->consignee_name("收货人姓名")->editable();
            $grid->phone_number("联系方式")->editable();
            $grid->user()->name("拼团人昵称");
            $grid->is_pay("是否支付")->display(function (){
                switch ($this->is_pay){
                    case '0':
                        return "未支付";
                        break;
                    case '1':
                }
            });
            $grid->is_master("是否团长")->display(function (){
                switch ($this->is_master){
                    case '0':
                        return "否";
                        break;
                    case '1':
                        return '是';
                        break;
                }
            });
            $grid->order_id("订单号");
            $grid->filter(function($filter){

                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->where(function ($query) {

                    $query->where('order_id', '=', "{$this->input}")
                        ->orWhere('group_id', '=', "{$this->input}");

                }, '订单号或团编号');

            });
            $grid->created_at("创建时间");
            $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(GroupBuyingSub::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('province_id')->options();

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
