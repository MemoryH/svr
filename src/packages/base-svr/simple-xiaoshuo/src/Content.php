<?php
namespace Basesvr\SimpleXiaoshuo;

use App\Models\ContentModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;



class Content{

    protected $_model;
    protected $table;
    public function __construct(ContentModel $contentModel)
    {
        $this->_model = $contentModel;
        $this->table = $contentModel->getTable();
    }

    public function index(array $search = [],string $search_vague, int $limit = 20, array $orders = ['id','desc'], array $columns = ['*'], int $page = 1): LengthAwarePaginator
    {
        $query = $this->_model
            ->where($search)
            ->where($this->table.'.title','like','%'.$search_vague.'%')
            ->join('xs_category',$this->table.'.category_id','=','xs_category.id')
            ->select($this->table.'.*','xs_category.title as category');
        if (is_array($orders[0])) foreach ($orders as $value) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($orders[0], $orders[1]);
        }
        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     * 小说内容排序
     * @param array $array
     * @param int $page
     * @param int $limit
     * @return bool
     */
    public function sortIndex(array $search = [],string $search_vague,array $array = [],int $page = 1,int $limit = 20){
        $data = [];
        if (!empty($array['visit']) && $array['visit'] =='visit'){
            if ($array['sort_order'] == 1){
                $data = ['visit','asc'];
            }else{
                $data = ['visit','desc'];
            }
        }
        if (!empty($array['visit_real']) && $array['visit_real'] =='visit_real'){
            if ($array['sort_order'] == 1){
                $data = ['visit_real','asc'];
            }else{
                $data = ['visit_real','desc'];
            }
        }
        if (!empty($array['exponent_bd']) && $array['exponent_bd'] =='exponent_bd'){
            if ($array['sort_order'] == 1){
                $data = ['exponent_bd','asc'];
            }else{
                $data = ['exponent_bd','desc'];
            }
        }
        if (empty($data)){
            return false;
        }
        $query = $this->_model
            ->where($search)
            ->where($this->table.'.title','like','%'.$search_vague.'%')
            ->join('xs_category',$this->table.'.category_id','=','xs_category.id')
            ->select($this->table.'.*','xs_category.title as category');

        if (is_array($data[0])) foreach ($data as $value) {

            $res = $query->orderBy($value[0], $value[1]);
        } else {
            $res = $query->orderBy($data[0], $data[1]);
        }

        $columns = ['*'];
        return $query->paginate($limit, $columns, 'page', $page);

    }

    /**
     * 小说热度更新
     * @param $data
     * @return mixed
     */
    public function updateHot($data){
        $id = $data['id'];
        $visit = $data['visit'];
        $res = $this->_model->where('id',$id)->update(['visit'=>$visit]);
        if ($res){
            return $res;
        }
    }


}