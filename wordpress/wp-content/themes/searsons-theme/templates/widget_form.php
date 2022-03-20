<?php
    $privacy = get_sub_field('privacy');
	$content = get_sub_field('content');
    $form_id = "[contact-form-7 id='$content']";

    get_template_part('templates/element_headline');
    get_template_part('templates/element_buttons');
?>
<?php /*?>

com e sem sidebar
com a sidebar carrega a estrtura do box layout, sem a sideebar nao carrega<?php */?>

<div class="box-layout">
    <div class="content-hold">
        <?php
              echo do_shortcode($form_id);
        
              if ($privacy) {
                  echo '
                    <div class="box-notice">
                        .'. $privacy .'.
                    </div>
                  ';
              }
        ?>
    </div>

    <aside class="aside-hold">
        <?php
            if(get_sub_field('sidebar') == 'yes') :
                get_template_part('templates/widget_form_contact_bar');
            endif;
        ?>
    </aside>
</div>