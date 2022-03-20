<?php

namespace App\Repositories;

use DB, Mail;
use App\Models\Order\Order;
use App\Services\Facades\Shopee as ShopeeService;
use App\Models\Product\ProductPlatform;

class Shopee {
  function unpublish($product) {
    if($product->store->platform('shopee') && $product->platform('shopee')) {
      ShopeeService::listItem((int)$product->store->platform('shopee')->platform_store_id, array(
          "item_list" => array(
              array(
                  "item_id" => (int) $product->platform('shopee')->platform_product_id,
                  "unlist" => true
              )
          )
      ));
    }
  }

  function publish($product) {
    if($product->store->platform('shopee') && $product->platform('shopee')) {
      ShopeeService::listItem((int)$product->store->platform('shopee')->platform_store_id, array(
          "item_list" => array(
              array(
                  "item_id" => (int) $product->platform('shopee')->platform_product_id,
                  "unlist" => false
              )
          )
      ));
    }
  }

  function updateStock($product) {
    if($product->store->platform('shopee') && $product->platform('shopee')) {
      $shop_id = (int) $product->store->platform('shopee')->platform_store_id;
      if($product->skus->count()>1) {
          $models = ShopeeService::getModel($shop_id, (int) $product->platform('shopee')->platform_product_id);

          if($models['error']== "") {
              $stockList = array();
              foreach($models['response']['model'] as $model) {
                  $sku = $product->skus->where('sku_code', $model['model_sku'])->first();
                  $stockList[] = array(
                      "model_id" => $model['model_id'],
                      "normal_stock" => $sku->stock($product)
                  );
              }

              ShopeeService::updatestock($shop_id, array(
                  "item_id" => (int) $product->platform('shopee')->platform_product_id,
                  "stock_list" => $stockList
              ));
          }
      }else {
          ShopeeService::updatestock($shop_id, array(
              "item_id" => (int) $product->platform('shopee')->platform_product_id,
              "stock_list" => array(array(
                  "model_id" => 0,
                  "normal_stock" => $sku->stock($product)
              ))
          ));
      }
    }
  }

  function delete($product) {
    if($product->store->platform('shopee') && $product->platform('shopee')) {
      ShopeeService::deleteProduct((int)$product->store->platform('shopee')->platform_store_id, array(
        "item_id" => (int) $product->platform('shopee')->platform_product_id
      ));
      ProductPlatform::where('platform_product_id', (int) $product->platform('shopee')->platform_product_id)->delete();
    }
  }

  function create($product) {
    if($product->store->platform('shopee')) {
      $shop_id = (int) $product->store->platform('shopee')->platform_store_id;
      $sku = $product->firstsku();
      $images = array();
      foreach($product->images as $image) {
        // $resp = Storage::url('public/products/'.$image->image); #pake ini?
        $resp = ShopeeService::uploadImage("https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg"); #perlu di ganti image product
        if($resp['message'] == "success") {
            $images[] = $resp['response']['image_info']['image_id'];
        }
      }
      $data = array(
          "item_name" => $product->title,
          "description" => $product->description,
          'category_id' => (int)$product->masterproduct->shopee_category_id,
          'original_price' => $sku->price,
          'normal_stock' => $sku->stock($product),
          "image" => array(
              "image_id_list" => $images
          ),
          "item_sku" => $sku->sku_code,
          "dimension" => array(
              "package_height" => $sku->height,
              "package_length" => $sku->length,
              "package_width" => $sku->width,
          ),
          "logistic_info" => array(
              array(
                  "logistic_id" => 80014,
                  "enabled" => true
              ),
              array(
                  "logistic_id" => 80005,
                  "enabled" => true
              )
          ),
          "weight" => round($sku->weight/1000,2),
          'brand' => array(
              "brand_id" => 0,
              "original_brand_name" => $product->store->storename
          ),
          "pre_order" => array(
              "is_pre_order" => true,
              "days_to_ship" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time
          ),
      );

      $response = ShopeeService::createProduct($shop_id, $data);
      if(isset($response['response']['item_id'])) {
        ProductPlatform::create(array(
          'product_id' => $product->id,
          'platform' => 'shopee',
          'platform_product_id' => $response['response']['item_id']
        ));

        $variant = array("item_id" => $response['response']['item_id']);
        if($product->skus->count()>1) {
            $selection = array();
            $tier_variation = array();
            foreach($product->options as $option) {
                $options = array();
                $option_list = array();
                foreach($option->details as $detail) {
                    $options[] = array(
                        'value' => $detail->title,
                    );
                    $option_list[] = array(
                        'option' => $detail->title
                    );
                    
                }
                $selection[] = array(
                    'name' => $option->title,
                    'options' => $options
                );

                $tier_variation[] = array(
                    "name" => $option->title,
                    "option_list" => $option_list
                );
            }

            $model = array();
            foreach($product->skus as $i => $sku) {
                $model[] = array(
                    "original_price" => $sku->price,
                    "model_sku" => $sku->sku_code,
                    "normal_stock" => $sku->stock($product),
                    "tier_index" => $sku->getCombinationVariant($selection)
                );
            }

            $variant['tier_variation'] = $tier_variation;
            $variant['model'] = $model;

            ShopeeService::createVariant($shop_id, $variant);
        }
      }
    }
  }

  function update($product) {
    if($product->store->platform('shopee') && $product->platform('shopee')) {
      $sku = $product->firstsku();
      $shop_id = (int) $product->store->platform('shopee')->platform_store_id;
      $images = array();
      foreach($product->images as $image) {
          // $resp = Storage::url('public/products/'.$image->image); #pake ini?
          $resp = ShopeeService::uploadImage("https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg"); #perlu di ganti image product
          if($resp['message'] == "success") {
              $images[] = $resp['response']['image_info']['image_id'];
          }
      }
      $data = array(
          "item_id" => (int) $product->platform('shopee')->platform_product_id,
          "item_name" => $product->title,
          "description" => $product->description,
          'category_id' => (int)$product->masterproduct->shopee_category_id,
          'original_price' => $sku->price,
          'normal_stock' => $sku->stock($product),
          "image" => array(
              "image_id_list" => $images
          ),
          "item_sku" => $sku->sku_code,
          "dimension" => array(
              "package_height" => $sku->height,
              "package_length" => $sku->length,
              "package_width" => $sku->width,
          ),
          "logistic_info" => array(
              array(
                  "logistic_id" => 80014,
                  "enabled" => true
              ),
              array(
                  "logistic_id" => 80005,
                  "enabled" => true
              )
          ),
          "weight" => round($sku->weight/1000,2),
          'brand' => array(
              "brand_id" => 0,
              "original_brand_name" => $product->store->storename
          ),
          "pre_order" => array(
              "is_pre_order" => true,
              "days_to_ship" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time
          ),
      );

      ShopeeService::updateProduct($shop_id, $data);
      
      $variant = array("item_id" => (int) $product->platform('shopee')->platform_product_id);
      if($product->skus->count()>1) {
          $selection = array();
          $tier_variation = array();
          foreach($product->options as $option) {
              $options = array();
              $option_list = array();
              foreach($option->details as $detail) {
                  $options[] = array(
                      'value' => $detail->title,
                  );
                  $option_list[] = array(
                      'option' => $detail->title
                  );
                  
              }
              $selection[] = array(
                  'name' => $option->title,
                  'options' => $options
              );

              $tier_variation[] = array(
                  "name" => $option->title,
                  "option_list" => $option_list
              );
          }

          $variant['tier_variation'] = $tier_variation;
          ShopeeService::updateVariant($shop_id, $variant);

          $models = ShopeeService::getModel((int) $product->store->platform('shopee')->platform_store_id, (int) $product->platform('shopee')->platform_product_id);

          if($models['error']== "") {
              $priceList = array();
              $stockList = array();
              foreach($models['response']['model'] as $model) {
                  $sku = $product->skus->where('sku_code', $model['model_sku'])->first();

                  $priceList[] = array(
                      "model_id" => $model['model_id'],
                      "original_price" => $sku->price
                  );

                  $stockList[] = array(
                      "model_id" => $model['model_id'],
                      "normal_stock" => $sku->stock($product)
                  );
              }

              ShopeeService::updatePrice($shop_id, array(
                  "item_id" => (int) $product->platform('shopee')->platform_product_id,
                  "price_list" => $priceList
              ));

              ShopeeService::updatestock($shop_id, array(
                  "item_id" => (int) $product->platform('shopee')->platform_product_id,
                  "stock_list" => $stockList
              ));
          }
      }else {
          ShopeeService::updatePrice($shop_id, array(
              "item_id" => (int) $product->platform('shopee')->platform_product_id,
              "price_list" => array(array(
                  "model_id" => 0,
                  "original_price" => $sku->price
              ))
          ));

          ShopeeService::updatestock($shop_id, array(
              "item_id" => (int) $product->platform('shopee')->platform_product_id,
              "stock_list" => array(array(
                  "model_id" => 0,
                  "normal_stock" => $sku->stock($product)
              ))
          ));
      }
    }else {
      $this->create($product);
    }
  }
}