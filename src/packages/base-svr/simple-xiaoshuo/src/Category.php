<?php
namespace Basesvr\SimpleXiaoshuo;

use App\Models\CategoryModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Category {
    protected $_model;
    public function __construct(CategoryModel $categoryModel)
    {
        $this->_model = $categoryModel;
    }

    /**
     * 获取小说分类列表
     * @return mixed
     */
    public function index(){
        $data = $this->_model->get()->toArray();
        foreach ($data as $key=>$v){
            if ($v['display'] ==1){
                $data[$key]['display'] = '是';
            }else{
                $data[$key]['display'] = '否';
            }
        }
        return $data;
    }

    /**
     * 增加小说分类
     * @param array $array
     * @return mixed
     */
    public function addCategory (array $array=[])
    {
        $category =  new CategoryModel();
        $data = [
            'title' => $array['title'],
            'display' => $array['display'],
            'created_at' => date('Y-m-d H:i:s',time())
        ];

        $query = $category->insert($data);
        return $query;
    }

    /**
     * 更新小说分类
     * @param $id
     * @param $array
     * @return mixed
     */
    public function updateCategory($id,$array){
        $res = $this->_model->where('id',$id)->update($array);
        if ($res){
            return $res;
        }
    }

    /**
     * 删除小说分类
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id){
        $res = $this->_model->where('id',$id)->delete();
        if ($res){
            return $res;
        }
    }

}