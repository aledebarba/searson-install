<?php
/***
 * =====================================================
 * 
 * Defines all hooks used by the module GUTS 
 * 
 * =====================================================
 */

use Nahid\JsonQ\Jsonq;

/**
* 
* Setup database update cronjob
*
* add five_minutes time interval 
* hook the cronjob action
* schedulle the cronjob
* define function
*
*
*/

add_filter( 'cron_schedules', 'cron_add_five_minutes' ); 
function cron_add_five_minutes( $schedules ) {
   $schedules['five_minutes'] = array(
       'interval' => 300,
       'display' => __( 'Each 5 Minutes' )
   );
   return $schedules;
}
add_action( 'getusertoshopify_cron_hook', 'getusertoshopify_updatedb' ); 
if ( ! wp_next_scheduled( 'getusertoshopify_cron_hook' ) ) {
  wp_schedule_event( time(), 'five_minutes', 'getusertoshopify_cron_hook' );
}
function getusertoshopify_updatedb() { // the scheduled Job
  $gut = new gutShopifyELT;
  $gut->dbPoke();
  $gut->set('dbstatus', 'RUNNING');
  $gut->startBulkQuery();
}

/**
 * 
 * Export REST API endpoint to get dbjson
 * 
 */
add_action( 'rest_api_init', function () {
  register_rest_route( 'guts/v1', '/all', array(
    'methods' => 'GET',
    'callback' => 'handle_get_all',
    'permission_callback' => function () {
      return true;
    }
  ) );
  register_rest_route( 'guts/v1', '/products', array(
    'methods' => 'GET',
    'callback' => 'handle_get_valid',
    'permission_callback' => function () {
      return true;
    }
  ) );
}
);
function handle_get_all( $data ) {
    $gut = new gutShopifyELT;
    $data = $gut->get('dbjson');
    return $data;
}

function handle_get_valid( $data ) {
  $gut = new gutShopifyELT;
  $data = $gut->get('dbjson');
  
  $q = new Jsonq($data);
  $q->where('status','=','ACTIVE');

  $priceMin = "";
  $priceMax = "";

  foreach($_GET as $key => $value ) {
    if ( $key == "price-range" ) {
      // resolve price-range tag
        preg_match('/f(.*?)t/', $value, $priceMin);
        preg_match('/t(.*?)\b/', $value, $priceMax);      
        $min = $priceMin[1]*100;
        $max = $priceMax[1]*100;
        $q->where("price.minVariantPrice.amount",">",$min);
        $q->where("price.maxVariantPrice.amount","<",$max);  
    } elseif  ( $key == "sweetness-range" ) {
      // resolve sweentness-range tag
        preg_match('/f(.*?)t/', $value, $sweetMin);
        preg_match('/t(.*?)\b/', $value, $sweetMax);      
        $q->where("metakey-Sweetness",">",$sweetMin[1]);
        $q->where("metakey-Sweetness","<",$sweetMax[1]);        
    } elseif ($key == "tags") {
      // do nothing
    } else {
      $q->where($key,'=',$value);
    }
  } 

  $filter = $q->from('products');
  $q->fetch();
  $fiteredSearchResult = $filter->toJson();
    
  $searchtags = explode(",",$_GET['tags']);
  
  if(count($searchtags) > 0 && $searchtags[0] != "") {
    $tagFilteredResults = array_filter($filter->toArray(), function($item) use ($searchtags) {
      foreach($searchtags as $tag) {
        if(in_array($tag, $item['tags'])) {
          return true;
        }
      }
    });
    return json_encode($tagFilteredResults);
  }
  
  return $fiteredSearchResult;
}

/**
 * handle ajax requests for the guts module
 */
add_action("wp_ajax_guts_products", "guts_products");
add_action("wp_ajax_nopriv_guts_products", "guts_products");
function guts_products() {

  echo "this is the data";
  foreach ($_POST as $key => $value) {
    echo $key." => ".$value;
  }
  die();
}