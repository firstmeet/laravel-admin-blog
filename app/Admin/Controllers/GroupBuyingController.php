<?php

namespace App\Admin\Controllers;

use App\GroupBuying;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;

class GroupBuyingController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
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
    protected function grid()
    {
        return Admin::grid(GroupBuying::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->group_id("团编码");
            $grid->goods()->goods_name("拼团商品");
            $grid->user()->name("团长名称");
            $grid->current_size("当前人数");
            $grid->group_size("团最大人数");
            $grid->exp_time("过期时间");
            $grid->status("团状态")->display(function (){
               switch ($this->status){
                   case '0':
                       return "失败";
                       break;
                   case '1':
                       return "开团成功,待拼团";
                       break;
                   case '2':
                       return "团已完成";
                       break;
               }
            });
            $grid->actions(function ($actions){
                $actions->append('<a href='.\url('admin/group_buying_sub?group_id='.$actions->row->group_id).' title="拼团人员"><i class="fa fa-align-justify"></i></a>');
            });
            $grid->created_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(GroupBuying::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
