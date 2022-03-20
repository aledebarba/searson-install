<?php

namespace ShopWP\API\Storefront\Cart;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function graph_query_apply_discount($cart_data) {
      return [
         "query" => 'mutation checkoutDiscountCodeApplyV2($discountCode: String!, $checkoutId: ID!) {
            checkoutDiscountCodeApplyV2(
               discountCode: $discountCode
               checkoutId: $checkoutId
            ) {
               checkout {
                  id
                  lineItemsSubtotalPrice {
                     amount
                  }
                  subtotalPriceV2 {
                     amount
                  }
                  totalPriceV2 {
                     amount
                  }
                  discountApplications(first: 1) {
                     edges {
                        node {
                           allocationMethod
                           targetSelection
                           targetType
                           value {
                              ... on MoneyV2 {
                                 amount
                              }
                              ... on PricingPercentageValue {
                                 percentage
                              }
                           }
                        }
                     }
                  }
               }
               checkoutUserErrors {
                  code
                  field
                  message
               }
            }
         }
         ',
         "variables" => [
            'discountCode' => $cart_data['discountCodes'],
            'checkoutId' => $cart_data['checkoutId']
         ]
      ];
   }

   public function graph_query_create_checkout($cart_data) {
      return [
         "query" => 'mutation checkoutCreate($cartInput: CheckoutCreateInput!) {
            checkoutCreate(input: $cartInput) {
               checkout {
                  id
               }
               checkoutUserErrors {
                  code
                  field
                  message
               }
            }
          }',
         "variables" => [
            'cartInput' => [
               "lineItems" => $cart_data['lines']
            ]
         ]
      ];
   }

   public function graph_query_create_cart($cart_data) {

      return [
         "query" => 'mutation cartCreateMutation($cartInput: CartInput) {
            cartCreate(input: $cartInput) {
              cart {
                id
                checkoutUrl
                attributes {
                   key
                   value
                }
                estimatedCost {
                   subtotalAmount {
                     amount
                  }
                  totalAmount {
                     amount
                  }
                }
                discountCodes {
                   applicable
                   code
                }
              }
              userErrors {
                code
                field
                message
              }
            }
          }',
         "variables" => [
            'cartInput' => [
               "lines" => $cart_data['lines'],
               "note" => $cart_data['note'],
               "attributes" => $cart_data['attributes'],
               "discountCodes" => $cart_data['discountCodes'],
            ]
         ]
      ];
   }

}