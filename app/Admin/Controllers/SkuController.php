<?php

namespace App\Admin\Controllers;

use App\Good;
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
use http\Env\Url;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

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
            $grid->actions(function ($actions){
                $actions->disableEdit();
                $actions->prepend('<a href="'.\url('admin/sku/'.$actions->getKey().'/edit?goods_id='.\Illuminate\Support\Facades\Request::get('goods_id')).'"><i class="fa fa-edit"></i></a>');
            });
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
            $goods_id=\Illuminate\Support\Facades\Request::get('goods_id');
            $form->display('id', 'ID');
            $form->hidden('goods_id',"GOODS_ID")->value($goods_id);
            $form->hidden('sku_id','SKU_ID')->value(Uuid::uuid());
            $options=[];
            if ($goods_id){
                $good=Good::find($goods_id);
                $options=SkuSpec::whereIn('id',$good->spec_groups)->pluck('name','id');
            }

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
            $form->saving(function (Form $form){
               $sku=array_filter($form->sku);
               $goods_id=$form->goods_id;
               $goods=Good::find($goods_id);
               if (count($goods->spec_groups)!=count($sku)){
                   $error = new MessageBag([
                       'title'   => '规格错误',
                       'message' => '选择规格数量错误,共有'.count($goods->spec_groups).'个规格组,已选择'.count($sku).'个',
                   ]);

                   return back()->with(compact('error'));
               }
               $arr=[];
                foreach ($sku as $key=>$value){
                    $sku_spec=SkuSpec::find($value);
                    array_push($arr,$sku_spec->sku_spec_group_id);
                }

                if (count($sku)!=count(array_unique($arr))){
                    $error = new MessageBag([
                        'title'   => '规格错误',
                        'message' => '不能选择相同规格组的规格',
                    ]);

                    return back()->with(compact('error'));
                }
            });
            $form->saved(function (Form $form) {

                // 跳转页面
                return redirect('/admin/sku?goods_id='.\Illuminate\Support\Facades\Request::get('goods_id'));

            });
        });
    }
}
