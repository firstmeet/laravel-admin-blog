<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\NewSku;
use App\Good;
use App\Services\GroupBuyingService;
use App\Sku;

use App\SkuSpec;
use App\SkuSpecGroup;
use function Couchbase\defaultDecoder;
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

            $content->header('库存规格');
            $content->description('库存规格列表');

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
            $grid->sku_change('所选规格');
            $grid->stock_number("库存数量")->editable();
            $grid->active_price('拼团价')->editable();
            $grid->group_price("团长价")->editable();
            $grid->single_price('单独购买价')->editable();
            $grid->created_at();
            $grid->actions(function ($actions){
                $actions->disableEdit();
                $actions->prepend('<a href="'.\url('admin/sku/'.$actions->getKey().'/edit?goods_id='.\Illuminate\Support\Facades\Request::get('goods_id')).'"><i class="fa fa-edit"></i></a>');
            });
            $grid->tools(function ($tools) {
                $tools->append(new NewSku(\Illuminate\Support\Facades\Request::get('goods_id')));
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
            $form->hidden('id', 'ID');
            $form->hidden('goods_id',"GOODS_ID")->value($goods_id);
            $form->hidden('sku_id','SKU_ID')->value(Uuid::uuid());
            $options=[];
            if ($goods_id){
                $good=Good::find($goods_id);
                $options=SkuSpec::whereIn('id',$good->spec_groups)->pluck('name','id');
            }
            $form->embeds('sku','规格',function ($form)use ($goods_id){
                $spec_group=Good::find($goods_id);
                foreach ($spec_group->spec_groups as $key=>$value){
                    $group=SkuSpecGroup::find($value);
                    $form->radio($group->id,$group->name)->options($group->sku_spec->pluck('name','id'));
                }
            });
            $form->text('active_price','拼团价格')->rules("required|numeric",[
                "required"=>'请输入拼团价格',
                "numeric"=>'输入类型错误，请输入数字'
            ]);
            $form->text('group_price','团长价格')->rules("required|numeric",[
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
            $form->tools(function (Form\Tools $tools) {
                // 去掉跳转列表按钮
                $tools->disableListButton();

            });
            $form->saved(function (Form $form) {

                // 跳转页面
                return redirect('/admin/sku?goods_id='.\Illuminate\Support\Facades\Request::get('goods_id'));

            });
        });
    }
    public function new_sku(Request $request)
    {
        $goods_id=$request->goods_id;
        $service=new GroupBuyingService();
        $arr=[];
        $goods=Good::find($goods_id);
        foreach ($goods->spec_groups as $key=>$v){
            $spec=SkuSpec::where('sku_spec_group_id',$v)->get()->toArray();
            $arr1=array_column($spec,'id');
            array_push($arr,$arr1);
        }
        $list=$service->getArrSet($arr);
        $arr2=[];
        foreach ($list as $key=>$value){
            $arr=[];
            foreach ($value as $k=>$v){
                $spec=SkuSpec::find($v);
                $arr[$spec->sku_spec_group_id]=$v;
            }
            $data=[
                'goods_id'=>$goods_id,
                'sku'=>$arr,
                'active_price'=>0,
                'single_price'=>0,
                'sale'=>0,
                'sku_id'=>Uuid::uuid(),
                'stock_number'=>0,
                'group_price'=>0
            ];
            Sku::create($data);
        }
       return response()->json(['msg'=>'success'],200);


    }

}
