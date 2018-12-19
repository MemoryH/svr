<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 15:01
 */

namespace Basesvr\SimpleXiaoshuo\Https\Controllers;


use Basesvr\SimpleXiaoshuo\Https\Controllers\Traits\ValidateHandler;
use Basesvr\SimpleXiaoshuo\Source;
use Basesvr\SimpleXiaoshuo\Utils\ReturnJson;
use Illuminate\Http\Request;

class SourceController extends Controller
{

    protected $_sourceService;

    use ValidateHandler;

    public function __construct(Source $source)
    {
        $this->_sourceService = $source;
    }

    /**
     * 获取源解析列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSourceList(Request $request){
        $data = $request->all();
        $rules = [
            'limit' => ['int'],
            'page'=>['int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $data = $this->_sourceService->index(
            [],
            $data['limit'] ?? 0,
            ['sort', 'asc'],
            ['*'],
            $data['page'] ?? 0
        )->toArray();
        return ReturnJson::success($data);
    }

    /**
     * 添加源解析规则
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSource(Request $request){
        $data = $request->all();
        $rules = [
            'domain' => ['required','string'],
            'title'=>['required','string'],
            'client_regex'=>['required']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $res = $this->_sourceService->sourceAdd($data);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('添加失败');
    }

    /**
     * 更新源解析
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSource(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int'],
            'domain' => ['string'],
            'title'=>['string'],
            'sort'=>['int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $id = $data['id'];
        $res =  $this->_sourceService->sourceUpdate($id,$data);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('更新失败');

    }

    public function delSource(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int'],
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $id = $data['id'];
        $res = $this->_sourceService->sourceDel($id);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('删除失败,检查数据是否存在');

    }





}