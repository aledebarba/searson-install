<?php

namespace ShopWP\API\Admin;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class Shop {

    public function __construct($Admin, $Admin_Queries) {
        $this->Admin = $Admin;
        $this->Admin_Queries = $Admin_Queries;
    }

    public function get_tags() {
        return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_get_tags());
    }

    public function get_vendors() {
        return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_get_vendors());
    }

    public function get_product_types() {
        return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_get_product_types());
    }    
}
