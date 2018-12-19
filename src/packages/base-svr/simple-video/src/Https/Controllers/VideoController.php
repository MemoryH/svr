<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 11:54
 */

namespace Basesvr\SimpleVideo\Https\Controllers;


use Basesvr\SimpleVideo\DictionaryOption;
use Basesvr\SimpleVideo\Https\Controllers\Traits\ValidateHandler;
use Basesvr\SimpleVideo\Utils\ReturnJson;
use Basesvr\SimpleVideo\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use ValidateHandler;

    protected $_videoService;


    public function __construct(Video $video)
    {
        $this->_videoService = $video;
    }

    /**
     * 获取视频列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request){
        $data = $request->all();
        $ruls = [
            'global_type' => ['required','string'],
            'dist_id'=>['int'],
            'epoch_id'=>['int'],
            'type_id'=>['int'],
            'page'=>['int'],
            'limit'=>['limit']
        ];
        $res = $this->validateField($data,$ruls);
        if (!empty($res)){
            return ReturnJson::fail($res);
        }
        if ($this->_videoService->checkGlobalType($data['global_type'])){
            $res =$this->_videoService->lists($data,['id','desc'],['*'],$data['page']??1,$data['limit']??10);
            return ReturnJson::success($res);
        }

        return ReturnJson::fail('所传视频分类不正确');

    }

    /**
     * 设置轮播图
     * @param Request $request
     */
    public function setBanner(Request $request){
        $data = $request->all();
        $rules = [
            'video_id'=>['required','int'],
            'key'=>['required','string'],
        ];
        $res = $this->validateField($data,$rules);
        if (!empty($res)){
            return ReturnJson::fail($res);
        }

        $results = $this->_videoService->bannerSet($data['video_id'],$data['key']);
        return ReturnJson::success($results);
    }

    /**
     * 获取轮播列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanner(Request $request){
        $data = $request->all();
        $rules = [
            'limit' =>['int'],
            'page'=>['int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $res = $this->_videoService->bannerGet($data['limit']??10, $data['page']??1);
        return ReturnJson::success($res);
    }

    /**
     * 编辑轮播访问量
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editBanner(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int'],
            'sort'=>['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $res = $this->_videoService->bannerEdit($data);
        return ReturnJson::success($res);
    }

    /**
     * 删除轮播项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delBanner(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int'],
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $res =$this->_videoService->bannerDel($data['id']);
        return ReturnJson::success($res);
    }

    public function getGlobalType(){
        return $this->_videoService->get('video','global_type');
    }

}