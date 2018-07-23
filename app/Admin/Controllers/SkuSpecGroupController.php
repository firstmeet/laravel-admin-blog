<?php

namespace App\Admin\Controllers;

use App\SkuSpecGroup;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SkuSpecGroupController extends Controller
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
        return Admin::grid(SkuSpecGroup::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name("规格组名称")->editable();
            $grid->sort("排序")->editable();
            $grid->type("类型")->radio([
                1=>'文本',
                2=>'颜色',
                3=>'图片'
            ]);
            $grid->status("状态")->radio([
                1=>'未启用',
                2=>'启用'
            ]);
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SkuSpecGroup::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name',"规格组名称");
            $form->select("type","类型")->options([1=>'文本',2=>'颜色',3=>'图片']);
            $form->number('sort',"排序");
            $form->radio('status')->options([
                1=>'未启用',
                2=>'启用'
            ])->default(2);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
