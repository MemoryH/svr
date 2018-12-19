<?php
namespace Basesvr\SimpleVideo\Utils;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Log;

/**
 * Class ReturnJson
 * @package App\Utils
 */
class ReturnJson
{
    /**
     * 成功时的json返回
     *
     * @param array|LengthAwarePaginator $data 数据
     *
     * @return JsonResponse ;
     * @throws \Exception
     * @internal param int $totalPages 总页数
     * @internal param int $totalCount 总条数
     * @internal param int $page 当前分页
     * @internal param int $limit 当前页最大显示条数
     * @internal param int $count 当前页实际显示条数
     * @internal param bool $firstPage 是否第一页
     * @internal param bool $lastPage 是否最末页
     * @internal param bool $hasPrePage 是否有上一页
     * @internal param bool $hasNextPage 是否有下一页
     * @internal param int $prePage 值大于0，为上一页分页数，否则无上一页
     * @internal param int $nextPage 值大于0，为下一页分页数，否则无下一页
     */
    public static function paginate ($data)
    {
        try {
            if ($data instanceof LengthAwarePaginator) {
                $data = static::listData($data);
                $reData = [
                    'totalPages'  => $data['totalPages'],
                    'totalCount'  => $data['totalCount'],
                    'page'        => $data['page'],
                    'limit'       => $data['limit'],
                    'count'       => $data['count'],
                    'firstPage'   => $data['firstPage'],
                    'lastPage'    => $data['lastPage'],
                    'hasPrePage'  => $data['hasPrePage'],
                    'hasNextPage' => $data['hasNextPage'],
                    'prePage'     => $data['prePage'],
                    'nextPage'    => $data['nextPage'],
                    'items'       => $data['data'],
                ];
            } else {
                throw new \Exception('传入的对象不是LengthAwarePaginator的实例');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return response()->json($reData);
    }

    /**
     * 详情输出
     *
     * @param array|Collection|\stdClass|string|bool $data
     *
     * @param string                                 $message
     *
     * @return JsonResponse
     */
    public static function success($data = '', $message = '操作成功')
    {
        $reData = [
            'code'    => 0,
            'message' => $message,
            'result'  => $data,
        ];
        return response()->json($reData);
    }

    /**
     *
     * fail
     *
     * @param string|array $message
     * @param int          $status
     *
     * @return JsonResponse
     */
    public static function fail($message = 'Service Error', $status = 500)
    {
        $reData = [
            'code'     => $status,
            'messages' => $message,
        ];

        Log::error('Service Error', $reData);

        return response()->json($reData, $status);
    }

    /**
     * 处理list数据
     *
     * @param LengthAwarePaginator $list
     * @param int                  $page
     * @param int                  $size
     *
     * @return mixed
     */
    public static function listData($list, $page = 1, $size = 10)
    {
        $reData['totalPages'] = (int)$list->lastPage();
        $reData['totalCount'] = (int)$list->total();
        $reData['page'] = max((int)$list->currentPage(), 1);
        $reData['limit'] = (int)$size;
        $reData['count'] = (int)$list->count();

        if ($page == 1) {
            $reData['firstPage'] = true; //是否为首页
            $reData['hasPrePage'] = false; //是否有上一页
            $reData['prePage'] = 0; //上一页的页数
            if ($reData['totalPages'] == 1) {
                $reData['hasNextPage'] = false; //是否有下一页
                $reData['nextPage'] = 0;
            } else {
                $reData['hasNextPage'] = true; //是否有下一页
                $reData['nextPage'] = 2; //下一页的页数
            }
        } else {
            $reData['firstPage'] = false; //是否为首页
            if ($reData['totalPages'] == 1) {
                $reData['hasPrePage'] = false; //是否有上一页
                $reData['hasNextPage'] = false; //是否有下一页
                $reData['prePage'] = 1; //上一页的页数
                $reData['nextPage'] = 0; //下一页的页数
            } elseif ($page < $reData['totalPages']) {
                $reData['hasPrePage'] = true; //是否有上一页
                $reData['hasNextPage'] = true; //是否有下一页
                $reData['prePage'] = $page - 1; //上一页的页数
                $reData['nextPage'] = $page + 1; //下一页的页数
            } else {
                $reData['hasPrePage'] = true; //是否有上一页
                $reData['hasNextPage'] = false; //是否有下一页
                $reData['prePage'] = $page - 1; //上一页的页数
                $reData['nextPage'] = 0; //下一页的页数
            }
        }

        if ($page == $reData['totalPages']) {
            $reData['lastPage'] = true; //是否是最末页
        } else {
            $reData['lastPage'] = false; //是否是最末页
        }

        $reData['data'] = $list->items();

        return $reData;
    }
}