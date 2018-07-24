<?php

namespace App\Admin\Controllers;

use App\SkuSpec;

use App\SkuSpecGroup;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class SkuSpecController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Request $request)
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

        return Admin::grid(SkuSpec::class, function (Grid $grid) {
            $group_id=\Illuminate\Support\Facades\Request::get('group_id');
            $grid->model()->where('sku_spec_group_id',$group_id);
            $grid->id('ID')->sortable();
            $grid->name("规格名称")->editable();
            $grid->sku_spec_group_id("所属规格组")->display(function ($sku_spec_group_id){
                return SkuSpecGroup::find($sku_spec_group_id)->name;
            });
            $grid->actions(function ($actions){
                $actions->disableEdit();
                $actions->append('<a href='.url('admin/sku_spec/'.$actions->getKey().'/edit?group_id='.\Illuminate\Support\Facades\Request::get('group_id')).'><i class="fa fa-edit"></i></a>');
            });
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
        return Admin::form(SkuSpec::class, function (Form $form) {
            $group_id=\Illuminate\Support\Facades\Request::get('group_id');
            $form->display('id', 'ID');
            $form->hidden("sku_spec_group_id")->value($group_id);
            $form->text("name",'规格名称');
            if ($group_id){
                $group=SkuSpecGroup::find($group_id);
            }
            if (isset($group)){
                switch ($group->type){
                    case 1:
                        $form->text("value",'值');
                        break;
                    case 2:
                        $form->color("value",'值');
                        break;
                    case 3:
                        $form->image("value",'值')->removable();
                        break;
                }
            }
            $form->saved(function($form){
                return redirect('/admin/sku_spec?group_id='.$form->sku_spec_group_id);
            });
            $form->setAction('/admin/sku_spec?group_id='.$group_id);
            $form->tools(function (Form\Tools $tools) {


                // 去掉跳转列表按钮
                $tools->disableListButton();

            });

        });
    }
}
