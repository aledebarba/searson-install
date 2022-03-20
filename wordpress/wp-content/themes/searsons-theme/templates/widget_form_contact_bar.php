<?php
    if(have_rows('site_contact', 'option')) :
    while(have_rows('site_contact', 'option')) : the_row();
?>
    <?php if(get_sub_field('email')) :
        $item = get_sub_field('email');
        $title = get_sub_field('title');
        $icon = get_sub_field('icon');
    ?>
    <div class="item-sidebar">
        <div class="title-sidebar">
            <h6 class="h5 text-transform"><?php echo $icon;?> <?php echo $title;?></h6>
        </div>

        <div class="content-sidebar">
            <div class="hold-sidebar">
                <p><a href="mailto: <?php echo $item;?>"><?php echo $item;?></a></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(get_sub_field('phone')) :
        $item = get_sub_field('phone');
        $title = get_sub_field('title');
        $icon = get_sub_field('icon');  
    ?>
    <div class="item-sidebar">
        <div class="title-sidebar">
            <h6 class="h5 text-transform"><?php echo $icon;?> <?php echo $title;?></h6>
        </div>

        <div class="content-sidebar">
            <div class="hold-sidebar">
                <p><a href="tel: <?php echo $item;?>"><?php echo $item;?></a></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(get_sub_field('whatsapp')) :
        $item = get_sub_field('whatsapp');
        $title = get_sub_field('title');
        $icon = get_sub_field('icon');
    ?>
    <div class="item-sidebar">
        <div class="title-sidebar">
            <h6 class="h5 text-transform"><?php echo $icon;?> <?php echo $title;?></h6>
        </div>

        <div class="content-sidebar">
            <div class="hold-sidebar">
                <p><a href="https://api.whatsapp.com/send/?phone=<?php echo $item;?>" target="_blank"><?php echo $item;?></a></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(get_sub_field('address')) :
        $item = get_sub_field('address');
        $title = get_sub_field('title');
        $icon = get_sub_field('icon');
    ?>
    <div class="item-sidebar">
        <div class="title-sidebar">
            <h6 class="h5 text-transform"><?php echo $icon;?> <?php echo $title;?></h6>
        </div>

        <div class="content-sidebar">
            <div class="hold-sidebar">
                <p><?php echo $item;?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(get_sub_field('office_hours')) :
        $item = get_sub_field('office_hours');
        $title = get_sub_field('title');
        $icon = get_sub_field('icon');
    ?>
    <div class="item-sidebar">
        <div class="title-sidebar">
            <h6 class="h5 text-transform"><?php echo $icon;?> <?php echo $title;?></h6>
        </div>

        <div class="content-sidebar">
            <div class="hold-sidebar">
                <p><?php echo $item;?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php
    endwhile;
    endif;
?>