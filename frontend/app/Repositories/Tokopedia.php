<?php

namespace App\Repositories;

use DB, Mail;
use App\Models\Order\Order;
use App\Services\Facades\Tokopedia as TokopediaService;
use App\Models\Product\ProductPlatform;

class Tokopedia {
  function unpublish($product) {
    if($product->store->platform('tokopedia') && $product->platform('tokopedia')) {
      TokopediaService::setInactiveProduct(array(
          'product_id' => array((int) $product->platform('tokopedia')->platform_product_id)
      ), (int)$product->store->platform('tokopedia')->platform_store_id);
    }
  }

  function publish($product) {
    if($product->store->platform('tokopedia') && $product->platform('tokopedia')) {
      if($product->skus->count()>1) {
          $platform_product = TokopediaService::getProduct((int)$product->platform('tokopedia')->platform_product_id);
          $product_id = array();
          foreach($platform_product['data'][0]['variant']['childrenID'] as $variant) {
              $product_id[] = $variant;
          }
          $data = array(
              'product_id' => $product_id
          );
      }else {
          $data = array(
              'product_id' => array((int)$product->platform('tokopedia')->platform_product_id)
          );
      }

      TokopediaService::setActiveProduct($data, (int)$product->store->platform('tokopedia')->platform_store_id);
    }
  }

  function updateStock($product) {
    if($product->store->platform('tokopedia') && $product->platform('tokopedia')) {
      $data = array();
      if($product->skus->count()>1) {
          foreach($product->skus as $sku) {
              $data[] = array(
                  'sku' => $sku->sku_code,
                  'new_stock' => $sku->stock($product)
              );
          }
      }else {
          $sku = $product->firstsku();
          $data[] = array(
              'product_id' => (int) $product->platform('tokopedia')->platform_product_id,
              'new_stock' => $sku->stock($product)
          );
      }
      TokopediaService::setStock($data, (int) $product->store->platform('tokopedia')->platform_store_id);
    }
  }

  function delete($product) {
    if($product->store->platform('tokopedia') && $product->platform('tokopedia')) {
      TokopediaService::deleteProduct(array(
          'product_id' => array((int) $product->platform('tokopedia')->platform_product_id)
      ), (int) $product->store->platform('tokopedia')->platform_store_id);
      ProductPlatform::where('platform_product_id', (int) $product->platform('tokopedia')->platform_product_id)->delete();
    }
  }

  function create($product) {
    if($product->store->platform('tokopedia')) {
      $images = array();
      foreach($product->images as $image) {
          // $images[] = array('file_path' => asset(Storage::url('public/products/'.$image->image))); #ganti ini?
          $images[] = array('file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg"); #perlu ganti
      }
      $sku = $product->firstsku();
      $productdata = array(
          "name" => $product->title,
          "condition" => "NEW",
          "description" => $product->description,
          "sku" => $sku->sku_code,
          "price" => $sku->price,
          "status" => "LIMITED",
          "stock" => $sku->stock($product),
          "min_order" => 1,
          "category_id" => (int) $product->masterproduct->tokopedia_category_id,
          "dimension" => array(
              "height" => $sku->height,
              "width" => $sku->width,
              "length" => $sku->length
          ),
          "price_currency" => "IDR",
          "weight" => $sku->weight,
          "weight_unit" => "GR",
          "is_free_return" => false,
          "is_must_insurance" => false,
          "pictures" => $images,
          "preorder" => array(
              "is_active" => true,
              "duration" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time,
              "time_unit" => "DAY"
          )
      );

      $variant = array();
      if($product->skus->count()>1) {
          $toped_variant = TokopediaService::getVariant($product->masterproduct->tokopedia_category_id);
          $selection = array();
          foreach($product->options as $option) {
              foreach($toped_variant['data']['variant_details'] as $variant_detail) {
                  if($variant_detail['name'] == $option->title) {
                      $options = array();
                      foreach($option->details as $detail) {
                          $options[] = array(
                              'hex_code' => "",
                              'unit_value_id' => 0,
                              'value' => $detail->title
                          );
                      }
                      $selection[] = array(
                          'name' => $variant_detail['name'],
                          'id' => $variant_detail['variant_id'],
                          'unit_id' => $variant_detail['units'][0]['variant_unit_id'],
                          'options' => $options
                      );
                  }
              }
          }

          $variantproducts = array();
          foreach($product->skus as $i => $sku) {
              $variantproducts[] = array(
                  "is_primary" => ($i==0) ? true : false,
                  "status" => "LIMITED",
                  "price" => $sku->price,
                  "stock" => $sku->stock($product),
                  "sku" => $sku->sku_code,
                  "combination" => $sku->getCombinationVariant($selection, $toped_variant['data']['variant_details'])
              );
          }

          foreach($selection as $i => $select) {
              unset($select['name']);
              $selection[$i] = $select;
          }
          $variant['products'] = $variantproducts;
          $variant['selection'] = $selection;
          $productdata['variant'] = $variant;
      }

      $data['products'] = array($productdata);
      $response = TokopediaService::createProduct($data, (int)$product->store->platform('tokopedia')->platform_store_id);
      if(isset($response['data']['success_rows_data'][0]['product_id'])) {
        ProductPlatform::create(array(
            'product_id' => $product->id,
            'platform' => 'tokopedia',
            'platform_product_id' => $response['data']['success_rows_data'][0]['product_id']
        ));
      }
    }
      
  }

  function update($product) {
    if($product->store->platform('tokopedia') && $product->platform('tokopedia')) {
      $images = array();
      foreach($product->images as $image) {
          // $images[] = array('file_path' => Storage::url('public/products/'.$image->image));
          $images[] = array('file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg");
      }
      $sku = $product->firstsku();
      $productdata = array(
          "id" => (int) $product->platform('tokopedia')->platform_product_id,
          "name" => $product->title,
          "condition" => "NEW",
          "description" => $product->description,
          "sku" => $sku->sku_code,
          "price" => $sku->price,
          "status" => ($product->is_publish) ? "LIMITED" : "EMPTY",
          "stock" => $sku->stock($product),
          "min_order" => 1,
          "category_id" => (int) $product->masterproduct->tokopedia_category_id, #need update
          "dimension" => array(
              "height" => $sku->height,
              "width" => $sku->width,
              "length" => $sku->length
          ),
          "price_currency" => "IDR",
          "weight" => $sku->weight,
          "weight_unit" => "GR",
          "is_free_return" => false,
          "is_must_insurance" => false,
          "pictures" => $images,
          "preorder" => array(
              "is_active" => true,
              "duration" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time,
              "time_unit" => "DAY"
          )
      );

      $variant = array();
      if($product->skus->count()>1) {
          $toped_variant = TokopediaService::getVariant($product->masterproduct->tokopedia_category_id);

          $selection = array();
          foreach($product->options as $option) {
              foreach($toped_variant['data']['variant_details'] as $variant_detail) {
                  if($variant_detail['name'] == $option->title) {
                      $options = array();
                      foreach($option->details as $detail) {
                          $options[] = array(
                              'hex_code' => "",
                              'unit_value_id' => 0,
                              'value' => $detail->title
                          );
                      }
                      $selection[] = array(
                          'name' => $variant_detail['name'],
                          'id' => $variant_detail['variant_id'],
                          'unit_id' => $variant_detail['units'][0]['variant_unit_id'],
                          'options' => $options
                      );
                  }
              }
          }

          $variantproducts = array();
          foreach($product->skus as $i => $sku) {
              $variantproducts[] = array(
                  "is_primary" => ($i==0) ? true : false,
                  "status" => "LIMITED",
                  "price" => $sku->price,
                  "stock" => $sku->stock($product),
                  "sku" => $sku->sku_code,
                  "combination" => $sku->getCombinationVariant($selection, $toped_variant['data']['variant_details'])
              );
          }

          foreach($selection as $i => $select) {
              unset($select['name']);
              $selection[$i] = $select;
          }
          $variant['products'] = $variantproducts;
          $variant['selection'] = $selection;
          $productdata['variant'] = $variant;
      }

      $data['products'] = array($productdata);
      $response = TokopediaService::updateProduct($data, (int)$product->store->platform('tokopedia')->platform_store_id);
    }
  }
}