<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 15:50
 */

namespace App\Repositories;


use App\GroupBuying;

class GroupBuyingRepository implements GroupBuyingInterface
{
    protected $where;
    protected $with;
    public function find($id)
    {
      return GroupBuying::find($id);
    }
    public function where($where = [])
    {
        $this->where=$where;
        return $this;
    }
    public function delete(...$id)
    {
        return GroupBuying::destroy($id);
    }
    public function create($arr)
    {
        return GroupBuying::create($arr);
    }
    public function with(...$arr)
    {
        $this->with=$arr;
        return $this;
    }
    public function findByWhere()
    {
        if (!$this->where){
            if (!$this->with){
                return GroupBuying::with($this->with())->where($this->where())->first();
            }else{
                return GroupBuying::where($this->where())->first();
            }
        }else{
            throw new \Mockery\Exception("参数错误");
        }
    }
    public function getByWhere()
    {
        if (!$this->where){
            if (!$this->with){
                return GroupBuying::with($this->with())->where($this->where())->get();
            }else{
                return GroupBuying::where($this->where())->get();
            }
        }else{
            throw new \Mockery\Exception("参数错误");
        }
    }
}