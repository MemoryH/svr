<?php
namespace Basesvr\SimpleXiaoshuo\Https\Controllers;



use Basesvr\SimpleXiaoshuo\Chapter;
use Basesvr\SimpleXiaoshuo\Content;
use Basesvr\SimpleXiaoshuo\Https\Controllers\Traits\ValidateHandler;
use Basesvr\SimpleXiaoshuo\Utils\ReturnJson;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
class ContentController extends Controller{

    protected $_contentService;
    protected $_chapterService;

    use ValidateHandler;

    public function __construct(Content $content,Chapter $chapter)
    {
        $this->_contentService =$content;
        $this->_chapterService =$chapter;
    }

    /**
     * 获取小说内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContent(Request $request){

        $data = $request->all();
        $rules = [
            'limit' => ['int'],
            'page'=>['int'],
            'category_id'=>['int'],
            'title'=>['string']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $search = [];
        $search_vague='';
        if (!empty($data['category_id'])){
            $search['category_id'] = $data['category_id'];
        }
        if (!empty($data['title'])){
            $search_vague = $data['title'];
        }
        $data = $this->_contentService->index(
            $search,
            $search_vague,
            $data['limit'] ?? 30,
            ['visit', 'desc'],
            ['*'],
            $data['page'] ?? 1
        )->toArray();
        return ReturnJson::success($data);
    }

    /**
     * 获取小说章节目录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChapterList(Request $request){
        $data = $request->all();
        $rules = [
            'content_id'=>['required','int'],
            'limit' =>['int'],
            'page'=>['int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $content_id = $data['content_id'];
        $data = $this->_chapterService->Chapter($content_id,$data['limit']??15,['*'],$data['page']??1);
        if ($data){
            return ReturnJson::success($data);
        }
        return ReturnJson::fail('未找到小说章节',400);

    }

    /**
     * 获取小说章节对应内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChapterContent(Request $request){
        $data = $request->all();
        $rules = [
            '_id' => ['required','string'],
            'content_id'=>['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $data = $this->_chapterService->chapterContent($data['_id'],$data['content_id']);
        if ($data){
            return ReturnJson::success($data);
        }
        return ReturnJson::fail('未找到小说内容',400);

    }

    /**
     * 小说热度更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateContentHot(Request $request){
        $data = $request->all();
        $rules = [
            'id' => ['required','int'],
            'visit'=>['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $res = $this->_contentService->updateHot($data);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('更新失败');
    }

    /**
     * 小说内容排序
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request){
        $data = $request->all();
        $rules = [
            'visit' => ['string'],
            'visit_real'=>['string'],
            'exponent_bd'=>['string'],
            'sort_order'=>['required','int'],
            'page'=>['required','int'],
            'limit'=>['required','int'],
            'category_id'=>['int'],
            'title'=>['string']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)){
            return ReturnJson::fail($errors);
        }
        $page = $data['page'];
        $limit = $data['limit'];
        $search = [];
        $search_vague='';
        if (!empty($data['category_id'])){
            $search['category_id'] = $data['category_id'];
        }
        if (!empty($data['title'])){
            $search_vague = $data['title'];
        }
        $res = $this->_contentService->sortIndex($search,$search_vague,$data,$page,$limit);
        if ($res){
            return ReturnJson::success($res);
        }
        return ReturnJson::fail('操作失败');

    }

    public function erweima(){
        $content = 'http://www.baidu.com?rand=' . rand(1000, 9999);
        $qrCode = new QrCode($content);
// 指定内容类型
        header('Content-Type: '.$qrCode->getContentType());
// 输出二维码
        echo $qrCode->writeString();
    }


}
