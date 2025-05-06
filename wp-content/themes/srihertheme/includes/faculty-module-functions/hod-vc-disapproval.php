<?php

function hod_vc_disapproval($profile_id)
{

    $values = array("hod_approval" => false,"principal_approval" => false, "vc_approval" => false);


    $profile_image_grp = get_field('fac_image', 'user_' . $profile_id);
        $hod_approval = $profile_image_grp['hod_approval'];
        $principal_approval = $profile_image_grp['principal_approval'];
        $vc_approval = $profile_image_grp['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("fac_image", $values, 'user_' . $profile_id);
    }


    $designation_grp = get_field('fac_designation', 'user_' . $profile_id);
        $hod_approval = $designation_grp['hod_approval'];
        $principal_approval = $designation_grp['principal_approval'];
        $vc_approval = $designation_grp['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("fac_designation", $values, 'user_' . $profile_id);
    }


    $qualification_grp = get_field('fac_qualification', 'user_' . $profile_id);
        $hod_approval = $qualification_grp['hod_approval'];
        $principal_approval = $qualification_grp['principal_approval'];
        $vc_approval = $qualification_grp['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("fac_qualification", $values, 'user_' . $profile_id);
    }

    $research_interest = get_field('fac_research_interest', 'user_' . $profile_id);
        $hod_approval = $research_interest['hod_approval'];
        $principal_approval = $research_interest['principal_approval'];
        $vc_approval = $research_interest['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("fac_research_interest", $values, 'user_' . $profile_id);
    }


    $contact_number_grp = get_field('contact_number', 'user_' . $profile_id);
        $hod_approval = $contact_number_grp['hod_approval'];
        $principal_approval = $contact_number_grp['principal_approval'];
        $vc_approval = $contact_number_grp['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("contact_number", $values, 'user_' . $profile_id);
    }


    $bio_grp = get_field('fac_bio_grp', 'user_' . $profile_id);
        $hod_approval = $bio_grp['hod_approval'];
        $principal_approval = $bio_grp['principal_approval'];
        $vc_approval = $bio_grp['vc_approval'];

    if (($hod_approval === false) && ($vc_approval === true)) {
        update_field("fac_bio_grp", $values, 'user_' . $profile_id);
    }


    $projects_grp = get_field('fac_projects', 'user_' . $profile_id);
     if (!empty($projects_grp)) {
        if (have_rows('fac_projects', 'user_' . $profile_id)) :
            while (have_rows('fac_projects', 'user_' . $profile_id)) : the_row();

                $approval = get_sub_field('approval');
                $hod_approvals = $approval['hod_approval'];
                $vc_approvals = $approval['vc_approval'];

                if (($hod_approvals === false) && ($vc_approvals === true)) {
                    update_sub_field("approval", $values, 'user_' . $profile_id);
                }
            endwhile;
        endif;
    }


    $awards_recognitions_grp = get_field('awards_recognitions', 'user_' . $profile_id);
     if (!empty($awards_recognitions_grp)) {
        if (have_rows('awards_recognitions', 'user_' . $profile_id)) :
            while (have_rows('awards_recognitions', 'user_' . $profile_id)) : the_row();
                $approval = get_sub_field('approval');
                $hod_approvals = $approval['hod_approval'];
                $vc_approvals = $approval['vc_approval'];

                if (($hod_approvals === false) && ($vc_approvals === true)) {
                    update_sub_field("approval", $values, 'user_' . $profile_id);
                }
            endwhile;
        endif;
    }


    $news_grp = get_field('fac_news', 'user_' . $profile_id);
     if (!empty($news_grp)) {
        if (have_rows('fac_news', 'user_' . $profile_id)) :
            while (have_rows('fac_news', 'user_' . $profile_id)) : the_row();
                $approval = get_sub_field('approval');
                $hod_approvals = $approval['hod_approval'];
                $vc_approvals = $approval['vc_approval'];

                if (($hod_approvals === false) && ($vc_approvals === true)) {
                    update_sub_field("approval", $values, 'user_' . $profile_id);
                }

            endwhile;
        endif;
    }


    $membership_grp = get_field('fac_membership', 'user_' . $profile_id);
     if (!empty($membership_grp)) {
        if (have_rows('fac_membership', 'user_' . $profile_id)) :
            while (have_rows('fac_membership', 'user_' . $profile_id)) : the_row();

                $approval = get_sub_field('approval');
                $hod_approvals = $approval['hod_approval'];
                $vc_approvals = $approval['vc_approval'];

                if (($hod_approvals === false) && ($vc_approvals === true)) {
                    update_sub_field("approval", $values, 'user_' . $profile_id);
                }
            endwhile;
        endif;
    }


    $student_guidance_grp = get_field('student_guidance', 'user_' . $profile_id);
     if (!empty($student_guidance_grp)) {
        if (have_rows('student_guidance', 'user_' . $profile_id)) :
            while (have_rows('student_guidance', 'user_' . $profile_id)) : the_row();

                $approval = get_sub_field('approval');
                $hod_approvals = $approval['hod_approval'];
                $vc_approvals = $approval['vc_approval'];

                if (($hod_approvals === false) && ($vc_approvals === true)) {
                    update_sub_field("approval", $values, 'user_' . $profile_id);
                }

            endwhile;
        endif;
    }
}
