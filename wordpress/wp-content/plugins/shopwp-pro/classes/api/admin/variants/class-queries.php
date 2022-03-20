<?php

namespace ShopWP\API\Admin\Variants;

if (!defined('ABSPATH')) {
    exit();
}

class Queries
{
    public function graph_query_variants_inventory_tracked($ids)
    {
        return [
            "query" => 'query($ids: [ID!]!) {
               nodes(ids: $ids) {
                  ... on ProductVariant {
                     id
                     inventoryPolicy
                     inventoryItem {
                        tracked
                        inventoryLevels(first: 5) {
                           edges {
                              node {
                                 available
                              }
                           }
                        }
                     }
                     
                  }
               }
            }',
            "variables" => [
                'ids' => $ids,
            ],
        ];
    }

   //  public function graph_query_products_by_variant_price($max_price)
   //  {
   //      return [
   //          "query" => 'query($max_price: String!) {
   //             products (first: 2, query: $max_price ) {
   //                pageInfo {
   //                   hasNextPage
   //                }
   //                edges {
   //                   cursor
   //                   node {
   //                      title
   //                      variants (first: 10) {
   //                         edges {
   //                         node {
   //                               price
   //                         }
   //                         }
   //                      }
   //                   }
   //                }
   //              }
   //          }',
   //          "variables" => [
   //              'max_price' => $max_price,
   //          ],
   //      ];
   //  }
    
}
