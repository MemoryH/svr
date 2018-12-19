<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 11:24
 */

namespace Basesvr\SimpleVideo;


use App\Models\DictionaryOptionModel;
use App\Models\RecommendModel;
use App\Models\VideoModel;
use Basesvr\SimpleVideo\Utils\ReturnJson;

class Video
{

    protected $_videoModel;
    protected $table;
    protected $_recommendTable;
    protected $_recommendModel;
    protected $_dictionaryModel;


    public function __construct(VideoModel $videoModel,RecommendModel $recommendModel,DictionaryOptionModel $dictionaryOptionModel)
    {
        $this->_videoModel =$videoModel;
        $this->table = $videoModel->getTable();
        $this->_recommendModel = $recommendModel;
        $this->_dictionaryModel = $dictionaryOptionModel;
        $this->_recommendTable = $recommendModel->getTable();
    }

    /**
     * 视频列表
     * @param array $search
     * @param array $orders
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function lists(array $search, array $orders = ['id','desc'], $columns = ['*'], int $page = 1, int $limit = 15)
    {

        $query = $this->_videoModel;

        if (!empty($search['global_type'])) $query = $query->where([$this->table.'.global_type' => $search['global_type']]);

        if (!empty($search['dist_id'])) $query = $query->where([$this->table.'.dist_id' => $search['dist_id']]);

        if (!empty($search['epoch_id'])) $query = $query->where([$this->table.'.epoch_id' => $search['epoch_id']]);

        if (!empty($search['type_id'])) {
            $query = $query->join("v_video_type as vt",$this->table.'.id','=','vt.video_id')
                ->where('vt.type_id',$search['type_id'])
                ->where('vt.global_type',$search['global_type'])
                ->select([$this->table.'.*','vt.type_id']);
        }

        if (is_array($orders[0])) foreach ($orders as $value) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($orders[0], $orders[1]);
        }

        return $query->paginate($limit, $columns, 'page', $page);
    }


    /**
     * 获取数据字典
     * @param $dictionary_table_code
     * @param $dictionary_code
     * @return mixed
     */
    public function get($dictionary_table_code,$dictionary_code){
        $res = $this->_dictionaryModel
            ->where(['dictionary_code'=>$dictionary_code,'dictionary_table_code'=>$dictionary_table_code])
            ->select(['value','name'])
            ->get()->toArray();
        return $res;
    }

    /**
     * 验证视频分类是否存在
     * @param $global_type
     * @return bool
     * @throws \Exception
     */
    public function checkGlobalType($global_type){
        $results =$this->get('video','global_type');
        $global_types = [];
        foreach ($results as $result){
            $global_types[] = $result['value'];
        }
        if (in_array($global_type,$global_types)){
            return true;
        }
        throw new \Exception('视频分类不存在');
    }

    /**
     * 设置轮播图
     * @param $video_id
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function bannerSet($video_id,$key){
        $query = $this->_recommendModel;

        if (!empty($query->where(['video_id'=>$video_id,'key'=>$key])->first())){
            throw new \Exception('Database Already exist');
        }
        $res= $this->_videoModel->getGlobalType($video_id);
        if ($res){
            $data = [];
            $data['video_id'] = $video_id;
            $data['key'] = $key;
            $data['global_type'] = $res['global_type'];
            $setRes = $query->insert($data);
            if ($setRes){
                return $setRes;
            }
            throw new \Exception('添加失败');
        }
        throw new \Exception('视频未找到');

    }


    /**
     * 获取轮播列表
     * @return mixed
     */
    public function bannerGet(int $limit = 10,int $page =1, array $orders = ['id','desc'],array $coulmns = ['*'],array $search = []){
        $query =  $this->_recommendModel
            ->where($search)
            ->join($this->table,$this->table.'.id','=',$this->_recommendTable.'.video_id')
            ->select($this->_recommendTable.'.*',$this->table.'.title');
        if (is_array($orders[0])) foreach ($orders as $value) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($orders[0], $orders[1]);
        }
        $results = $query->paginate($limit, $coulmns, 'page', $page);
        foreach ($results as $key => $result){
            if ($result['key'] =='ad_carousel_type'){
                $results[$key]['key'] = '分类轮播';
            }
            if ($result['key'] == 'ad_carousel'){
                $result[$key]['key'] = '推荐轮播';
            }
        }
        return $results;
    }


    /**
     * 更新轮播排序
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function bannerEdit($data){
        $id = $data['id'];
        $res = $this->_recommendModel->where('id',$id)->update(['sort'=>$data['sort']]);
        if ($res){
            return $res;
        }
        throw new \Exception('编辑失败');
    }

    public function bannerDel($id){

        $res = $this->_recommendModel->where('id',$id)->delete();

        if ($res){
            return $res;
        }
        throw new \Exception('请不要反复删除');
    }
}