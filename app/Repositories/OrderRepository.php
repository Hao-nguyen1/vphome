<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class UserService
 * @package App\Services
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(
        Order $model
    ){
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
        // int $currentPage = 1,
        
    ){
        $query = $this->model->select($column);
        return $query  
                ->keyword($condition['keyword'] ?? null, ['fullname', 'phone', 'email', 'address', 'code'], ['field' => 'name', 'relation' => 'products'])
                ->publish($condition['publish'] ?? null)
                ->customDropdownFilter($condition['dropdown'] ?? null)
                ->relationCount($relations ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->customWhereRaw($rawQuery['whereRaw'] ?? null)
                ->customJoin($join ?? null)
                ->customGroupBy($extend['groupBy'] ?? null)
                ->customOrderBy($orderBy ?? null)
                ->customerCreatedAt($condition['created_at'] ?? null)
                // ->toSql();
                ->paginate($perPage)
                ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }


    public function getOrderById($id){
        return $this->model->select([
                'orders.*',
                'provinces.name as province_name',
                'districts.name as district_name',
                'wards.name as ward_name',
            ]
        )
        ->leftJoin('provinces', 'orders.province_id', '=','provinces.code')
        ->leftJoin('districts', 'orders.district_id', '=','districts.code')
        ->leftJoin('wards', 'orders.ward_id', '=','wards.code')
        ->with('products')
        ->find($id);
    }

    public function getOrderByTime($month, $year){
        return $this->model
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->count();
    }

    public function getTotalOrders(){
        return $this->model->count();
    }

    public function getCancleOrders(){
        return $this->model->where('confirm', '=', 'cancle')->count();
    }

    public function revenueOrders(){

        return $this->model
        ->join('order_product', 'order_product.order_id', '=', 'orders.id')
        ->where('orders.payment', '=', 'paid')
        ->sum(DB::raw('order_product.price * order_product.qty'));

    }


    public function revenueByYear($year){
        return $this->model->select(
            DB::raw('
                months.month, 
                COALESCE(SUM(JSON_UNQUOTE(JSON_EXTRACT(orders.cart, "$.cartTotal"))), 0) as monthly_revenue
            ')
        )
        ->from(DB::raw('(
            SELECT 1 AS month
                UNION SELECT 2
                UNION SELECT 3
                UNION SELECT 4
                UNION SELECT 5
                UNION SELECT 6
                UNION SELECT 7
                UNION SELECT 8
                UNION SELECT 9
                UNION SELECT 10
                UNION SELECT 11
                UNION SELECT 12
        ) as months'))
        ->leftJoin('orders', function($join) use ($year){
            $join->on(DB::raw('months.month'), '=', DB::raw('MONTH(orders.created_at)'))
            ->where('orders.payment', '=', 'paid')
            ->where(DB::raw('YEAR(orders.created_at)'), '=', $year);
        })
        ->groupBy('months.month')
        ->get();

    }

    public function revenue7Day(){
        return $this->model
        ->select(DB::raw('
            dates.date,
            COALESCE(SUM(JSON_UNQUOTE(JSON_EXTRACT(orders.cart, "$.cartTotal"))), 0) as daily_revenue
        '))
        ->from(DB::raw('(
            SELECT CURDATE() - INTERVAL (a.a + (10*b.a) + (100 * c.a)) DAY as date
            FROM (
                SELECT 0 AS a UNION ALL
                SELECT 1 UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9
            ) as a
            CROSS JOIN (
                SELECT 0 AS a UNION ALL 
                SELECT 1 UNION ALL 
                SELECT 2 UNION ALL 
                SELECT 3 UNION ALL 
                SELECT 4 UNION ALL 
                SELECT 5 UNION ALL 
                SELECT 6 UNION ALL 
                SELECT 7 UNION ALL 
                SELECT 8 UNION ALL 
                SELECT 9
            ) as b
            CROSS JOIN (
                SELECT 0 AS a UNION ALL 
                SELECT 1 UNION ALL 
                SELECT 2 UNION ALL 
                SELECT 3 UNION ALL 
                SELECT 4 UNION ALL 
                SELECT 5 UNION ALL 
                SELECT 6 UNION ALL 
                SELECT 7 UNION ALL 
                SELECT 8 UNION ALL 
                SELECT 9
            ) as c
        ) as dates'))
        
        ->leftJoin('orders', function($join){
            $join->on(DB::raw('DATE(orders.created_at)'), '=', DB::raw('dates.date'))
            ->where('orders.payment', '=', 'paid');
        })
        ->where(DB::raw('dates.date'), '>=', DB::raw('CURDATE() - INTERVAL 6 DAY'))
        ->groupBy(DB::raw('dates.date'))
        ->orderBy(DB::raw('dates.date'), 'ASC')
        ->get();
    }

    public function revenueCurrentMonth($currentMonth, $currentYear){
        return $this->model->select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('COALESCE(SUM(JSON_UNQUOTE(JSON_EXTRACT(orders.cart, "$.cartTotal"))), 0) as daily_revenue')
        )
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->groupBy('day')
        ->orderBy('day')
        ->get()->toArray();
    }

    public function orderByCustomer($customer_id = 0, $condition = []){
        $query = $this->model->select([
            'orders.*',
            'provinces.name as province_name',
            'districts.name as district_name',
            'wards.name as ward_name',
        ]
        )
        ->where('orders.customer_id',$customer_id)
        ->leftJoin('provinces', 'orders.province_id', '=','provinces.code')
        ->leftJoin('districts', 'orders.district_id', '=','districts.code')
        ->leftJoin('wards', 'orders.ward_id', '=','wards.code')
        ->with('products');
        if(isset($condition['keyword']) && !empty($condition['keyword'])) {
            $query->where(function ($query) use ($condition){
                $keyword = $condition['keyword'];
                $query->where('orders.code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('orders.fullname', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('orders.phone', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('orders.address', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query->paginate(20);
    }

    /*Filer Time */

    public function getReportTime($startDate, $endDate){
        return $this->model->select(
                DB::raw("DATE(created_at) as order_date"),
                DB::raw("COUNT(DISTINCT customer_id) as count_customer "),
                DB::raw("COUNT(DISTINCT orders.id) as count_order"),
                DB::raw("SUM(order_product.price * order_product.qty) as sum_revenue"),
                DB::raw("(SELECT SUM(JSON_UNQUOTE(JSON_EXTRACT(promotion, '$.discount'))) FROM orders WHERE DATE(created_at) = order_date GROUP BY DATE(created_at)) as sum_discount"),
            )
            ->join('order_product', 'order_product.order_id', '=', 'orders.id')
            ->whereDate('created_at','>=', $startDate)
            ->whereDate('created_at','<=', $endDate)
            ->where('orders.payment', '=', 'paid')
            ->groupBy('order_date')
            ->get()->toArray();
    }
    

    public function getProductReportTime($startDate, $endDate){
        return $this->model->select(
                DB::raw("IFNULL(product_variants.sku, products.code) as sku"),
                DB::raw("name as product_name"),
                DB::raw("COUNT(DISTINCT orders.customer_id) as count_customer"),
                DB::raw("COUNT(orders.id) as count_order"),
                DB::raw("SUM(order_product.price * order_product.qty) as sum_revenue"),
                DB::raw("(SELECT SUM(JSON_UNQUOTE(JSON_EXTRACT(promotion, '$.discount'))) FROM orders WHERE DATE(created_at) = DATE(orders.created_at)) as sum_discount")
            )
            ->join('order_product', 'order_product.order_id', '=', 'orders.id')
            ->leftJoin('product_variants', 'product_variants.uuid', '=', 'order_product.uuid')
            ->leftJoin('products', 'products.id', '=', 'order_product.product_id')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.payment', '=', 'paid')
            ->groupBy('order_product.product_id')
            ->get()->toArray();
    }

    public function getCustomerReportTime($startDate, $endDate){
        return $this->model->select(
                DB::raw("sources.name as source_name"),
                DB::raw("COUNT(DISTINCT orders.customer_id) as count_customer"),
                DB::raw("COUNT(orders.id) as count_order"),
                DB::raw("SUM(order_product.price * order_product.qty) as sum_revenue"),
                DB::raw("(SELECT SUM(JSON_UNQUOTE(JSON_EXTRACT(promotion, '$.discount')))) as sum_discount")
            )
            ->join('customers', 'customers.id', '=', 'orders.customer_id')
            ->join('order_product', 'order_product.order_id', '=', 'orders.id')
            ->leftJoin('sources', 'sources.id', '=', 'customers.source_id')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.payment', '=', 'paid')
            ->groupBy('sources.id')
            ->get()->toArray();
    }
    
    public function getTotalRevenueReportTime($startDate, $endDate){
        return $this->model->select(
                DB::raw("SUM(order_product.price * order_product.qty) as sum_revenue"),
                DB::raw("(SELECT SUM(JSON_UNQUOTE(JSON_EXTRACT(promotion, '$.discount')))) as sum_discount")
            )
            ->join('order_product', 'order_product.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.payment', '=', 'paid')
            ->get()
            ->toArray();
    }

    public function newOrder($startDate, $endDate){
        return $this->model
        ->whereDate('orders.created_at', '>=', $startDate)
        ->whereDate('orders.created_at', '<=', $endDate)
        ->get();
    }

}
