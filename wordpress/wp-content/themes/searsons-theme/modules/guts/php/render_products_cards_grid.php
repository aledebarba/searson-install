<?php 
/**
 * Receives the results array and render cards
 *
 * @param [array] $res
 * @return $results as html code
 */

function render_results_as_cards_grid($res, $req = []) {
  $page = $_GET['current-page'];
  if (!$page) {
    $page = 1;
  };
  $productsPerPage = $req['productsPerPage']? $req['productsPerPage'] : 20; 
  $from = ($page - 1) * $productsPerPage;
  $to = $page * $productsPerPage;
  $count = 0;
  $totalFound = count($res);
  if ($totalFound>0) { 
  $results = "
    <section class='widget_feed__results'>
      <div class='box-headline' style='display:none;'>
          Results
        </div>
        <div class='guts__hold-feed'>
  ";

  foreach ($res as $product) {
    if ( // FILTER OUT drafts, inactives and sold outs products from results
      $product->status == 'ACTIVE' &&
      $product->totalInventory != '0'
    ) { 
      /**
       * reset current product output values
       */
      $metakeys=[];
      $card = "";
      $card_title="";
      $card_image="";
      $card_link = "";
      $card_href = "";
      $card_title_text = "";
      $card_title_short = "";
      $card_price="";
      $card_metakeys="";
      $price = "";
      $card_id = "";
    
      /**
       * get evey product key pair and add correspondent html code
       */
      
      foreach($product as $key => $value) {
        if( strpos($key, "metakey") !== false) {
          /**
           * if metakeys,create array to iteract later
           */
          if( 
              $key == "metakey-Region" ||
              $key == "metakey-Country" ||
              $key == "metakey-Sweetness" ||
              $key == "metakey-Maturity" ||
              $key == "metakey-Grape" ||
              $key == "metakey-Vegan" ||
              $key == "metakey-Organic" ||
              $key == "metakey-Colour"
          ) { 
            $metakeys[]=[$key,$value]; 
          }
        }
        if ($key == "image") {
          // $card_image = "<div class='image'><img src='$value' alt='product illustration'></div>";
          $card_image = "<a href='https://searsons.com/products/$value/'><img src='$value' alt='product illustration'></a>";
        }
        if ($key == "handle") {
          $card_link = "<a class='more_info' href='https://searsons.com/products/$value/'>More Info</a>";
          $card_href = "https://searsons.com/products/$value/";
        }
        if ($key == "title") {
          //$card_title = "<div class='title'><h1>$t</h1></div>";
          $card_title_text = $value;
        }
        if ($key == "price") {
          $max = $value['maxVariantPrice'];
          $price = toCurrency($max['amount']/100, $max['currencyCode']);
          $card_price="<div class='price'><p>$price</p></div>";
        }
        if ($key == "id") {
          $card_id = $value;
        }
      }
    
      /**
       * Resolve metakeys array html
       */
      $card_metakeys="<div class='metakeys' style='display: none;'>";
      foreach($metakeys as $metakey) {
        $mkey_class=$metakey[0];
        $mkey_val=$metakey[1];
        if ($mkey_class == "metakey-Organic") { $mkey_val = "Organic"; }
        if ($mkey_class == "metakey-Vegan") { $mkey_val = "Vegan"; }
        $card_metakeys.= "
          <div class='metakey'>
              <div class='$mkey_class'></div>
              <div class='value'>$mkey_val</div>
          </div>";
      }
      $card_metakeys.="</div>";
    
      /**
       * resolve card
       */
      $card = "
      <div class='guts__search-result__card' aria-hidden='true'>
          <div class='image' role='presentarion'>
              $card_image
          </div>
          <div class='body'>
            <div class='header'>
              <h3 class='h4'>
                <a href='$card_href' title='$card_title_text'>$card_title_text</a>
              </h3>
              <h6>$price</h6>
            </div>
            <div class='action'>
              ".do_shortcode("[wps_products_buy_button hide_quantity='true' items_per_row='1' product_id='$card_id']")."
            </div>
            $card_metakeys
            </div>
      </div>
      ";
    
      /**
       * 
       * add card html to results output
       * render only products in current page
       * 
       */
      if ($count >= $from && $count <= $to) {
        $results .= $card;
        $count++;
      }
      if ($count >= $to) {
        break;
      } 
    }
  }
  
  $results .= "  
      </div> <!-- .hold-feed -->
      <div id='guts__pagination'>
        <div  id='guts__data-reference' 
              data-per-page='$productsPerPage' 
              data-current-page='$page'
              data-total-rendered='$count'
              data-total-found='$totalFound'>
        </div>
        <div id='guts__pagination-hold'>
        </div>         
    </section>"; 
} else { 
  $results = "<div class='gut__filter gut__reder__cards gut__filter__no-results'>There are no results for this filter combination.</div>";
}

return $results;
}

/**
 * Convert value to currency
 *
 * @return string
 */
function toCurrency($value, $currency = "EUR") {
    $oFormatter = new \NumberFormatter('de_DE', \NumberFormatter::CURRENCY);
    return($oFormatter->formatCurrency($value, $currency));
  }
  

?>