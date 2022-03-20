<?php

namespace ShopWP\Utils;

use ShopWP\Utils;


if (!defined('ABSPATH')) {
	exit;
}


class URLs {

   static public function get_extension($path) {

      $qpos = strpos($path, "?");

      if (Utils::str_contains($path, "?")) {
         $path = substr($path, 0, $qpos);
      }

      return pathinfo($path, PATHINFO_EXTENSION);

   }

}

