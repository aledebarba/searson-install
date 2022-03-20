<?php

$Root = ShopWP\Factories\Render\Root_Factory::build();

$encoded_options = $Root->encode_component_data($data->data);
$component_id = $Root->generate_component_id($encoded_options);

$Root->render_root_component([
    'component_type' => $data->type,
    'component_id' => $component_id,
    'component_options' => $encoded_options,
    'component_skeleton' => empty($data->skeleton) ? false : $data->skeleton,
    'component_after' => empty($data->after) ? false : $data->after
]);