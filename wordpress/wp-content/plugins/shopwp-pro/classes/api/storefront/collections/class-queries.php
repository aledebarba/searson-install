<?php

namespace ShopWP\API\Storefront\Collections;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function collection_schema() {
      return '
         title
         handle
         id
         description
         descriptionHtml
         onlineStoreUrl
         image {
            width
            height
            altText
            id
            originalSrc
            transformedSrc
         }
      ';
   }

   public function query_get_collections($queryParams, $custom_schema = false) {

      $schema = $custom_schema ? $custom_schema : $this->collection_schema();

      if (empty($queryParams['cursor'])) {
         unset($queryParams['cursor']);
      }

      return [
         "query" => 'query($query: String!, $first: Int!, $cursor: String, $sortKey: CollectionSortKeys, $reverse: Boolean) {
            collections(first: $first, query: $query, after: $cursor, reverse: $reverse, sortKey: $sortKey) {
               pageInfo {
                  hasNextPage
                  hasPreviousPage
               }
               edges {
                  cursor
                  node {
                     ' . $schema . '
                  }
               }
            }
         }',
         "variables" => $queryParams
      ];
   }

}