<?php
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$user_roles = $curauth->roles;
$userID = $curauth->ID;
$parent_id = null;
if ($post && has_post_parent($userID)) {
    $parent_id = $post->post_parent;
}
if (in_array('faculty', $user_roles)) {
    if (!get_field('display_faculty', 'user_' . $userID)) {
        ob_start();
        wp_redirect(site_url("/"));
        exit;
    }
}
get_header();
add_action('wp_footer', 'page_scripts', 25);
function page_scripts()
{ ?>
    <script>
        $(document).ready(function() {
            $('.authorTab li a').click(function(e) {
                e.preventDefault();
                var tabId = $(this).attr('href');
                $('.authorDetail > div').removeClass('active');
                $(tabId).addClass('active');
                $('.authorTab a').removeClass('active');
                $(this).addClass('active');

                var activeText = $(this).text();
                $('.sidebarDrop').text(activeText);
            });
            $('.authorTab li:first-child a').trigger('click');
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".sidebarDrop").click(function() {
                $('.authorTab').toggleClass("open");
            });
            $(".authorTab li").click(function() {
                $('.authorTab').toggleClass("open");
            });
        });
    </script>
<?php } ?>

<?php
$profImg = get_theme_file_uri('/images/faculty-img/avatar.jpg');
$qualification = "";
$research = "";
$designation = '';
$bio = "";
$projects_array = array();
$news_array = array();
$awards_array = array();
$membership_array = array();

if (in_array('faculty', $user_roles)) {

    if (get_field('fac_image', 'user_' . $userID)) {
        $profImgGrp = get_field('fac_image', 'user_' . $userID);
        if ($profImgGrp['frontend_profile_image'] && $profImgGrp['vc_approval']) {
            $profImg = $profImgGrp['frontend_profile_image'];
        }
    }

    if (get_field('fac_designation', 'user_' . $userID)) {
        $designation_field = get_field('fac_designation', 'user_' . $userID);
        if ($designation_field['vc_approval']) {
            $designationId = $designation_field['frontend_designation'];
            $designationNames = [];
    
            foreach ($designationId as $id) {
                $designations = get_term($id, 'designations');
                if ($designations && !is_wp_error($designations)) {
                    $designationNames[] = $designations->name;
                }
            }
    
            // Join with separator, skip the last one
            $designation = implode(' | ', $designationNames);
        }
    }
    

    if (get_field('fac_qualification', 'user_' . $userID)) {
        $qualification_field = get_field('fac_qualification', 'user_' . $userID);
        if ($qualification_field['vc_approval']) {
            $qualificationId = $qualification_field['frontend_qualification'];
            $qualificationNames = [];
    
            foreach ($qualificationId as $id) {
                $qualifications = get_term($id, 'qualifications');
                if ($qualifications && !is_wp_error($qualifications)) {
                    $qualificationNames[] = $qualifications->name;
                }
            }
    
            // Join with separator, skip the last one
            $qualification = implode(' | ', $qualificationNames);
        }
    }
    

    if (get_field('fac_research_interest', 'user_' . $userID)) {
        $research_field = get_field('fac_research_interest', 'user_' . $userID);
        if ($research_field['vc_approval']) {
            $researchId = $research_field['frontend_research_interest'];
            foreach ($researchId as $id) {
                $researchs = get_term($id, 'research');
                $research .= $researchs->name . ' ';
            }
        }
    }

    if (get_field('fac_bio_grp', 'user_' . $userID)) {
        $bio_field = get_field('fac_bio_grp', 'user_' . $userID);
        if ($bio_field['vc_approval']) {
            $bio =  $bio_field['frontend_bio'];
            
        }
    }

    $row = 0;
    if (get_field('fac_projects', 'user_' . $userID)) {
        if (have_rows('fac_projects', 'user_' . $userID)) :

            while (have_rows('fac_projects', 'user_' . $userID)) : the_row();
                $row++;
                $showing_data = get_sub_field('showing_data');

                $approval = get_sub_field('approval');
                if ($approval['vc_approval']) {
                    if ($row === 1) {
                    }
                    $project_data = array(
                        'project_name' => $showing_data['project_name'],
                        'project_description' => $showing_data['project_description'],
                        'project_pdf' => $showing_data['project_pdf']
                    );
                    $projects_array[] = $project_data;
                }

            endwhile;
        endif;
    }


    $row = 0;
    if (get_field('awards_recognitions', 'user_' . $userID)) {
        if (have_rows('awards_recognitions', 'user_' . $userID)) :
            while (have_rows('awards_recognitions', 'user_' . $userID)) : the_row();
                $row++;
                $showing_data = get_sub_field('showing_data');

                $approval = get_sub_field('approval');

                if ($approval['vc_approval']) {
                    if ($row === 1) {
                    }
                    $award_data = array(
                        'award_name' => $showing_data['award_name'],
                        'award_description' => $showing_data['award_description'],
                        'award_image' => wp_get_attachment_image_url($showing_data['award_image'])
                    );
                    $awards_array[] = $award_data;
                }

            endwhile;
        endif;
    }

    $row = 0;
    if (get_field('fac_news', 'user_' . $userID)) {
        //var_dump(get_field('fac_news', 'user_' . $userID));
        if (have_rows('fac_news', 'user_' . $userID)) :
            //var_dump(get_field('fac_news', 'user_' . $userID));
            while (have_rows('fac_news', 'user_' . $userID)) : the_row();
                $row++;
                $showing_data = get_sub_field('showing_data');

                $approval = get_sub_field('approval');

                if ($approval['vc_approval']) {
                    if ($row === 1) {
                    }

                    $news_data = array(
                        'news_title' => $showing_data['news_title'],
                        'news_link' => $showing_data['news_link'],
                    );
                    $news_array[] = $news_data;
                }

            endwhile;
        endif;
    }

    $row = 0;
    if (get_field('fac_membership', 'user_' . $userID)) {
        // $membership_grp = get_field('fac_membership', 'user_' . $userID);
        if (have_rows('fac_membership', 'user_' . $userID)) :
            while (have_rows('fac_membership', 'user_' . $userID)) : the_row();
                $row++;
                $showing_data = get_sub_field('showing_data');
                $approval = get_sub_field('approval');

                if ($approval['vc_approval']) {
                    if ($row === 1) {
                    }
                    $membership_data = array(
                        'membership_title' => $showing_data['membership_title'],
                        'membership_description' => $showing_data['membership_description'],
                    );
                    $membership_array[] = $membership_data;
                }

            endwhile;
        endif;
    }
} else {

    if (get_field('fac_image', 'user_' . $userID)) {
        $profImgID = get_field('fac_image', 'user_' . $userID);
        $profImg = wp_get_attachment_image_url($profImgID);
    }

    if (get_field('fac_designation', 'user_' . $userID)) {
        $designationId = get_field("fac_designation", 'user_' . $userID);
        foreach ($designationId as $id) {
            $designations = get_term($id, 'designations');
            $designation .= $designations->name . ' ';
        }
    }

    if (get_field('fac_qualification', 'user_' . $userID)) {
        $qualificationId = get_field('fac_qualification', 'user_' . $userID);
        foreach ($qualificationId as $id) {
            $qualifications = get_term($id, 'qualifications');
            $qualification .= $qualifications->name . '  ';
        }
    }

    if (get_field('fac_research_interest', 'user_' . $userID)) {
        $researchId = get_field("fac_research_interest", 'user_' . $userID);
        foreach ($researchId as $id) {
            $researchs = get_term($id, 'research');
            $research .= $researchs->name . ' ';
        }
    }

    if (!empty(get_field('mains_bio', 'user_' . $userID))) {
        $bio = get_field('mains_bio', 'user_' . $userID);
    }

    if (get_field('mains_projects', 'user_' . $userID)) {
        $projects_array = get_field('mains_projects', 'user_' . $userID);
    }

    if (!empty(get_field('mains_awards_recognitions', 'user_' . $userID))) {
        $awards_array = get_field('mains_awards_recognitions', 'user_' . $userID);
    }

    if (!empty(get_field('mains_news', 'user_' . $userID))) {
        $news_array = get_field('mains_news', 'user_' . $userID);
    }

    if (!empty(get_field('main_membership', 'user_' . $userID))) {
        $membership_array = get_field('main_membership', 'user_' . $userID);
    }
}

?>

<section class="bannerBlock">
    <div class="bannerBg" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');"></div>
    <div class="container">
        <div class="bannerArea">
            <?php $banner_title = get_banner_title(); ?>
            <h1><?php echo esc_html($banner_title); ?></h1>
            <?php custom_breadcrumbs(); ?>
        </div>
    </div>
</section>

<section class="contentBlock">
    <div class="container">
        <div class="contentArea">
            <div class="authorArea">
                <div class="authorHeader">
                    <h2><?php echo $curauth->first_name . ' ' . $curauth->last_name; ?></h2>
                    <p><?php echo $designation; ?></p>
                </div>
                <div class="authorLeft">
                    <div class="authorThumb">
                        <img src="<?php echo $profImg; ?>" alt="<?php $curauth->display_name; ?>" class="profile-img">
                    </div>
                </div>
                <div class="authorRight">
                    <?php if($qualification) { ?>
                    <h4>Qualification</h4>
                    <p><?php echo $qualification; ?>  </p>
                    <?php } ?>
                    
                    <?php if(get_field('research_url','user_' . $userID)) { ?>
                    <h4>Publication URL </h4>
                    <p><a href="<?php echo get_field('research_url','user_' . $userID); ?>" target="_blank"> <?php echo get_field('research_url','user_' . $userID); ?></a></p>
                    <?php } ?>
                    <?php if($curauth->user_email) { ?>
                    <h4>Contact Info</h4>
                    <p><a href="mailto:<?php echo $curauth->user_email; ?>"><?php echo $curauth->user_email; ?></a></p>
                    <?php } ?>
                </div>
            </div>
            <div class="authorDetailArea">
                <div class="authorTabArea">
                    <div class="sidebarDrop">Bio</div>
                    <ul class="authorTab">
                        <?php if($bio) { ?>
                        <li>
                            <a href="#bio">Bio</a>
                        </li>
                        <?php } if(get_field('education_user','user_' . $userID)) { ?>
                        <li>
                            <a href="#education">Education</a>
                        </li>
                        <?php } if($researchId) { ?>
                        <li>
                            <a href="#researchinterest">Research Interest</a>
                        </li>
                        <?php } if($projects_array) { ?>
                        
                        <li>
                            <a href="#projects">Projects</a>
                        </li>
                        <?php  } if($awards_array) { ?>
                        <li>
                            <a href="#awards">Awards & Recognitions</a>
                        </li>
                        <?php } if($news_array) { ?>
                        <li>
                            <a href="#news">News</a>
                        </li>
                        <?php } if($membership_array) { ?>
                        <li>
                            <a href="#membership">Membership</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="authorDetail">
                    <?php if ($bio) { ?>
                        <div id="bio">
                            <h2>Bio</h2>
                            <p><?php echo $bio; ?></p>
                        </div>
                    <?php } ?>
                    <div id="education">
                        <h2>Educations</h2>
                        <?php echo get_field('education_user','user_' . $userID); ?>
                    </div>
                    <div id="researchinterest">
                        <h2>Research Interest</h2>
                        <?php echo $researchId; ?>
                    </div>

                    <!-- <div id="patents">
                        <h2>Patents</h2>
                        <p>Quisque id mi. Sed magna purus, fermentum eu, tincidunt eu, varius ut, felis. Quisque id mi. Praesent egestas neque eu enim. Quisque id mi. Nulla neque dolor, sagittis eget, iaculis quis, molestie non, velit. Pellentesque dapibus hendrerit tortor. Morbi ac felis. Sed libero. Nunc nec neque. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus. Nam ipsum risus, rutrum vitae, vestibulum eu, molestie vel, lacus. Donec venenatis vulputate lorem. Mauris sollicitudin fermentum libero. Aenean vulputate eleifend tellus.</p>
                        <p>Sed aliquam ultrices mauris. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Phasellus gravida semper nisi. Nullam sagittis. Nullam vel sem.</p>
                        <p>Aenean viverra rhoncus pede. Praesent venenatis metus at tortor pulvinar varius. Phasellus blandit leo ut odio. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.</p>
                        <p>Nullam sagittis. Quisque malesuada placerat nisl. Donec venenatis vulputate lorem. Sed in libero ut nibh placerat accumsan. Quisque malesuada placerat nisl. Donec mollis hendrerit risus. Sed hendrerit. Quisque id mi. Proin magna. Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor.</p>
                        <p>In hac habitasse platea dictumst. Quisque malesuada placerat nisl. In dui magna, posuere eget, vestibulum et, tempor auctor, justo. Phasellus a est. Pellentesque posuere.</p>
                        <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed magna purus, fermentum eu, tincidunt eu, varius ut, felis. Proin faucibus arcu quis ante. Fusce vel dui.
                    </div> -->
                    <div id="projects">
                        <h2>Projects</h2>
                        <?php foreach ($projects_array as $project) { ?>
                            <?php if ($project['project_name']) { ?>
                                <h4><?php echo  $project['project_name']; ?></h4>
                            <?php }
                            if ($project['project_description']) { ?>
                                <p><?php echo  $project['project_description']; ?></p>
                            <?php }
                            if ($project['project_pdf']) { ?>
                                <a href="<?php echo  esc_url($project['project_pdf']); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Document', 'acodez'); ?></a>
                            <?php } ?>
                        <?php  } ?>
                    </div>
                    <div id="awards">
                        <h2>Awards & Recognitions</h2>
                        <?php foreach ($awards_array as $award) { ?>
                            <?php if ($award['award_name']) { ?>
                                <h4 class="award-tit"><?php echo  $award['award_name']; ?></h4>
                            <?php }
                            if ($award['award_description']) { ?>
                                <p class="award-cntnt"><?php echo  $award['award_description']; ?></p>
                            <?php }
                            if ($award['award_image']) { ?>
                                <img src="<?php echo  $award['award_image']; ?>" alt="<?php echo  $award['award_name']; ?>">
                            <?php } ?>
                        <?php  } ?>
                    </div>
                    <div id="news">
                        <h2>News</h2>
                        <?php 
                        foreach ($news_array as $news) { ?>
                            <?php if ($news['news_title']) { ?>
                                <h4><?php echo  $news['news_title']; ?></h4>
                            <?php }
                            if ($news['news_link']) { ?>
                                <a href="<?php echo  esc_url($news['news_link']); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Read More', 'acodez'); ?></a>
                            <?php } ?>
                        <?php  } ?>
                    </div>
                    <div id="membership">
                        <h2>Membership</h2>
                        <?php foreach ($membership_array as $membership) { ?>
                            <?php if ($membership['membership_title']) { ?>
                                <h4><?php echo  $membership['membership_title']; ?></h4>
                            <?php }
                            if ($membership['membership_description']) { ?>
                                <p><?php echo  $membership['membership_description']; ?></p>
                            <?php } ?>
                        <?php  } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php get_footer(); ?>