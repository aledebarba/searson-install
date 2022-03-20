<?php

$Templates = ShopWP\Factories\Render\Templates_Factory::build();

?>

<div
   data-wpshopify-component
   data-wpshopify-component-id="<?= sanitize_html_class($data->component_id); ?>"
   data-wpshopify-component-type="<?= sanitize_html_class($data->component_type); ?>"
   data-wpshopify-payload-settings="<?= sanitize_html_class($data->component_options); ?>">

   <?php 

      if ($data->component_skeleton) {
         $Templates->set_and_get_template([
            'data' => $data,
            'full_path' => $data->component_skeleton
         ]);
      }
   
   ?>

</div>

<?php 

if ($data->component_after) {
   $Templates->set_and_get_template([
      'data' => $data,
      'full_path' => $data->component_after
   ]);
}

?>