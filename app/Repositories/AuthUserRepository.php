<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 15:50
 */

namespace App\Repositories;


use App\UserAuth;

class AuthUserRepository implements AuthUserInterface
{
    protected $where=[];
    protected $with=[];
    protected $model=UserAuth::class;
    public function find($id)
    {
        if ($this->with){
            return $this->model::with($this->with)->find($id);
        }
        return $this->model::find($id);
    }
    public function where($where = [])
    {
        $this->where=$where;
        return $this;
    }
    public function delete(...$id)
    {
        return $this->model::destroy($id);
    }
    public function create($arr)
    {
        return $this->model::create($arr);
    }
    public function with($arr)
    {
        $this->with=$arr;
        return $this;
    }
    public function findByWhere()
    {
        if ($this->where){
            if ($this->with){
                return $this->model::with($this->with)->where($this->where)->first();
            }else{
                return $this->model::where($this->where)->first();
            }
        }else{
            throw new \Mockery\Exception("参数错误");
        }
    }
    public function getByWhere()
    {
        if (!$this->where){
            if (!$this->with){
                return $this->model::with($this->with)->where($this->where)->get();
            }else{
                return $this->model::where($this->where)->get();
            }
        }else{
            throw new \Mockery\Exception("参数错误");
        }
    }
    public function getAll()
    {
        return $this->model::all();
    }
    public function paginate($item)
    {
        if ($this->with){
            $with=$this->with;
            return $this->model::with($with)->paginate();
        }
        return $this->model::paginate($item);
    }
}