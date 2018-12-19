<?php
namespace Basesvr\SimpleBase\Https\Controllers;




use Basesvr\SimpleBase\Https\Controllers\Traits\ValidateHandler;
use Basesvr\SimpleBase\Menu;
use Basesvr\SimpleBase\Utils\ReturnJson;
use Illuminate\Http\Request;

class MenuController extends Controller
{


    protected $_menuService;

    use ValidateHandler;

    /**
     * Menu constructor.
     * @param Menu $menuService
     */
    public function __construct(Menu $menuService)
    {
        $this->_menuService = $menuService;
    }

    /**
     * 获取菜单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenuList(Request $request)
    {
        $data = $request->all();
        $rules = [
            'system' => ['required', 'string']
        ];
        $errors = $this->validateField($data, $rules);
        if (!empty($errors)) {
            return ReturnJson::fail($errors);
        }

        $res = $this->_menuService->list(['system' => $data['system']]);
        return ReturnJson::success($res);
    }
}