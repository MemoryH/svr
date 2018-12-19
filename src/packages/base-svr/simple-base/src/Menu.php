<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/4
 * Time: 10:20 AM
 */

namespace Basesvr\SimpleBase;




use App\Models\MenuModel;

class Menu
{

    protected $_model;
    public function __construct(MenuModel $menuModel)
    {
        $this->_model = $menuModel;
    }

    public function list(array $search){
        //to do 暂时一级分类
        return $this->_model->where($search)->get()->toArray();
    }
}