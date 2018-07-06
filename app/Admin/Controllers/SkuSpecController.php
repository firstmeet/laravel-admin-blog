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
            $grid->id('ID')->sortable();
            $grid->name("规格名称")->editable();
            $grid->sku_spec_group_id("所属规格组")->display(function ($sku_spec_group_id){
                return SkuSpecGroup::find($sku_spec_group_id)->name;
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

            $form->display('id', 'ID');
            $form->text("name",'规格名称');
            $form->select('sku_spec_group_id','所属规格组')->options('/admin/api/spec_group');
        });
    }
}
