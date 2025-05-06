    <?php 
    $post_type = get_post_type();
    if ($post_type === 'post') {
    ?>
        <div class="recentArchive" data-margin-top="100" data-sticky-for="1023">
            <div class="recent">
                <h2>Recent Posts</h2>
                <?php $orig_post = $post;
                    global $post;
                    $tags = wp_get_post_tags($post->ID);
                    $catgs = wp_get_post_categories($post->ID);
                    if($tags || $catgs){
                    $tag_ids = array();
                    $cat_ids = array();
                    foreach($tags as $individual_tag){ $tag_ids[] = $individual_tag->term_id;}
                    foreach($cat_ids as $individual_cat){ $cat_ids[] = $individual_cat->term_id;}
                    //$cat_ids = implode(",", $catgs);
                        }
                    $args=array(
                    'tag__in' => $tag_ids,
                    'in_category' => $cat_ids,
                    'post__not_in' => array($post->ID),
                    'posts_per_page'=>5, 
                    );
        
                $wp_query = new WP_Query($args);
                while($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <div class="post">
                    <p><?php echo get_the_date('l '); ?><span><?php echo get_the_date('j,F,Y'); ?></span></p>
                    <h3><?php echo get_the_title(); ?></h3>
                </div>
                <?php endwhile;
                wp_reset_query();  ?>
            </div>
            <div class="archive">
                <h2>Archives</h2>
                <div class="month">
                    <ul class="archv-list">
                        <li>
                        <?php
                        wp_get_archives(array(
                            'type' => 'monthly',
                            'format' => 'custom',
                            'before'          => '',
                            'after'           => '',
                            'show_post_count' => true,
                        ));
                        ?></li>
                    </ul>
                </div>  
            </div>
        </div>
    <?php } if($post_type === 'news'){?>
        <!-- recent-news -->
        <div class="recentNews" data-margin-top="100" data-sticky-for="1023">
            <div class="recent">
                <h2>Recent News</h2>
                <?php
            $post_args = array(
            'post_type' => 'news',
            'paged' => $paged,
            'order' => 'DESC',
            'posts_per_page' => 5,
            );
            $post_query = new WP_Query( $post_args );
            if ( $post_query->have_posts()) { ?>
                <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                    $postID = $post_query->post->ID; ?>
                <div class="post">
                    <p><?php echo get_the_date('l '); ?><span><?php echo get_the_date('j,F,Y'); ?></span></p>
                    <h3><?php echo get_the_title();?></h3>
                </div><?php endwhile;
             } ?>
            </div>
        </div>
    <?php } ?>