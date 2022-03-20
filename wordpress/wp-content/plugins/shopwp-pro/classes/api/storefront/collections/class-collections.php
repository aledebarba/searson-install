<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Collections {

   public function __construct($Storefront, $Storefront_Queries) {
      $this->Storefront = $Storefront;
		$this->Storefront_Queries = $Storefront_Queries;
   }
   
   public function get_collections($query_params, $custom_schema = false) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->query_get_collections($query_params, $custom_schema));
   }  

}