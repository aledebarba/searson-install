<?php 

use Nahid\JsonQ\Jsonq;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/myclabs/deep-copy/src/DeepCopy/deep_copy.php';
require __DIR__ . '/php/module_hooks.php';
require __DIR__ . '/php/module_enqueues.php';
require __DIR__ . '/php/module_panel.php';
require __DIR__ . '/php/render_card_grid.php';


/**
 * Define gutShopifyELT
 * 
 * This class has all functions and dependencies
 * to connect shopify database, extract and query data
 * from a mirrored json equivalent
 * 
 */
class gutShopifyELT {
  
  private $wpdb;
  private $tableName;  

  function __construct() {
    global $wpdb;
    $this->wpdb = $wpdb;
    $this->tableName = $wpdb->prefix . '_gutshopify_config';
  }

  function renderCards($res) {
    $products = $res['result'];
    return render_results_as_cards_grid($products);
  }

  /**
   * Auxiliary function
   *
   * @param database_reference $option
   * @return database_query_response
   */
  function get($option) {
    $sql = "SELECT $option FROM $this->tableName WHERE id=1";
    $res = $this->wpdb->get_var($sql);
    return ($res);
  }
  /**
   * Auxiliary function
   *
   * @param database_reference $option
   * @return void
   */
  function set($option, $value) {
    $this->wpdb->query( 
      $this->wpdb->prepare("UPDATE $this->tableName SET $option = %s WHERE id=1", $value)
    );
  }

  /**
   * Clear junk entities
   * 
   * @param string $value to be evalueated and cleaned
   * @return string 
   */
  function validValue( $value ) {
    $res = rtrim($value, '"');
    $res = ltrim($res, '"');
    $res = rtrim($res);
    $res = ltrim($res);
    $res = htmlentities( $res, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8", true);
    $res = filter_var($res, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    return ($res);
  }

  /**
   * If needed, extract data from shopify and transform
   * 
   * This function check the Shopify Bulk Mutation state and if it is not ready
   * set the values of jsonStatus and jsonError. If database is ready, the jsonURL is setted
   * 
   * @param $filter array of filter
   * @param $order the data order ('title A-Z', 'title Z-A', 'price min-maxn', 'price max-min', 'relevance', 'best selling');
   * @param $exclude array with tags that filter OUT the results (products with the TAGS do not appear)
   * @param $count interger how many products should return
   * 
   * @return void
   * 
   */

  function get_products() {
    // check database status
    $apitoken = $this->get('apitoken');
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://searsons.myshopify.com/admin/api/2022-01/graphql.json',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'query {
      currentBulkOperation {
        id
        status
        errorCode
        createdAt
        completedAt
        objectCount
        fileSize
        url
        partialDataUrl
      }
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/graphql',
        'X-Shopify-Access-Token: '.$apitoken
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $info           = json_decode($response);
    $url            = $info->data->currentBulkOperation->url;
    $bulk_status    = $info->data->currentBulkOperation->status;
    $current_status = $this->get('dbstatus');
    
    if ($bulk_status == "COMPLETED") {
      if ($current_status == "RUNNING") {
        $this->set('dburl', $url);
        $this->set('dbstatus', $bulk_status);
        $this->transformData();
        return true;
      }
    } 
    return false;
  }
   
  /**
   * Check if database is ok to go
   * 
   * @return true if everything is ok
   * @return false if error
   * 
   */
  function dbPoke() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $charset_collate = $wpdb->get_charset_collate();
  
    // setup sql
    $sql = "CREATE TABLE $this->tableName (
      id int(9) NOT NULL AUTO_INCREMENT,
      store_url varchar(2048) DEFAULT '' NOT NULL,
      store_name varchar(512) DEFAULT '' NOT NULL,
      apikey tinytext DEFAULT '' NOT NULL,
      username varchar(64) DEFAULT '' NOT NULL,
      apitoken tinytext DEFAULT '' NOT NULL,
      scopes varchar(2048) DEFAULT '' NOT NULL,
      apisecret tinytext DEFAULT '' NOT NULL,
      storefronttoken tinytext DEFAULT '' NOT NULL,
      syncfreq int(2) NOT NULL,
      dburl varchar(2048) DEFAULT '' NOT NULL,
      dbdate timestamp DEFAULT CURRENT_TIMESTAMP,
      dbjson longtext DEFAULT '',
      dbstatus varchar(20) DEFAULT 'INIT' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    
    $dbExist = $this->wpdb->get_var("SHOW TABLES LIKE '$this->tableName'") == $this->tableName;
    if ($dbExist == false) {
      dbDelta( $sql ); // create or update structure of db
    }
    
    $hasData = $this->wpdb->get_results("SELECT * FROM $this->tableName");
    if (count($hasData) == 0) { // create some new data
      $this->wpdb->replace($this->tableName, 
        array( 
            'id' => 1,
            'store_url' => 'searsons.shopify.com', 
            'store_name' => 'Searons',
            'apikey' => '24a5922b815423a4208f3eb177b9579b',
            'username' => 'searsons_admin',
            'apitoken' => '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',
            'scopes' => 'read_products,read_product_listings',
            'apisecret' => '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',
            'storefronttoken' => '433954aed747667c0aa460acd450d876',
            'syncfreq' => 5,
            'dburl' => '',
            'dbdate' => 0,
            'dbjson' => '{ "data": [] }',
            'dbstatus' => 'init'
        ),array('%d','%s', '%s', '%s', '%s','%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'));
    } 
   return;
}


/**
 * Update current database time 
 */
function updateTime() {
  $time = new DateTime('now');
  $timestamp = $time->getTimestamp();
  $this->wpdb->query( 
      $this->wpdb->prepare("UPDATE $this->tableName SET dbdate = %d WHERE id=1", $timestamp)
  );
}

/**
 *
 * 
 * Transform data from shopify to json object 
 * 
 * 
 * 
 */

function transformData() {
  $furl = $this->get('dburl');
  if (!$furl) { return false; } 

  $error = '';
  $lncount = 0;
  $block = '{ "products": [ {';
  $jsonlines = fopen( $furl, 'r' );
  $imgCount = 0;

  if ($jsonlines) {
    while (($line = fgets($jsonlines)) !== false) {
        /**
         * for lines with main product information, gather product data
         */
          if ( strpos($line, "__parentId") == 0 ) {
            if ( $lncount != 0 ) { 
                $block.= ' "end": true }, {' ; 
          }
          $product = json_decode( $line );
          $block .= '"id" : "'.ltrim($product->id, "gid:\/\/shopify\/Product\/").'",';
          $block .= '"handle" : "'.$product->handle.'",';
          $block .= '"title" : "'.$this->validValue($product->title).'",';
          $block .= '"description" : "'.$this->validValue($product->description).'",';
          $block .= '"type" : "'.$product->productType.'",';
          $block .= '"totalInventory" : "'.$product->totalInventory.'",';
          $block .= '"tracksInventory" : "'.$product->tracksInventory.'",';
          $block .= '"status" : "'.$product->status.'",';
          $block .= '"updatedAt" : "'.$product->updatedAt.'",';
          $tags = rtrim(ltrim(json_encode($product->tags), '"'),'"');
          $block .= '"tags" : '.$tags.',';
          $price = rtrim(ltrim(json_encode($product->priceRange), '"'),'"');
          $block .= '"price" : '.$price.',';
          $lncount++;
          $imgCount = 0;
        } else { 
           /**
            * 
            * if the line is not a main product information line, gather the information
            * on this line that is related to the product
            *
            */
            $detail = json_decode( $line );
            if ( strpos($line, "gid:\/\/shopify\/Collection\/" ) !=0 ) { 
              // is a Collection
              // Actually, do nothing. Client asked to remove colletions.
              // $collection = '"collection-'.$detail->handle.'" : true, ';
              // $block.= $collection;
            }
            if ( strpos($line, '"namespace":"wine_spec"' ) !=0 ) { 
              // is a Metakey
              $keyname = '"metakey-'.$detail->key.'" : "';
              $keyvalue = $this->validValue($detail->value);
              $keyvalue.='",'; 
              if ( strpos($keyname, 'metakey-Region') != true ) { 
                // client asked to remove Region from search
                $block.= $keyname.$keyvalue;
              }
            }
            if ( strpos($line, '"src":"https:' ) !=0 ) { // is an Image
              $imgCount++;
              if ($imgCount < 2) { 
                $block.= '"image":"'.$detail->src.'",' ;
              } else {
                //TODO add images as array of images
              }
            }
            $lncount++;
          }
          }

          fclose($jsonlines);
          $block .= '"end": true }]}'; // finish the block to close json file
  } // ... if $jsonlines ...

  $this->wpdb->query(
    $this->wpdb->prepare("UPDATE $this->tableName SET dbjson = %s WHERE id=1", $block)
  );

}

/**
 * Send Shopify a request to update json data
 * 
 * This functions POSTS to shopify admin api a graphql mutation asking 
 * to expor data. Shopify start the proccess in background and updates 
 * the status of the current bulk mutation to RUNNING. When the mutation
 * finishes, shopify updates current bulk mutation status to COMPLETED
 * 
 * @return void
 */
function startBulkQuery(){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://searsons.myshopify.com/admin/api/2022-01/graphql.json',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'mutation {
    bulkOperationRunQuery(
    query: """
    {
    products {
      edges {
        node {
          id
          handle
          title
          totalInventory
          tracksInventory
          status
          updatedAt
          description
          tags
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
          collections {
            edges {
              node {
                id
                handle
                description
                title
              }
            }
          }
          metafields(namespace: "wine_spec") {
            edges {
              node {
                key
                namespace
                value
                id
                type
              }
            }
          }
          images(first: 1) {
            edges {
              node {
                src
                height
                width
                id
              }
            }
          }
        }
      }
    }
  }
      """
    ) {
      bulkOperation {
        id
        status
      }
      userErrors {
        field
        message
      }
    }
  }
  ',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/graphql',
      'X-Shopify-Access-Token: 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'
    ),
  ));

  $response = curl_exec($curl);
  curl_close($curl);
}

/**
 *
 * Extract data from db and create dinamic search form
 *
 * @return      html form output
 *
 */
function dynamicFormItems() {

  $data = $this->get('dbjson');
  $q = new Jsonq($data);  
  /**
   * auxiliary class $formOpt to store form options
   */
  $formOpt = new stdClass;
    $formOpt->title = "";
    $formOpt->ref = "";
    $formOpt->options = [];
    $formOptions = [];
  
  // get all products
  $formData = json_decode($q->from('products')->get());

  foreach($formData as $info) { // process all data to resolve meta-keys and collections
    foreach ($info as $key => $value) {
      // each item bellow will generate radio buttons groups for every
      // option in database 
      // other options are hardcoded in the form
      if($value !== "") {
        if( 
          $key == "metakey-Region" ||
          $key == "metakey-Country" ||
          $key == "metakey-Maturity" ||
          $key == "metakey-Grape" ||
          $key == "metakey-Vegan" ||
          $key == "metakey-Colour" || 
          $key == "type"
          ) {   
            if(!isset($formOptions["$key"])) { // only create an element if it don't exist already
              $formOptions["$key"] = new $formOpt; 
              $formOptions["$key"]->options = []; 
            };
            if (strpos($key, "metakey-") !== false ) { // set title for metakeys
              $formOptions["$key"]->title = ltrim($key, "metakey-");
            } else { // 
              $formOptions["$key"]->title = $key; // set title for type
            }
            if(!in_array($value, $formOptions["$key"]->options) ) { // set value
               $formOptions["$key"]->options[]=$value; 
            }
        }
        
        if ( strpos($key, "collection-") !== false ) { // resolve collection
          $collectionName = ltrim($key,"collection-");
          if(!isset($formOptions['collection'])) { // only create item if it don't exist already
            $formOptions['collection'] = new $formOpt; 
            $formOptions['collection']->title='collection';
            $formOptions['collection']->options=[];
          };
          if(!in_array($collectionName, $formOptions['collection']->options)) { 
            $formOptions['collection']->options[] = $collectionName;
          }
        }

    }
    } // foreach
  } // foreach
  
  //
  // Create form
  //

  $formOptionsHtml = ""; // reset output 
  $accordionClasses = "";

  foreach ($formOptions as $opt) { 
    if ( strpos('metakey-',$opt->title) !== false ) {
      $title = ltrim('metakey-', $opt->title);
    } else {
      $title = $opt->title;
    }
    // resolve accordeon classes names by normalizing title
    $accordionClasses = preg_replace('/[[:space:]]+/', '-', $title);
    $accordionClasses = strtolower($title);
    $formOptionsHtml.="
    <div class='accordion $accordionClasses'>
      <div class='accordion__title'>
        <h1>$title</h1>
        <span class='current__selection' for='$title'></span>
        <div class='icon'></div>
      </div>
      <div class='radio_list'>
    ";

    // order options by alphabetical order
    sort($opt->options);

    foreach($opt->options as $item) {
      // normalize class name
      $radioGroupClasses = preg_replace('/[[:space:]]+/', '-', $item);
      $radioGroupClasses = strtolower($radioGroupClasses); 


      if ($title == "collection") {
        $formOptionsHtml.="
          <div class='input_radio-group $radioGroupClasses'>
          <label for='$item'>            
            <input type='radio' name='$title' value='$item' class='list-item'>
            <span>$item</span>
          </label>
          </div>
          ";
      } else {
        $formOptionsHtml.="
        <div class='input_radio-group $radioGroupClasses'>
          <label for='$item'>
            <input type='radio' name='$title' value='$item' class='list-item'>
            <span>$item</span>
          </label>
        </div>"; }
      } // end for each
    $formOptionsHtml.="</div> <!-- end radio list -->
    </div> <!-- end accordion --> 
    ";
  } 
  return $formOptionsHtml;
}

/**
 * Filter data based on url params and render results
 * 
 * @param renderer closure function that render each data block output
 * @return html output
 * 
 */
function filterData($req = []) {
  
    $data = $this->get('dbjson');
    $origen = new Jsonq($data);
    $total = $origen->from('products');
    $info = [];
    $info['total'] = $total->get()->count();

    $q = new Jsonq($data);
    $res = $q->from('products');

    /**
     * filter out inactive and out of stock products
     */
    $q->where("status","=","ACTIVE");
    //$q->where("totalInventory",">",0);

    /**
     * filter by GET parameters
     */
    if (count($_GET) > 0) {
      $active_filters = "<div class='gut__current_filters'>";
      foreach ($_GET as $key => $value) { 
        $active_filters .= "<div class='gut__filter'><span>$key :</span><span> $value</span></div>"; 
        if (
          $key == "Country" ||
          $key == "Region" ||
          $key == "Maturity" ||
          $key == "Grape" ||
          $key == "Vegan" ||
          $key == "Colour" 
        ) {
          $k = 'metakey-'.$key;
          $q->where($k,'=',$value);
        }
        
        if ($key == "price-range" ) {
          $range = explode( '-', $value );
          $min = ltrim($range[1],'f');
          $max = ltrim($range[2],'t');
          $min = $min * 100;
          $max = $max * 100;
          $q->where("price.minVariantPrice.amount",'>',$min);
          $q->where("price.maxVariantPrice.amount",'<',$max);
        }

        if ($key == "sweetness-range" ) {
          $range = explode( '-', $value );
          $min = ltrim($range[1],'f');
          $max = ltrim($range[2],'t');
          $q->where("metakey-Sweetness",'>',$min);
          $q->where("metakey-Sweetness",'<',$max);
        }

        if ($key == "collection") {
          $q->where($key."-".$value,'=','true');
        }
        if ($key == "type" ) { 
          $q->where($key,"=",$value);
        }
        // sort order 
        if ($key == "sortby" ) { 
          if ($value == "price-asc") {
            $q->sortBy("price.minVariantPrice.amount",'asc');
          }
          if ($value == "price-desc") {
            $q->sortBy("price.minVariantPrice.amount",'desc');
          }
          if ($value == "title-asc") {
            $q->sortBy("title",'asc');
          }
          if ($value == "title-desc") {
            $q->sortBy("title",'desc');
          }
          if ($value == "date-asc") {
            $q->sortBy("createdAt",'asc');
          }
        }
      } // for each
      $active_filters .= "</div>";
    } else {
      $active_filters = "<div class='gut__filter gut__filter__no-filter-selected'><p>No Filters Selected</p></div>";
    } // if $_GET>0

    $q->get(); 

    $info['filtered'] = $q->count();
    return (array('filters' => $active_filters, 'result'=>$res, 'info' => $info));
  }

function renderFilterForm(){

$formOptions = $this->dynamicFormItems(); // this part of the form is made based on products Options like collections, type and metakey
//$priceMin = $_GET['price-min'] ? $_GET['price-min'] : '0' ;
//$priceMax = $_GET['price-max'] ? $_GET['price-max'] : '99999';

echo <<<FORM
    <div id='gutshopifyelt'>
        <div class='guts__filter_overlay closed'>
            <div class='close-button'>
                <i class="fal fa-times"></i>
            </div>
        
            <form id='gutshopifyelt__form' class='multi-select-form' method="GET" action="" name="gutshopifyelt__search-form">
                $formOptions
                
                <!-- Sweetness range radio pickers -->
                <div class='accordion'>
                    <div class='accordion__title'>
                        <h1>Sweetness</h1>
                        <span class='current__selection' for='sweetness-range'></span>
                        <div class='icon'></div>
                    </div>
                    <div class='radio_list'>                    
                      <div class='input_radio-group sweetness-range'>
                        <label for='sweetness-f0-t99999'>
                          <input type='radio' name='sweetness-range' value='sweetness-f0-t99999' class='list-item'>
                          <span>All</span>
                        </label>
                      </div>
                      <div class='input_radio-group sweetness-range'>
                        <label for='sweetness-f0-t1'>
                          <input type='radio' name='sweetness-range' value='sweetness-f0-t1' class='list-item'>
                          <span>Dry</span>
                        </label>
                      </div>
                      <div class='input_radio-group sweetness-range'>
                        <label for='sweetness-f1-t5'>
                          <input type='radio' name='sweetness-range' value='sweetness-f1-t5' class='list-item'>
                          <span>Off Dry</span>
                        </label>
                      </div>
                      <div class='input_radio-group sweetness-range'>
                        <label for='sweetness-5-t7'>
                          <input type='radio' name='sweetness-range' value='sweetness-f5-t7' class='list-item'>
                          <span>Medium Sweet</span>
                        </label>
                      </div>
                      <div class='input_radio-group sweetness-range'>
                        <label for='sweetness-f7-t99999'>
                          <input type='radio' name='sweetness-range' value='sweetness-f7-t99999' class='list-item'>
                          <span>Sweet</span>
                        </label>
                      </div>
                    </div>
                </div>
                <!-- end sweetness range radio pickers -->

                <!-- Price range radio pickers -->
                <div class='accordion'>
                    <div class='accordion__title'>
                        <h1>Price</h1>
                        <span class='current__selection' for='price-range'></span>
                        <div class='icon'></div>
                    </div>
                    <div class='radio_list'>                    
                      <div class='input_radio-group price-range'>
                        <label for='price-f0-t99999'>
                          <input type='radio' name='price-range' value='price-f0-t99999' class='list-item'>
                          <span>All</span>
                        </label>
                      </div>
                      <div class='input_radio-group price-range'>
                        <label for='price-f0-t15'>
                          <input type='radio' name='price-range' value='price-f0-t15' class='list-item'>
                          <span>€1 to €15</span>
                        </label>
                      </div>
                      <div class='input_radio-group price-range'>
                        <label for='price-f15-t25'>
                          <input type='radio' name='price-range' value='price-f15-t25' class='list-item'>
                          <span>€15 to €25</span>
                        </label>
                      </div>
                      <div class='input_radio-group price-range'>
                        <label for='price-f25-t50'>
                          <input type='radio' name='price-range' value='price-f25-t50' class='list-item'>
                          <span>€25 to €50</span>
                        </label>
                      </div>
                      <div class='input_radio-group price-range'>
                        <label for='price-f50-t100'>
                          <input type='radio' name='price-range' value='price-f50-t100' class='list-item'>
                          <span>€50 to €100</span>
                        </label>
                      </div>
                      <div class='input_radio-group price-range'>
                        <label for='price-f100-t99999'>
                          <input type='radio' name='price-range' value='price-f100-t99999' class='list-item'>
                          <span>€100 or more</span>
                        </label>
                      </div>
                    </div>
                </div>
                <!-- end Price range radio pickers -->
                <!-- sort -->
                <div class="accordion">
                    <div class='accordion__title'>
                        <h1>Sort order</h1>
                        <span class='current__selection' for='sortby'></span>
                        <div class='icon'></div>
                    </div>
                    <label for="sortby">Sort by:</label>
                    <select name="sortby">
                        <option value="title-asc">Title A-Z</option>
                        <option value="title-desc">Title Z-A</option>
                        <option value="price-asc">Price (Low to High)</option>
                        <option value="price-desc">Price (High to Low)</option>
                        <option value="date-asc">Recently Added</option>
                    </select>
                </div>
                <!-- /sort -->
                <div class="guts__filter_overlay__buttons bottom">
                    <input type="reset" value="Clear Options" id="form-reset-button" class="button line font-weight-medium">
                    <input type="submit" value="Filter" class="button default text-transform font-weight-bold">
                </div>
            </form>
        </div>
    </div>
FORM;
} 

/**
 * END CLASS
 */

}
?>