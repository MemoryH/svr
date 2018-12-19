<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 11:18
 */

namespace App\Models;


class VideoModel extends BaseModel
{

    protected $connection = 'mysql_video';
    protected $table = 'v_video';


    /**
     * 验证分类是否存在
     * @param $video_id
     * @return mixed
     */
    public function getGlobalType($video_id){
        return self::where(['id'=>$video_id])->select('global_type')->first()->toArray();
    }
}