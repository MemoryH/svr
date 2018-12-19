<?php
namespace Basesvr\SimpleXiaoshuo\Https\Controllers;




use Basesvr\SimpleXiaoshuo\Https\Controllers\Traits\ValidateHandler;

use Basesvr\SimpleXiaoshuo\Category;

use Basesvr\SimpleXiaoshuo\Utils\ReturnJson;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $_CategoryService;

    use ValidateHandler;


    public function __construct(Category $category)
    {
        $this->_CategoryService = $category;
    }


    /**
     * 获取小说分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryList()
    {
        $data = $this->_CategoryService->index();
        return ReturnJson::success($data);
    }


    /**
     * 添加小说分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryAdd(Request $request){
        $data = $request->all();
        $rules = [
            'title'=>['required','string'],
            'display'=>['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $array['title']=$request->input('title');
        $array['display']=$request->input('display');
        $res = $this->_CategoryService->addCategory($array);
        return ReturnJson::success($res);
    }

    /**
     * 更新分类数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryUpdate(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int'],
            'title'=>['string'],
            'display'=>['int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $array = [];
        if (!empty($request->input('title'))){
            $array['title'] = $request->input('title');
        }
        if (!empty($request->input('display')) ||$request->input('display')==0 ){
            $array['display'] = $request->input('display');
        }
        $res = $this->_CategoryService->updateCategory($data['id'],$array);
        if ($res){
            return ReturnJson::success($res);
        }


    }

    /**
     * 删除分类数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryDel(Request $request){
        $data = $request->all();
        $rules = [
            'id'=>['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $res = $this->_CategoryService->deleteCategory($data['id']);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('未找到该分类',400);
    }
}