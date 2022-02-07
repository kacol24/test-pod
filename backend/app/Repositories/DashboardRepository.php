<?php
namespace App\Repositories;

use DB, Cookie, Google, Cache, Session, Product;
use App\Models\Dashboard\PageView;
use App\Models\Dashboard\PageViewCounter;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Repositories\Facades\ProductRepository;

class DashboardRepository {
  public function getTotalViewProduct($start_date, $end_date, $brand_id = null) {
    if($brand_id) {
      $view = PageViewCounter::join('products','products.id','=','page_view_counters.product_id')->rangeDate($start_date,$end_date)->where('product_id','>',0)->where('products.brand_id', $brand_id)->where('page_view_counters.store_id', session('store')->id)->select(DB::raw('SUM(total_view) as total_view'))->first()->total_view;
        if(!$view)
          $view = 0;
        return $view;  
    }else {
      if(!session('store')->ga_view_id) {
        $view = PageViewCounter::rangeDate($start_date,$end_date)->where('product_id','>',0)->where('store_id', session('store')->id)->select(DB::raw('SUM(total_view) as total_view'))->first()->total_view;
        if(!$view)
          $view = 0;
        return $view;  
      }else {
        $results = Google::getTotalViewProduct($start_date, $end_date);
        $total = 0;
        if(isset($results['pageviews'])) {
          foreach($results['pageviews'] as $view) {
            $total += $view;
          }  
        }
        return $total;
      }
    }
  }

  public function getVisitor($start_date, $end_date, $brand_id = null) {
    if($brand_id) {
      return PageView::join('products','products.id','=','page_views.product_id')->rangeDate($start_date,$end_date)->where('page_views.store_id', session('store')->id)->where('products.brand_id', $brand_id)->select(DB::raw('count(DISTINCT guest_token) as total_visitor'))->first()->total_visitor;
    }else {
      if(!session('store')->ga_view_id) {
        return PageView::rangeDate($start_date,$end_date)->where('store_id', session('store')->id)->select(DB::raw('count(DISTINCT guest_token) as total_visitor'))->first()->total_visitor;
      }else {
        return Google::getUser($start_date, $end_date);
      }
    }
    
  }

  public function getProductViewLogs($start_date,$end_date, $brand_id = null) {
    $results = array();
    if($brand_id) {
      $datas = PageViewCounter::join('products','products.id','=','page_view_counters.product_id')->rangeDate($start_date,$end_date)->where('page_view_counters.store_id', session('store')->id)->where('products.brand_id', $brand_id)->where('product_id','>',0)->select(DB::raw('sum(total_view) as total_view, DATE_FORMAT(page_view_counters.created_at, "%Y-%m-%d") as created_at'))->groupBy(DB::raw('DATE_FORMAT(page_view_counters.created_at, "%Y-%m-%d")'))->get();
      foreach($datas as $data) {
        $results[$data->created_at] = $data->total_view;  
      }
    }else {
      if(!session('store')->ga_view_id) {
        $datas = PageViewCounter::rangeDate($start_date,$end_date)->where('store_id', session('store')->id)->where('product_id','>',0)->select(DB::raw('sum(total_view) as total_view, DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))->get();
        foreach($datas as $data) {
          $results[$data->created_at] = $data->total_view;  
        }
      }else {
        $datas = Google::getProductView($start_date, $end_date, "ga:date", "ASCENDING");
        $results = array();
        if(isset($datas['ga:date'])) {
          foreach($datas['ga:date'] as $i => $date) {
            if(isset($result[date("Y-m-d",strtotime($date))])) {
              $results[date("Y-m-d",strtotime($date))] += $datas['pageviews'][$i];
            }else {
              $results[date("Y-m-d",strtotime($date))] = $datas['pageviews'][$i];
            }
          }
        }
      }  
    }
    return $results;
  }

  public function getBestSeller($start_date, $end_date, $limit=5, $brand_id = null) {
    $products = Product::leftJoin(DB::raw("(SELECT SUM(quantity) as quantity,product_id FROM order_details JOIN orders ON orders.id = order_details.order_id where status_id in (2,3,4) and date(DATE_FORMAT(orders.created_at, '%Y-%m-%d')) >= '".$start_date."' and date(DATE_FORMAT(orders.created_at, '%Y-%m-%d')) <= '".$end_date."' group by product_id 
) orders"), function($join)
        {
            $join->on('products.id', '=', 'orders.product_id');
        })->where('is_publish',1)->where('store_id', session('store')->id)->whereNull('deleted_at');
    if($brand_id) {
      $products = $products->where('products.brand_id', $brand_id);
    }

    return $products->orderBy('orders.quantity', 'DESC')->paginate($limit);
  }

  public function getMostViewer($start,$end, $brand_id = null) {
    $results = array();
    if($brand_id) {
      $datas = Product::leftJoin(DB::raw("(SELECT SUM(total_view) as total_view,product_id FROM page_view_counters where DATE_FORMAT(created_at, '%Y-%m-%d') >= '".$start."' and DATE_FORMAT(created_at, '%Y-%m-%d') <= '".$end."' group by product_id ) views"), function($join)
        {
            $join->on('products.id', '=', 'views.product_id');
        })->where('is_publish',1)->where('store_id', session('store')->id)->whereNull('deleted_at');

      if($brand_id) {
        $datas = $datas->where('products.brand_id', $brand_id);
      }

      $datas = $datas->orderBy('views.total_view', 'DESC')->paginate(5);
        foreach($datas as $data) {
          $results[] = array("product" => $data, "view" => $data->total_view);  
        }
    }else {
      if(!session('store')->ga_view_id) {
        $datas = Product::leftJoin(DB::raw("(SELECT SUM(total_view) as total_view,product_id FROM page_view_counters where DATE_FORMAT(created_at, '%Y-%m-%d') >= '".$start."' and DATE_FORMAT(created_at, '%Y-%m-%d') <= '".$end."' group by product_id ) views"), function($join)
        {
            $join->on('products.id', '=', 'views.product_id');
        })->where('is_publish',1)->where('store_id', session('store')->id)->whereNull('deleted_at')->orderBy('views.total_view', 'DESC')->take(5)->get();

        foreach($datas as $data) {
          $results[] = array("product" => $data, "view" => $data->total_view);  
        }
      }else {
        $datas = Google::getMostProductView($start, $end);
        if(isset($datas['ga:pagePath'])) {
          foreach($datas['ga:pagePath'] as $i => $data)  {
            $temp = explode("/", $data);
            $slug = $temp[count($temp)-1];
            $key = 'product_detail_'.$slug.config('app.locale').session('store')->currency.session('store')->id.app_key();
            $product = Cache::tags(['store'.session('store')->id.app_key(), 'products'.session('store')->id.app_key()])->rememberForever($key, function() use ($slug){
              return ProductRepository::getBySlug($slug);
            });
            if($product) {
              $results[] = array("product" => $product, "view" => $datas['pageviews'][$i]);  
            }
          }
        }
      }
    }
      
    return $results;
  }

  public function getTransactionByStatus($start_date,$end_date) {
    return OrderStatus::leftJoin(DB::raw("(SELECT count(id) as transactions,status_id FROM orders where DATE_FORMAT(created_at, '%Y-%m-%d') >= '".$start_date."' and DATE_FORMAT(created_at, '%Y-%m-%d') <= '".$end_date."' and store_id='".session('store')->id."' and deleted_at is null group by status_id ) orders"), function($join)
        {
            $join->on('order_status.id', '=', 'orders.status_id');
        })->orderBy('order_status.id', 'ASC')->get();
  }
}