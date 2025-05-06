<?php
    /*
    Template Name: Sub Page Template
    */
    get_header();
    $pageID = get_the_ID();
    $parent_id = null;
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
    }
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){ ?>
        <script>
            $(document).ready(function () {
            });
        </script>
    <?php } ?>

<section class="contentBlock">
    <div class="container">
        <div class="contentArea">
            <div class="contentFull">
                <?php if(have_posts()): while(have_posts()): the_post()?>
                    <?php the_content()?>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>