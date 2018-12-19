<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 15:04
 */

namespace Basesvr\SimpleXiaoshuo;


use App\Models\SourceModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Source
{
    protected $_model;

    public function __construct(SourceModel $sourceModel)
    {
        $this->_model = $sourceModel;
    }

    public function index(array $search = [], int $limit = 20, array $orders = ['id','desc'], array $columns = ['*'], int $page = 1): LengthAwarePaginator
    {
        $query = $this->_model->where($search);
        if (is_array($orders[0])) foreach ($orders as $value) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($orders[0], $orders[1]);
        }
        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     * 添加源解析
     * @param array $array
     * @return mixed
     */
    public function sourceAdd(array $array){
        $source = new SourceModel();

        $data = [
            'domain'=>$array['domain'],
            'title'=>$array['title'],
            'client_regex'=>$array['client_regex'],
            'created_at'=>date('Y-m-d H:i:s',time())
        ];
        $res = $source->insert($data);

        if ($res){
            return $res;
        }
    }


    /**
     * 更新源解析
     * @param int $id
     * @param array $array
     * @return mixed
     */
    public function sourceUpdate(int $id,array $array){
        $data = [];
        if (!empty($array['title'])){
            $data['title'] = $array['title'];
        }
        if (!empty($array['domain'])){
            $data['domain'] = $array['domain'];
        }
        if (!empty($array['sort'])){
            $data['sort'] = $array['sort'];
        }
        if (!empty($array['client_regex'])){
            $data['client_regex'] = $array['client_regex'];
        }
        $res = $this->_model->where('id',$id)->update($data);
        if ($res){
            return $res;
        }
    }

    public function sourceDel(int $id){
        $res = $this->_model->where('id',$id)->delete();
        if ($res){
            return $res;
        }
    }
}