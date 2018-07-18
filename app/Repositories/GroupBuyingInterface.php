<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 14:24
 */

namespace App\Repositories;


interface GroupBuyingInterface
{
     public function find($id);
     public function create($arr);
     public function with(...$arr);
     public function where($where=[]);
     public function delete(...$id);
     public function findByWhere();
     public function getByWhere();
}