<?php
namespace Basesvr\SimpleXiaoshuo;

use App\Models\Mongo\ChapterModel;

class Chapter
{
    protected $_model;
    public function __construct(ChapterModel $chapterModel)
    {
        $this->_model = $chapterModel;
    }


    /**
     * 获取章节目录
     * @param int $content_id
     * @return bool
     */
    public function Chapter(int $content_id,int $limit = 15,$columns=['*'],int $page=1){
        $res = $this->_model->where('content_id',$content_id)->orderBy('sort','asc')->paginate($limit, $columns, 'page', $page) ;
        if ($res){
            return $res;
        }
        return false;
    }

    public function chapterContent(string $id,int $content_id){
        $res = $this->_model->where(['_id'=>$id,'content_id'=>$content_id])->first(['content']);
        if ($res){
            return $res;
        }
        return false;
    }
}