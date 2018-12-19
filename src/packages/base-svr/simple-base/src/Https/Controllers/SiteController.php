<?php
namespace Basesvr\SimpleBase\Https\Controllers;





use Basesvr\SimpleBase\Https\Controllers\Traits\ValidateHandler;
use Basesvr\SimpleBase\Site;
use Basesvr\SimpleBase\Utils\ReturnJson;
use Illuminate\Http\Request;

class SiteController extends Controller
{


    protected $_siteService;

    use ValidateHandler;
    /**
     * Site constructor.
     * @param Site $siteService
     */
    public function __construct(Site $siteService)
    {
        $this->_siteService = $siteService;
    }

    /**
     * 获取站点列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSiteList(Request $request)
    {
        $data = $request->all();
        $rules = [
            'limit' => ['required','int'],
            'page' => ['required','int']
        ];
        $errors = $this->validateField($data,$rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }
        $data = $this->_siteService->index(
            [],
            $data['limit'] ?? 0,
            ['id', 'desc'],
            ['*'],
            $data['page'] ?? 0
        )->toArray();

        return ReturnJson::success($data);
    }
}