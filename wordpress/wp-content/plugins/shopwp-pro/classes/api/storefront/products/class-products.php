<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Products {

   public function __construct($Storefront, $Storefront_Queries) {
      $this->Storefront = $Storefront;
		$this->Storefront_Queries = $Storefront_Queries;
   }

   public function sanitize_collection_ids($query_params) {
      return array_map(function($collection) {

         if (!is_array($collection)) {
            return $collection;
         }

         if (array_key_exists('id', $collection)) {
            return $collection['id'];
         }

         return false;

         
      }, $query_params['ids']);      
   }
      
   public function get_products($query_params, $custom_schema = false) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->query_get_products($query_params, $custom_schema));
   }

   public function get_product_by_id($storefront_id, $custom_schema = false) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->query_get_product_by_id($storefront_id, $custom_schema));
   }

   public function get_products_from_collection_id($query_params, $custom_schema = false) {

      $query_params['ids'] = $this->sanitize_collection_ids($query_params);

      return $this->Storefront->graphql_api_request($this->Storefront_Queries->query_get_products_from_collection_id($query_params, $custom_schema));
   }   

}