<?php

namespace ShopWP\API\Storefront\Products;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function default_product_schema() {
      return '
         availableForSale
         compareAtPriceRange {
            maxVariantPrice {
               amount
               currencyCode
            }
            minVariantPrice {
               amount
               currencyCode
            }
         }
         createdAt
         description
         descriptionHtml
         handle
         id
         onlineStoreUrl
         options {
            id
            name
            values
         }
         priceRange {
            maxVariantPrice {
               amount
               currencyCode
            }
            minVariantPrice {
               amount
               currencyCode
            }
         }
         productType
         publishedAt
         requiresSellingPlan
         title
         totalInventory
         updatedAt
         vendor,
         images(first: 250) {
            edges {
               node {
                  width
                  height
                  altText
                  id
                  originalSrc
                  transformedSrc
               }
            }
         },
         variants(first: 100) {
            edges {
               node {
                  product {
                     title
                  }
                  availableForSale
                  compareAtPriceV2 {
                     amount
                     currencyCode
                  }
                  currentlyNotInStock
                  id
                  image {
                     width
                     height
                     altText
                     id
                     originalSrc
                     transformedSrc
                  }
                  priceV2 {
                     amount
                     currencyCode
                  }
                  quantityAvailable
                  requiresShipping
                  selectedOptions {
                     name 
                     value
                  }
                  sku
                  title
                  weight
                  weightUnit
               }
            }
         }
         metafields(first: 10) {
            edges {
               node {
                  type
                  value
                  id
                  key
                  namespace
               }
            }
         }
      ';
   }

   public function query_get_product_by_id($storefront_id, $custom_schema = false) {

      $schema = $custom_schema ? $custom_schema : $this->default_product_schema();

      return [
         "query" => 'query($id: ID!) {
            product(id: $id) {
               ' . $schema . '
            }
         }',
         "variables" => [
            'id' => $storefront_id
         ]
      ];
   }

   public function query_get_products($queryParams, $custom_schema = false) {

      if (empty($queryParams['cursor'])) {
         unset($queryParams['cursor']);
      }

      $schema = $custom_schema ? $custom_schema : $this->default_product_schema();

      // Docs: https://shopify.dev/api/storefront/reference/common-objects/queryroot#products-2021-10

      return [
         "query" => 'query($query: String!, $first: Int!, $cursor: String, $sortKey: ProductSortKeys, $reverse: Boolean) {
            products(first: $first, query: $query, after: $cursor, reverse: $reverse, sortKey: $sortKey) {
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

   public function query_get_products_from_collection_id($query_params, $custom_schema = false) {

      if (empty($query_params['cursor'])) {
         unset($query_params['cursor']);
      }

      $schema = $custom_schema ? $custom_schema : $this->default_product_schema();

      return [
         "query" => 'query nodes($ids: [ID!]!, $first: Int!, $cursor: String, $sortKey: ProductCollectionSortKeys, $reverse: Boolean) {
            nodes(ids: $ids) {
               ...on Collection {
                  id
                  products(first: $first sortKey: $sortKey reverse: $reverse after: $cursor) {
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
               }
            }
         }',
         "variables" => $query_params 
      ];
      
   }
   

}