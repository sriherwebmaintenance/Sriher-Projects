<?php

function overall_approvals($profile_id)
{

    $values = array("hod_approval" => true, "principal_approval" => true,"vc_approval" => true);


    update_field("fac_image", $values, $profile_id);
    update_field("fac_designation", $values, $profile_id);
    update_field("fac_qualification", $values, $profile_id);
    update_field("fac_research_interest", $values,  $profile_id);
    update_field("contact_number", $values,  $profile_id);
    update_field("fac_bio_grp", $values, $profile_id);


    

    $fac_projects_grp = get_field('fac_projects', $profile_id);
    if (!empty($fac_projects_grp)) {

        if (have_rows('fac_projects', $profile_id)) :
            while (have_rows('fac_projects', $profile_id)) : the_row();
                $approval = get_sub_field('approval');

                if ($approval['hod_approval']) {
                    update_sub_field("approval", $values, $profile_id);
                }
            endwhile;
        endif;
    }

    $awards_recognitions_grp = get_field('awards_recognitions', $profile_id);
    if (!empty($awards_recognitions_grp)) {

        if (have_rows('awards_recognitions', $profile_id)) :
            while (have_rows('awards_recognitions', $profile_id)) : the_row();
                $approval = get_sub_field('approval');

                if ($approval['hod_approval']) {
                    update_sub_field("approval", $values, $profile_id);
                }
            endwhile;
        endif;
    }


    $fac_news_grp = get_field('fac_news', $profile_id);
    if (!empty($fac_news_grp)) {

        if (have_rows('fac_news', $profile_id)) :
            while (have_rows('fac_news', $profile_id)) : the_row();
                $approval = get_sub_field('approval');

                if ($approval['hod_approval']) {
                    update_sub_field("approval", $values, $profile_id);
                }
            endwhile;
        endif;
    }

    $fac_membership_grp = get_field('fac_membership', $profile_id);
    if (!empty($fac_membership_grp)) {

        if (have_rows('fac_membership', $profile_id)) :
            while (have_rows('fac_membership', $profile_id)) : the_row();
                $approval = get_sub_field('approval');

                if ($approval['hod_approval']) {
                    update_sub_field("approval", $values, $profile_id);
                }
            endwhile;
        endif;
    }
    
}
