<?php

namespace ShopWP\Render;

use ShopWP\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;

    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    public function merge_user_component_data(
        $user_data = [],
        $component_type = false,
        $component_class = false
    ) {
      if (!$user_data) {
         $user_data = [];
      }

      $default_vals = $component_class->$component_type($user_data);

      $default_vals_formatted = Utils_Data::standardize_layout_data(array_replace_recursive($default_vals, $user_data));

      if ($component_type !== 'cart_icon') {
        $query = $component_class->create_product_query($default_vals_formatted);

        $default_vals_formatted['query'] = $query;
      }

      return $default_vals_formatted;
    }

    public function params_client_render($params)
    {
        return [
            'data' => $params,
            'path' => 'components/wrapper/wrapper',
            'name' => 'client',
        ];
    }

    public function set_and_get_template($params)
    {
        if (empty($params['full_path'])) {
            return $this->Template_Loader->set_template_data($params['data'])->get_template_part($params['path'], $params['name']);
            
        } else {
            return $this->Template_Loader->set_template_data($params['data'])->get_template_part($params['full_path']);
        }
    }

    public function load($params)
    {
        return $this->set_and_get_template(
            $this->params_client_render($params)
        );
    }

}
