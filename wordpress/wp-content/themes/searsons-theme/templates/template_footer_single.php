<section class="widget-article primary" >
    <div class="hold">
        <div class="box-text">
            <h2 class="h3 font-primary font-weight-bold"><?php the_title();?></h2>
                
            <ul class="list-post-single">
                <li>
                    <time datetime="<?php the_time('Y-m-d'); ?>"><i class="fal fa-calendar-day"></i> <?php the_time('F jS, Y'); ?></time>
                </li>

                <li>
                    <i class="fal fa-folder-open"></i> <?php the_category(', '); ?>
                </li>

                <?php the_tags('<li><i class="fal fa-tags"></i> ', ', ', '</li>'); ?>
            </ul>
        </div>
    </div>
</section>