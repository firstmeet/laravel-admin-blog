<?php

namespace App\Admin\Controllers;

use App\Stock;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class StockController extends Controller
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

            $content->header('拼团');
            $content->description('规格');

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
        return Admin::grid(Stock::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->stocks_name('规格名称')->editable();
            $grid->sort("排序")->editable();
            $grid->is_show("是否显示")->radio([
                1=>'不显示',
                2=>'显示'
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
        return Admin::form(Stock::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('stocks_name',"规格名称");
            $form->number('sort','排序');
            $form->radio('is_show','是否显示')->options([
                1=>'不显示',
                2=>'显示'
            ])->default(1);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
