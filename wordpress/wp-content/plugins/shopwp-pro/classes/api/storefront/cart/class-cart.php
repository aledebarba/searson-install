<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Cart {

   public function __construct($Storefront, $Storefront_Queries) {
      $this->Storefront = $Storefront;
		$this->Storefront_Queries = $Storefront_Queries;
   }
   
   public function create_cart($cart_data) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_create_cart($cart_data));
   }

   public function create_checkout($cart_data) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_create_checkout($cart_data));
   }

   public function apply_discount($cart_data) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_apply_discount($cart_data));
   }

}
