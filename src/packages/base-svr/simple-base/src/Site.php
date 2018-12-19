<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/4
 * Time: 10:20 AM
 */

namespace Basesvr\SimpleBase;




use App\Models\SiteModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class Site
{
    protected $_model;
    public function __construct(SiteModel $siteModel)
    {
        $this->_model = $siteModel;
    }

    public function index(array $search = [], int $limit = 20, array $orders = ['id','desc'], array $columns = ['*'], int $page = 1): LengthAwarePaginator
    {
        $query = $this->_model->where($search);
        if (is_array($orders[0])) foreach ($orders as $value) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($orders[0], $orders[1]);
        }

        return $query->paginate($limit, $columns, 'page', $page);
    }


}