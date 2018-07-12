<?php

namespace App\Admin\Controllers;

use App\Sku;

use App\SkuSpec;
use App\SkuSpecGroup;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class SkuController extends Controller
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
    public function create(Request $request)
    {
        $goods_id=$request->get('goods_id');

        return Admin::content(function (Content $content)use($goods_id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form($goods_id));
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Sku::class, function (Grid $grid) {
            $grid->model()->where('goods_id',\Illuminate\Support\Facades\Request::get('goods_id'));
            $grid->id('ID')->sortable();
            $grid->sku_id('SKU_ID');
            $grid->sku('所选规格')->display(function ($sku) {
                $arr=json_decode($sku);
                $arr2=[];
                foreach ($arr as $key=>$value){
                    $str_arr=explode(':',$value);
                    $spec_group=SkuSpecGroup::find($str_arr[0]);
                    $spec=SkuSpec::find($str_arr[1]);
                    $str=$spec_group->name.':'.$spec->name;
                    array_push($arr2,$str);
                }
                return implode(';',$arr2);
            });
            $grid->stock_number("库存数量");
            $grid->active_price('拼团价')->editable();
            $grid->single_price('单独购买价')->editable();
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
        return Admin::form(Sku::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->hidden('goods_id',"GOODS_ID")->value(\Illuminate\Support\Facades\Request::get('goods_id'));
            $form->hidden('sku_id','SKU_ID')->value(Uuid::uuid());
            $options=SkuSpec::all()->pluck('name','id');
            $form->listbox("sku",'SKU')->options($options)->rules("required",[
                "required"=>'请选择规格'
            ]);
            $form->text('active_price','拼团价格')->rules("required|numeric",[
                "required"=>'请输入拼团价格',
                "numeric"=>'输入类型错误，请输入数字'
            ]);

            $form->text('single_price','单独购买价格')->rules("required|numeric",[
                "required"=>'请输入单独购买价格',
                "numeric"=>'输入类型错误，请输入数字'
            ]);
            $form->number("stock_number","库存数量");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            $form->saved(function (Form $form) {

                // 跳转页面
                return redirect('/admin/sku?goods_id='.\Illuminate\Support\Facades\Request::get('goods_id'));

            });
        });
    }
}
