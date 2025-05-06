<?php

/*-------------Update Notification Manager-------------*/
add_action('profile_update', 'update_notification_controller', 20);
add_action('user_update', 'update_notification_controller', 20);

function update_notification_controller($profile_id)
{
    $current_user = wp_get_current_user();

    if (is_admin() && in_array('faculty', (array) $current_user->roles)) {

        $mail_flag = 0;
        $values = array("hod_approval" => false, "vc_approval" => false);

        $profile_image_grp = get_field('fac_image', 'user_' . $profile_id);
        $current_profile_image = $profile_image_grp['current_profile_image'];
        $approved_profile_image = $profile_image_grp['frontend_profile_image'];
        $base64Image1 = '';
        $base64Image2 = '';
        if(!compareImages($current_profile_image, $approved_profile_image)){
            $mail_flag = 1;
            update_field("fac_image", $values, 'user_' . $profile_id);
        }

        $designation_grp = get_field('fac_designation', 'user_' . $profile_id);
        $current_designation = $designation_grp['current_designation'];
        $approved_designation = $designation_grp['frontend_designation'];
        if ($current_designation !== $approved_designation) {
            $mail_flag = 1;
            update_field("fac_designation", $values, 'user_' . $profile_id);
        }

        $qualification_grp = get_field('fac_qualification', 'user_' . $profile_id);
        $current_qualification = $qualification_grp['current_qualification'];
        $approved_qualification = $qualification_grp['frontend_qualification'];
        if ($current_qualification !== $approved_qualification) {
            $mail_flag = 1;
            update_field("fac_qualification", $values, 'user_' . $profile_id);
        }

        $research_grp = get_field('fac_research_interest', 'user_' . $profile_id);
        $current_research = $research_grp['current_research_interest'];
        $approved_research = $research_grp['frontend_research_interest'];
        if ($current_research !== $approved_research) {
            $mail_flag = 1;
            update_field("fac_research_interest", $values, 'user_' . $profile_id);
        }

        $contact_number_grp = get_field('contact_number', 'user_' . $profile_id);

        $current_contact_number = $contact_number_grp['current_contact_number'];
        $approved_contact_number = $contact_number_grp['frontend_contact_number'];
        if ($current_contact_number != $approved_contact_number) {
            $mail_flag = 1;

            update_field("contact_number", $values, 'user_' . $profile_id,  true);
        }

        $bio_grp = get_field('fac_bio_grp', 'user_' . $profile_id);
        $current_bio = $bio_grp['current_bio'];
        $approved_bio = $bio_grp['frontend_bio'];
        if ($current_bio !== $approved_bio) {
            $mail_flag = 1;
            update_field("fac_bio_grp", $values, 'user_' . $profile_id);
        }


        $projects_grp = get_field('fac_projects', 'user_' . $profile_id);
        if (!empty($projects_grp)) {
            if (have_rows('fac_projects', 'user_' . $profile_id)) :
                while (have_rows('fac_projects', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');

                    $current_project_name = $current_data['project_name'];
                    $showing_project_name = $showing_data['project_name'];
                    if ($current_project_name != $showing_project_name) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_project_des = $current_data['project_description'];
                    $showing_project_des = $showing_data['project_description'];
                    if ($current_project_des != $showing_project_des) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_project_pdf = $current_data['project_pdf'];
                    $showing_project_pdf = $showing_data['project_pdf'];
                    if ($current_project_pdf != $showing_project_pdf) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        $awards_recognitions_grp = get_field('awards_recognitions', 'user_' . $profile_id);
        if (!empty($awards_recognitions_grp)) {
            if (have_rows('awards_recognitions', 'user_' . $profile_id)) :
                while (have_rows('awards_recognitions', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');

                    $current_award_name = $current_data['award_name'];
                    $showing_award_name = $showing_data['award_name'];
                    if ($current_award_name != $showing_award_name) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_award_des = $current_data['award_description'];
                    $showing_award_des = $showing_data['award_description'];
                    if ($current_award_des != $showing_award_des) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_award_image = $current_data['award_image'];
                    $showing_award_image = $showing_data['award_image'];
                    if ($current_award_image != $showing_award_image) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        $news_grp = get_field('fac_news', 'user_' . $profile_id);
        if (!empty($news_grp)) {
            if (have_rows('fac_news', 'user_' . $profile_id)) :
                while (have_rows('fac_news', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');

                    $current_news_title = $current_data['news_title'];
                    $showing_news_title = $showing_data['news_title'];
                    if ($current_news_title != $showing_news_title) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_news_link = $current_data['news_link'];
                    $showing_news_link = $showing_data['news_link'];
                    if ($current_news_link != $showing_news_link) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        $membership_grp = get_field('fac_membership', 'user_' . $profile_id);
        if (!empty($membership_grp)) {
            if (have_rows('fac_membership', 'user_' . $profile_id)) :
                while (have_rows('fac_membership', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');

                    $current_membership_title = $current_data['membership_title'];
                    $showing_membership_title = $showing_data['membership_title'];
                    if ($current_membership_title != $showing_membership_title) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_membership_des = $current_data['membership_description'];
                    $showing_membership_des = $showing_data['membership_description'];
                    if ($current_membership_des != $showing_membership_des) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        $student_guidance_grp = get_field('student_guidance', 'user_' . $profile_id);
        if (!empty($student_guidance_grp)) {
            if (have_rows('student_guidance', 'user_' . $profile_id)) :
                while (have_rows('student_guidance', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');

                    $current_student_guidance_title = $current_data['student_guidance_title'];
                    $showing_student_guidance_title = $showing_data['student_guidance_title'];
                    if ($current_student_guidance_title != $showing_student_guidance_title) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                    $current_student_guidance_des = $current_data['student_guidance_description'];
                    $showing_student_guidance_des = $showing_data['student_guidance_description'];
                    if ($current_student_guidance_des != $showing_student_guidance_des) {
                        $mail_flag = 1;
                        update_sub_field("approval", $values, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        if ($mail_flag == 1) {

            $assos_hod = get_field('fac_hod', 'user_' . $profile_id);
            $hod = get_userdata($assos_hod);
            $recipient = $hod->data->user_email;

            if (is_user_logged_in() && $hod && $hod->ID == get_current_user_id()) {

                $profile_link = admin_url("user-edit.php?user_id={$profile_id}");
            } else {

                $profile_link = wp_login_url();
            }


            $headers = array('Content-Type: text/html; charset=UTF-8');
            $subject = 'Profile Updated  ' . $current_user->display_name;
            $message = ' 
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html 
            xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta http-equiv="X-UA-Compatible" 
            content="IE=edge" /> <meta name="format-detection" content="telephone=no" /> <meta name="viewport" 
            content="width=device-width, initial-scale=1.0" /> <title>Profile Updated</title> <style type="text/css" emogrify="no"> 
            #outlook a { padding: 0; } .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span,
            .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse;
            mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader 
            { display: none !important; mso-hide: all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height:
            0px !important; font-size: 0px !important; } body { width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 
            100%; margin: 0; padding: 0; } img { outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a img 
            { border: none; } table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; } th { font-weight: normal; 
            text-align: left; } *[class="gmail-fix"] { display: none !important; } </style> <style type="text/css" emogrify="no"> @media 
            (max-width: 600px) { .gmx-killpill { content: " \03D1"; } } </style> <style type="text/css" emogrify="no"> @media (max-width: 600px) 
            { .gmx-killpill { content: " \03D1"; } .r0-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px 
            !important; } .r1-i { background-color: #ffffff !important; } .r2-c { box-sizing: border-box !important; text-align: center 
            !important; valign: top !important; width: 100% !important; } .r3-o { border-style: solid !important; margin: 0 auto 0 auto 
            !important; width: 100% !important; } .r4-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 
            15px !important; padding-top: 20px !important; } .r5-c { box-sizing: border-box !important; display: block !important; 
            valign: top !important; width: 100% !important; } .r6-o { border-style: solid !important; width: 100% !important; } .r7-i { 
            background-color: #105c8e !important; padding-bottom: 10px !important; padding-left: 0px !important; padding-right: 0px !important; 
            padding-top: 10px !important; } .r8-o { border-style: solid !important; margin: 0 auto 0 auto !important; margin-bottom: 
            10px !important; margin-top: 10px !important; width: 100% !important; } .r9-i { padding-left: 0px !important; padding-right: 
            0px !important; } .r10-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 
            100% !important; } .r11-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important; } 
            .r12-i { padding-left: 15px !important; padding-right: 15px !important; padding-top: 15px !important; text-align: 
            left !important; } .r13-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important; } 
            .r14-i { background-color: transparent !important; } .r15-i { padding-bottom: 15px !important; padding-left: 25px !important; 
            padding-right: 25px !important; padding-top: 15px !important; text-align: left !important; } .r16-i { color: #3b3f44 
            !important; padding-left: 0px !important; padding-right: 0px !important; } .r17-o { border-style: solid !important; margin: 0 
            auto 0 auto !important; margin-bottom: 15px !important; margin-top: 15px !important; width: 100% !important; } 
            .r18-i { text-align: center !important; } .r19-r { background-color: #092046 !important; border-color: #ffffff !important; 
            border-radius: 8px !important; border-width: 0px !important; box-sizing: border-box; height: initial !important; 
            padding-bottom: 12px !important; padding-left: 5px !important; padding-right: 5px !important; padding-top: 12px !important; 
            text-align: center !important; width: 100% !important; } .r20-i { background-color: transparent !important; color: #3b3f44 
            !important; } .r21-c { box-sizing: border-box !important; width: 100% !important; } .r22-i { color: #3b3f44 !important; 
            font-size: 0px !important; padding-bottom: 15px !important; padding-left: 65px !important; padding-right: 65px !important; 
            padding-top: 15px !important; } .r23-c { box-sizing: border-box !important; width: 32px !important; } .r24-o { 
            border-style: solid !important; margin-right: 8px !important; width: 32px !important; } .r25-i { color: #3b3f44 !important; 
            padding-bottom: 5px !important; padding-top: 5px !important; } .r26-o { border-style: solid !important; margin-right: 0px 
            !important; width: 32px !important; } .r27-i { background-color: #092046 !important; padding-bottom: 20px !important; 
            padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important; } .r28-i { color: #3b3f44 
            !important; padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important; } body 
            { -webkit-text-size-adjust: none; } .nl2go-responsive-hide { display: none; } .nl2go-body-table { min-width: unset !important; }
            .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; 
            border: none !important; } .resp-table { display: inline-table !important; } .magic-resp { display: table-cell !important; } } 
            </style> <style type="text/css"> p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #ffffff; text-decoration: 
            underline; } .nl2go-default-textstyle { color: #3b3f44; font-family: Verdana; font-size: 16px; line-height: 1.5; word-break: 
            break-word; } .default-button { color: #000000; font-family: Verdana; font-size: 16px; font-style: normal; font-weight: bold; 
            line-height: 1.15; text-decoration: none; word-break: break-word; } .default-heading1 { color: #1f2d3d; font-family: Verdana; 
            font-size: 36px; word-break: break-word; } .default-heading2 { color: #1f2d3d; font-family: Verdana; font-size: 32px; word-
            break: break-word; } .default-heading3 { color: #1f2d3d; font-family: Verdana; font-size: 24px; word-break: break-word; } .
            default-heading4 { color: #1f2d3d; font-family: Verdana; font-size: 18px; word-break: break-word; } a[x-apple-data-detectors] 
            { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit 
            !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: n
            one; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: 
            fixed; visibility: hidden; width: 0; } </style> <!--[if mso ]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> <
            o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><! [endif]--> <style type="text/css"> a:link 
            { color: #fff; text-decoration: underline; } </style> </head> <body bgcolor="#ffffff" text="#3b3f44" link="#ffffff" 
            yahoo="fix" style="background-color: #ffffff" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" 
            class="nl2go-body-table" width="100%" style="background-color: #ffffff; width: 100%" > <tr> <td> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="600" align="center" class="r0-o" style="table-layout: fixed; width: 
            600px" > <tr> <td valign="top" class="r1-i" style="background-color: #ffffff"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> 
            <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" 
            border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style=" background-color: #105c8e; 
            font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" 
            style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r7-i" style=" background-color: #105c8e; 
            padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; " > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="300" class="r8-o" style=" table-layout: fixed; width: 300px; " > 
            <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> <tr> 
            <td style=" font-size: 0px; line-height: 0px; " > <img src="https://img.mailinblue.com/6573358/images/content_library/original/651cf0e620d2f777295fb10c.png" width="300" border="0" style=" display: block; width: 100%; " /> 
            </td> </tr> <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 
            100%" > <tr> <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > 
            <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
            width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
            cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
            <td align="left" valign="top" class="r12-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 
            16px; line-height: 1.5; word-break: break-word; padding-left: 15px; padding-right: 15px; padding-top: 15px; text-align: left; "
              
           > <div> <p style="margin: 0">Hi ' . $hod->data->display_name . ',</p> </div> </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" 

           align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" style=" table-
           layout: fixed; width: 570px; " > <tr> <td height="30" class="r14-i" style=" font-size: 30px; line-height: 30px; background-
           color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r10-c" align="left"> <table cellspacing="0" cellpadding=
           "0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> <td align="left" 
           valign="top" class="r15-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px; word-break: 
           break-word; line-height: 1.5; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 15px; text-align: 

           left; " > <div> <p style="margin: 0"> ' . $current_user->display_name . ' has just updated their profile. To ensure the accuracy of 
           their information, please take a moment to review and verify the changes made. </p> </div> </td> </tr> </table> </td> </tr> 

           </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> <td class="r4-i" 
           style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >
           <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r16-i" style=" 
           color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 16px; padding-left: 15px; padding-right: 15px; " > <table 
           width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table 
           cellspacing="0" cellpadding="0" border="0" role="presentation" width="114" class="r17-o" style=" table-layout: fixed; width: 114px; " > 
           <tr class="nl2go-responsive-hide"> <td height="15" style=" font-size: 15px; line-height: 15px; "></td> </tr> <tr> <td height="18" 
           align="center" valign="top" class="r18-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px;

           line-height: 1.5; word-break: break-word; " > <a href="' . $profile_link . '" class="r19-r default-button" target="_blank" data-btn="1"

          style=" font-style: normal; font-weight: bold; line-height: 1.15; text-decoration: none; word-break: break-word; border-style: solid; 
          word-wrap: break-word; display: block; -webkit-text-size-adjust: none; background-color: #092046; border-color: #ffffff; border-radius: 
          8px; border-width: 0px; color: #ffffff; font-family: arial, helvetica, sans-serif; font-size: 16px; height: 18px; mso-hide: all; 
          padding-bottom: 12px; padding-left: 5px; padding-right: 5px; padding-top: 12px; width: 104px; " > <span ><span style=" font-family: 
          Lucida sans unicode, lucida grande, sans-serif, arial; " >Click Here</span ></span ></a > <!--<![endif]--> </td> </tr> <tr class="
          nl2go-responsive-hide"> <td height="15" style=" font-size: 15px; line-height: 15px; " > </td> </tr> </table> </td> </tr> <tr> 
          <td class="r13-c" align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td height="30" class="r20-i" style=" font-size: 30px; line-height: 30px; 
          background-color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" align="center"> <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="570" align="center" class="r3-o" style=" table-layout: fixed; width: 570px; " > 
          <tr> <td valign="top"> <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r21-c" 
          style=" display: inline-block; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r6-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td class="r22-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 16px; padding-bottom: 15px; padding-left: 206px; padding-right: 206px; padding-top: 15px; " > <table width="100%" cellspacing="0" 
          cellpadding="0" border="0" role="presentation" > <tr> <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > 
          <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 
          100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://facebook.com/SRIHER.Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/facebook_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="42" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://instagram.com/sriher.official" 
          target="_blank" style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/instagram_32px.png" width="32" border="0" style=" display: block; width: 100%; " /></a> </td> 
          <td class="nl2go-responsive-hide" width="10" style=" font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> 
          <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 100%; " > 
          <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://twitter.com/SRIHER_Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/twitter_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="32" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r26-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://youtube.com/channel/UC3c7OTHOy2wT3grS0aVtl6Q" target="_blank" 
          style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/youtube_32px.png" width="32" 
          border="0" style=" display: block; width: 100%; " /></a> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> </td> </tr> 
          </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0"
           cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > 
           <tr> <td class="r27-i" style=" background-color: #092046; padding-bottom: 20px; padding-top: 20px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: 
           normal" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
           width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
           cellpadding="0" border="0" role="presentation" width=var"100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
           <td align="center" valign="top" class="r28-i nl2go-default-textstyle" style=" font-family: Verdana; word-break: break-word; 
           background-image: url("https://www.sriramachandra.edu.in/"); color: #3b3f44; font-size: 18px; line-height: 3; padding-bottom: 0px; 
           padding-top: 0px; text-align: center; " > <div> <p style=" margin: 0; font-size: 14px; " > <span style=" color: #ffffff; font-family: 
           Arial; font-size: 16px; " >© 2023 All Rights Reserved</span ><a href="https://www.sriramachandra.edu.in/" title="Shriher" 
           target="_blank" style=" color: #fff; text-decoration: none; " ><span style=" color: #ffffff; font-family: Arial; font-size: 16px;
            " > sriramachandra.edu.in</span ></a > </p> </div> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </body></html>';


            wp_mail($recipient, $subject, $message, $headers);
        }
    } elseif (is_admin() && in_array('hod', (array) $current_user->roles)) {

        $faculty_data = get_userdata($profile_id);
        $hod_id = get_field('fac_hod', 'user_' . $profile_id);
        $hod = get_userdata($hod_id);
        $query = new WP_User_Query(array(
            'role' => 'vc',
        ));
        $vc_details = $query->get_results();
        $firstUser = $vc_details[0];
        $vc_mail = $firstUser->user_email;
        $vc_name = $firstUser->display_name;

        //feedback
        if (!empty(get_field("fac_feedback", 'user_' . $profile_id))) {
            $feedback = " and regarding the feedback," . get_field("fac_feedback", 'user_' . $profile_id);
        } else {
            $feedback = 'no additional feedback has been provided';
        }

        /***************------------------ Notification to VC ----------------***********/
        $recipient = $vc_mail;

        if (is_user_logged_in() && $vc_details && ($vc_details->ID == get_current_user_id())) {

            $profile_link = admin_url("user-edit.php?user_id={$profile_id}");
        } else {

            $profile_link = wp_login_url();
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = 'Request for Verification of  ' . $faculty_data->display_name;
        $message = ' 
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html 
            xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta http-equiv="X-UA-Compatible" 
            content="IE=edge" /> <meta name="format-detection" content="telephone=no" /> <meta name="viewport" 
            content="width=device-width, initial-scale=1.0" /> <title>Profile Updated</title> <style type="text/css" emogrify="no"> 
            #outlook a { padding: 0; } .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span,
            .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse;
            mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader 
            { display: none !important; mso-hide: all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height:
            0px !important; font-size: 0px !important; } body { width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 
            100%; margin: 0; padding: 0; } img { outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a img 
            { border: none; } table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; } th { font-weight: normal; 
            text-align: left; } *[class="gmail-fix"] { display: none !important; } </style> <style type="text/css" emogrify="no"> @media 
            (max-width: 600px) { .gmx-killpill { content: " \03D1"; } } </style> <style type="text/css" emogrify="no"> @media (max-width: 600px) 
            { .gmx-killpill { content: " \03D1"; } .r0-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px 
            !important; } .r1-i { background-color: #ffffff !important; } .r2-c { box-sizing: border-box !important; text-align: center 
            !important; valign: top !important; width: 100% !important; } .r3-o { border-style: solid !important; margin: 0 auto 0 auto 
            !important; width: 100% !important; } .r4-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 
            15px !important; padding-top: 20px !important; } .r5-c { box-sizing: border-box !important; display: block !important; 
            valign: top !important; width: 100% !important; } .r6-o { border-style: solid !important; width: 100% !important; } .r7-i { 
            background-color: #105c8e !important; padding-bottom: 10px !important; padding-left: 0px !important; padding-right: 0px !important; 
            padding-top: 10px !important; } .r8-o { border-style: solid !important; margin: 0 auto 0 auto !important; margin-bottom: 
            10px !important; margin-top: 10px !important; width: 100% !important; } .r9-i { padding-left: 0px !important; padding-right: 
            0px !important; } .r10-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 
            100% !important; } .r11-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important; } 
            .r12-i { padding-left: 15px !important; padding-right: 15px !important; padding-top: 15px !important; text-align: 
            left !important; } .r13-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important; } 
            .r14-i { background-color: transparent !important; } .r15-i { padding-bottom: 15px !important; padding-left: 25px !important; 
            padding-right: 25px !important; padding-top: 15px !important; text-align: left !important; } .r16-i { color: #3b3f44 
            !important; padding-left: 0px !important; padding-right: 0px !important; } .r17-o { border-style: solid !important; margin: 0 
            auto 0 auto !important; margin-bottom: 15px !important; margin-top: 15px !important; width: 100% !important; } 
            .r18-i { text-align: center !important; } .r19-r { background-color: #092046 !important; border-color: #ffffff !important; 
            border-radius: 8px !important; border-width: 0px !important; box-sizing: border-box; height: initial !important; 
            padding-bottom: 12px !important; padding-left: 5px !important; padding-right: 5px !important; padding-top: 12px !important; 
            text-align: center !important; width: 100% !important; } .r20-i { background-color: transparent !important; color: #3b3f44 
            !important; } .r21-c { box-sizing: border-box !important; width: 100% !important; } .r22-i { color: #3b3f44 !important; 
            font-size: 0px !important; padding-bottom: 15px !important; padding-left: 65px !important; padding-right: 65px !important; 
            padding-top: 15px !important; } .r23-c { box-sizing: border-box !important; width: 32px !important; } .r24-o { 
            border-style: solid !important; margin-right: 8px !important; width: 32px !important; } .r25-i { color: #3b3f44 !important; 
            padding-bottom: 5px !important; padding-top: 5px !important; } .r26-o { border-style: solid !important; margin-right: 0px 
            !important; width: 32px !important; } .r27-i { background-color: #092046 !important; padding-bottom: 20px !important; 
            padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important; } .r28-i { color: #3b3f44 
            !important; padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important; } body 
            { -webkit-text-size-adjust: none; } .nl2go-responsive-hide { display: none; } .nl2go-body-table { min-width: unset !important; }
            .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; 
            border: none !important; } .resp-table { display: inline-table !important; } .magic-resp { display: table-cell !important; } } 
            </style> <style type="text/css"> p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #ffffff; text-decoration: 
            underline; } .nl2go-default-textstyle { color: #3b3f44; font-family: Verdana; font-size: 16px; line-height: 1.5; word-break: 
            break-word; } .default-button { color: #000000; font-family: Verdana; font-size: 16px; font-style: normal; font-weight: bold; 
            line-height: 1.15; text-decoration: none; word-break: break-word; } .default-heading1 { color: #1f2d3d; font-family: Verdana; 
            font-size: 36px; word-break: break-word; } .default-heading2 { color: #1f2d3d; font-family: Verdana; font-size: 32px; word-
            break: break-word; } .default-heading3 { color: #1f2d3d; font-family: Verdana; font-size: 24px; word-break: break-word; } .
            default-heading4 { color: #1f2d3d; font-family: Verdana; font-size: 18px; word-break: break-word; } a[x-apple-data-detectors] 
            { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit 
            !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: n
            one; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: 
            fixed; visibility: hidden; width: 0; } </style> <!--[if mso ]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> <
            o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><! [endif]--> <style type="text/css"> a:link 
            { color: #fff; text-decoration: underline; } </style> </head> <body bgcolor="#ffffff" text="#3b3f44" link="#ffffff" 
            yahoo="fix" style="background-color: #ffffff" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" 
            class="nl2go-body-table" width="100%" style="background-color: #ffffff; width: 100%" > <tr> <td> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="600" align="center" class="r0-o" style="table-layout: fixed; width: 
            600px" > <tr> <td valign="top" class="r1-i" style="background-color: #ffffff"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> 
            <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" 
            border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style=" background-color: #105c8e; 
            font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" 
            style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r7-i" style=" background-color: #105c8e; 
            padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; " > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="300" class="r8-o" style=" table-layout: fixed; width: 300px; " > 
            <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> <tr> 
            <td style=" font-size: 0px; line-height: 0px; " > <img src="https://img.mailinblue.com/6573358/images/content_library/original/651cf0e620d2f777295fb10c.png" width="300" border="0" style=" display: block; width: 100%; " /> 
            </td> </tr> <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 
            100%" > <tr> <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > 
            <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
            width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
            cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
            <td align="left" valign="top" class="r12-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 
            16px; line-height: 1.5; word-break: break-word; padding-left: 15px; padding-right: 15px; padding-top: 15px; text-align: left; "
              
           > <div> <p style="margin: 0">Hi ' . $vc_name . ',</p> </div> </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" 

           align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" style=" table-
           layout: fixed; width: 570px; " > <tr> <td height="30" class="r14-i" style=" font-size: 30px; line-height: 30px; background-
           color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r10-c" align="left"> <table cellspacing="0" cellpadding=
           "0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> <td align="left" 
           valign="top" class="r15-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px; word-break: 
           break-word; line-height: 1.5; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 15px; text-align: 

           left; " > <div> <p style="margin: 0"> ' . $faculty_data->display_name . '\'s profile has been verified by the Head of Department,'
            . ' ' . $hod->display_name . ' ' . $feedback . '. Your esteemed verification, Vice Chancellor, is now awaited to ensure its utmost accuracy and completeness.
           </p> </div> </td> </tr> 
           </table> </td> </tr> 

           </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> <td class="r4-i" 
           style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >
           <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r16-i" style=" 
           color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 16px; padding-left: 15px; padding-right: 15px; " > <table 
           width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table 
           cellspacing="0" cellpadding="0" border="0" role="presentation" width="114" class="r17-o" style=" table-layout: fixed; width: 114px; " > 
           <tr class="nl2go-responsive-hide"> <td height="15" style=" font-size: 15px; line-height: 15px; "></td> </tr> <tr> <td height="18" 
           align="center" valign="top" class="r18-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px;

           line-height: 1.5; word-break: break-word; " > <a href="' . $profile_link . '" class="r19-r default-button" target="_blank" data-btn="1"

          style=" font-style: normal; font-weight: bold; line-height: 1.15; text-decoration: none; word-break: break-word; border-style: solid; 
          word-wrap: break-word; display: block; -webkit-text-size-adjust: none; background-color: #092046; border-color: #ffffff; border-radius: 
          8px; border-width: 0px; color: #ffffff; font-family: arial, helvetica, sans-serif; font-size: 16px; height: 18px; mso-hide: all; 
          padding-bottom: 12px; padding-left: 5px; padding-right: 5px; padding-top: 12px; width: 104px; " > <span ><span style=" font-family: 
          Lucida sans unicode, lucida grande, sans-serif, arial; " >Click Here</span ></span ></a > <!--<![endif]--> </td> </tr> <tr class="
          nl2go-responsive-hide"> <td height="15" style=" font-size: 15px; line-height: 15px; " > </td> </tr> </table> </td> </tr> <tr> 
          <td class="r13-c" align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td height="30" class="r20-i" style=" font-size: 30px; line-height: 30px; 
          background-color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" align="center"> <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="570" align="center" class="r3-o" style=" table-layout: fixed; width: 570px; " > 
          <tr> <td valign="top"> <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r21-c" 
          style=" display: inline-block; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r6-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td class="r22-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 16px; padding-bottom: 15px; padding-left: 206px; padding-right: 206px; padding-top: 15px; " > <table width="100%" cellspacing="0" 
          cellpadding="0" border="0" role="presentation" > <tr> <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > 
          <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 
          100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://facebook.com/SRIHER.Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/facebook_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="42" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://instagram.com/sriher.official" 
          target="_blank" style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/instagram_32px.png" width="32" border="0" style=" display: block; width: 100%; " /></a> </td> 
          <td class="nl2go-responsive-hide" width="10" style=" font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> 
          <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 100%; " > 
          <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://twitter.com/SRIHER_Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/twitter_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="32" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r26-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://youtube.com/channel/UC3c7OTHOy2wT3grS0aVtl6Q" target="_blank" 
          style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/youtube_32px.png" width="32" 
          border="0" style=" display: block; width: 100%; " /></a> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> </td> </tr> 
          </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0"
           cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > 
           <tr> <td class="r27-i" style=" background-color: #092046; padding-bottom: 20px; padding-top: 20px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: 
           normal" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
           width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
           cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
           <td align="center" valign="top" class="r28-i nl2go-default-textstyle" style=" font-family: Verdana; word-break: break-word; 
           background-image: url("https://www.sriramachandra.edu.in/"); color: #3b3f44; font-size: 18px; line-height: 3; padding-bottom: 0px; 
           padding-top: 0px; text-align: center; " > <div> <p style=" margin: 0; font-size: 14px; " > <span style=" color: #ffffff; font-family: 
           Arial; font-size: 16px; " >© 2023 All Rights Reserved</span ><a href="https://www.sriramachandra.edu.in/" title="Shriher" 
           target="_blank" style=" color: #fff; text-decoration: none; " ><span style=" color: #ffffff; font-family: Arial; font-size: 16px;
            " > sriramachandra.edu.in</span ></a > </p> </div> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </body></html>';
        $faculty_data = get_userdata($profile_id);

        if (in_array('faculty', (array) $faculty_data->roles)) {

            wp_mail($recipient, $subject, $message, $headers);
        }

        /***************------------------ Notification to Faculty ----------------***********/
        $faculty_data = get_userdata($profile_id);

        $recipient = $faculty_data->user_email;
        $profile_link = admin_url("user-edit.php?user_id={$profile_id}");
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = 'Profile verified by HOD(' . $hod->data->display_name . ')';
        $message = ' 
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html 
            xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta http-equiv="X-UA-Compatible" 
            content="IE=edge" /> <meta name="format-detection" content="telephone=no" /> <meta name="viewport" 
            content="width=device-width, initial-scale=1.0" /> <title>Profile Updated</title> <style type="text/css" emogrify="no"> 
            #outlook a { padding: 0; } .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span,
            .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse;
            mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader 
            { display: none !important; mso-hide: all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height:
            0px !important; font-size: 0px !important; } body { width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 
            100%; margin: 0; padding: 0; } img { outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a img 
            { border: none; } table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; } th { font-weight: normal; 
            text-align: left; } *[class="gmail-fix"] { display: none !important; } </style> <style type="text/css" emogrify="no"> @media 
            (max-width: 600px) { .gmx-killpill { content: " \03D1"; } } </style> <style type="text/css" emogrify="no"> @media (max-width: 600px) 
            { .gmx-killpill { content: " \03D1"; } .r0-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px 
            !important; } .r1-i { background-color: #ffffff !important; } .r2-c { box-sizing: border-box !important; text-align: center 
            !important; valign: top !important; width: 100% !important; } .r3-o { border-style: solid !important; margin: 0 auto 0 auto 
            !important; width: 100% !important; } .r4-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 
            15px !important; padding-top: 20px !important; } .r5-c { box-sizing: border-box !important; display: block !important; 
            valign: top !important; width: 100% !important; } .r6-o { border-style: solid !important; width: 100% !important; } .r7-i { 
            background-color: #105c8e !important; padding-bottom: 10px !important; padding-left: 0px !important; padding-right: 0px !important; 
            padding-top: 10px !important; } .r8-o { border-style: solid !important; margin: 0 auto 0 auto !important; margin-bottom: 
            10px !important; margin-top: 10px !important; width: 100% !important; } .r9-i { padding-left: 0px !important; padding-right: 
            0px !important; } .r10-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 
            100% !important; } .r11-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important; } 
            .r12-i { padding-left: 15px !important; padding-right: 15px !important; padding-top: 15px !important; text-align: 
            left !important; } .r13-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important; } 
            .r14-i { background-color: transparent !important; } .r15-i { padding-bottom: 15px !important; padding-left: 25px !important; 
            padding-right: 25px !important; padding-top: 15px !important; text-align: left !important; } .r16-i { color: #3b3f44 
            !important; padding-left: 0px !important; padding-right: 0px !important; } .r17-o { border-style: solid !important; margin: 0 
            auto 0 auto !important; margin-bottom: 15px !important; margin-top: 15px !important; width: 100% !important; } 
            .r18-i { text-align: center !important; } .r19-r { background-color: #092046 !important; border-color: #ffffff !important; 
            border-radius: 8px !important; border-width: 0px !important; box-sizing: border-box; height: initial !important; 
            padding-bottom: 12px !important; padding-left: 5px !important; padding-right: 5px !important; padding-top: 12px !important; 
            text-align: center !important; width: 100% !important; } .r20-i { background-color: transparent !important; color: #3b3f44 
            !important; } .r21-c { box-sizing: border-box !important; width: 100% !important; } .r22-i { color: #3b3f44 !important; 
            font-size: 0px !important; padding-bottom: 15px !important; padding-left: 65px !important; padding-right: 65px !important; 
            padding-top: 15px !important; } .r23-c { box-sizing: border-box !important; width: 32px !important; } .r24-o { 
            border-style: solid !important; margin-right: 8px !important; width: 32px !important; } .r25-i { color: #3b3f44 !important; 
            padding-bottom: 5px !important; padding-top: 5px !important; } .r26-o { border-style: solid !important; margin-right: 0px 
            !important; width: 32px !important; } .r27-i { background-color: #092046 !important; padding-bottom: 20px !important; 
            padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important; } .r28-i { color: #3b3f44 
            !important; padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important; } body 
            { -webkit-text-size-adjust: none; } .nl2go-responsive-hide { display: none; } .nl2go-body-table { min-width: unset !important; }
            .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; 
            border: none !important; } .resp-table { display: inline-table !important; } .magic-resp { display: table-cell !important; } } 
            </style> <style type="text/css"> p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #ffffff; text-decoration: 
            underline; } .nl2go-default-textstyle { color: #3b3f44; font-family: Verdana; font-size: 16px; line-height: 1.5; word-break: 
            break-word; } .default-button { color: #000000; font-family: Verdana; font-size: 16px; font-style: normal; font-weight: bold; 
            line-height: 1.15; text-decoration: none; word-break: break-word; } .default-heading1 { color: #1f2d3d; font-family: Verdana; 
            font-size: 36px; word-break: break-word; } .default-heading2 { color: #1f2d3d; font-family: Verdana; font-size: 32px; word-
            break: break-word; } .default-heading3 { color: #1f2d3d; font-family: Verdana; font-size: 24px; word-break: break-word; } .
            default-heading4 { color: #1f2d3d; font-family: Verdana; font-size: 18px; word-break: break-word; } a[x-apple-data-detectors] 
            { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit 
            !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: n
            one; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: 
            fixed; visibility: hidden; width: 0; } </style> <!--[if mso ]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> <
            o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><! [endif]--> <style type="text/css"> a:link 
            { color: #fff; text-decoration: underline; } </style> </head> <body bgcolor="#ffffff" text="#3b3f44" link="#ffffff" 
            yahoo="fix" style="background-color: #ffffff" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" 
            class="nl2go-body-table" width="100%" style="background-color: #ffffff; width: 100%" > <tr> <td> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="600" align="center" class="r0-o" style="table-layout: fixed; width: 
            600px" > <tr> <td valign="top" class="r1-i" style="background-color: #ffffff"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> 
            <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" 
            border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style=" background-color: #105c8e; 
            font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" 
            style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r7-i" style=" background-color: #105c8e; 
            padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; " > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="300" class="r8-o" style=" table-layout: fixed; width: 300px; " > 
            <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> <tr> 
            <td style=" font-size: 0px; line-height: 0px; " > <img src="https://img.mailinblue.com/6573358/images/content_library/original/651cf0e620d2f777295fb10c.png" width="300" border="0" style=" display: block; width: 100%; " /> 
            </td> </tr> <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 
            100%" > <tr> <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > 
            <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
            width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
            cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
            <td align="left" valign="top" class="r12-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 
            16px; line-height: 1.5; word-break: break-word; padding-left: 15px; padding-right: 15px; padding-top: 15px; text-align: left; "
              
           > <div> <p style="margin: 0">Hi ' . $faculty_data->display_name . ',</p> </div> </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" 

           align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" style=" table-
           layout: fixed; width: 570px; " > <tr> <td height="30" class="r14-i" style=" font-size: 30px; line-height: 30px; background-
           color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r10-c" align="left"> <table cellspacing="0" cellpadding=
           "0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> <td align="left" 
           valign="top" class="r15-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px; word-break: 
           break-word; line-height: 1.5; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 15px; text-align: 

           left; " > <div> <p style="margin: 0"> We are pleased to inform you that your profile has received verification from  '
            . $hod->data->display_name . '(HOD) .
           </p> </div> </td> </tr> </table> </td> </tr> 

           </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> <td class="r4-i" 
           style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >
           <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r16-i" style=" 
           color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 16px; padding-left: 15px; padding-right: 15px; " > <table 
           width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >  <tr> 
          <td class="r13-c" align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td height="30" class="r20-i" style=" font-size: 30px; line-height: 30px; 
          background-color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" align="center"> <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="570" align="center" class="r3-o" style=" table-layout: fixed; width: 570px; " > 
          <tr> <td valign="top"> <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r21-c" 
          style=" display: inline-block; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r6-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td class="r22-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 16px; padding-bottom: 15px; padding-left: 206px; padding-right: 206px; padding-top: 15px; " > <table width="100%" cellspacing="0" 
          cellpadding="0" border="0" role="presentation" > <tr> <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > 
          <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 
          100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://facebook.com/SRIHER.Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/facebook_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="42" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://instagram.com/sriher.official" 
          target="_blank" style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/instagram_32px.png" width="32" border="0" style=" display: block; width: 100%; " /></a> </td> 
          <td class="nl2go-responsive-hide" width="10" style=" font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> 
          <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 100%; " > 
          <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://twitter.com/SRIHER_Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/twitter_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="32" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r26-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://youtube.com/channel/UC3c7OTHOy2wT3grS0aVtl6Q" target="_blank" 
          style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/youtube_32px.png" width="32" 
          border="0" style=" display: block; width: 100%; " /></a> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> </td> </tr> 
          </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0"
           cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > 
           <tr> <td class="r27-i" style=" background-color: #092046; padding-bottom: 20px; padding-top: 20px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: 
           normal" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
           width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
           cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
           <td align="center" valign="top" class="r28-i nl2go-default-textstyle" style=" font-family: Verdana; word-break: break-word; 
           background-image: url("https://www.sriramachandra.edu.in/"); color: #3b3f44; font-size: 18px; line-height: 3; padding-bottom: 0px; 
           padding-top: 0px; text-align: center; " > <div> <p style=" margin: 0; font-size: 14px; " > <span style=" color: #ffffff; font-family: 
           Arial; font-size: 16px; " >© 2023 All Rights Reserved</span ><a href="https://www.sriramachandra.edu.in/" title="Shriher" 
           target="_blank" style=" color: #fff; text-decoration: none; " ><span style=" color: #ffffff; font-family: Arial; font-size: 16px;
            " > sriramachandra.edu.in</span ></a > </p> </div> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </body></html>';


        // wp_mail($recipient, $subject, $message, $headers);

    } elseif (is_admin() && in_array('vc', (array) $current_user->roles) && !is_user_profile_page()) {

        $faculty_data = get_userdata($profile_id);

        $profile_image_grp = get_field('fac_image', 'user_' . $profile_id);
        if($profile_image_grp){
            // Extract necessary fields
            $current_profile_image = $profile_image_grp['current_profile_image'];
            $approved_profile_image = $profile_image_grp['frontend_profile_image'];
            $vc_approval = $profile_image_grp['vc_approval'];

            // Check if image needs updating and VC approval is granted

            if((!compareImages($current_profile_image, $approved_profile_image)) && $vc_approval === true){
                // Fetch the attachment ID for the new image URL
                $image_id = attachment_url_to_postid($current_profile_image);
                if ($image_id) {
                    // Update the image field within the group
                $profile_image_grp['current_profile_image'] = $image_id;
                $profile_image_grp['frontend_profile_image'] = $image_id;
                // Update the group field
                $updated = update_field('fac_image', $profile_image_grp, 'user_' . $profile_id);

                }
            
            }
        }


        // No need to repeat the same code again!


        $designation_grp = get_field('fac_designation', 'user_' . $profile_id);
        $current_designation = $designation_grp['current_designation'];
        $approved_designation = $designation_grp['frontend_designation'];
        $vc_approval = $designation_grp['vc_approval'];

        if (($current_designation !== $approved_designation) && ($vc_approval === true)) {
            $designation_data = array("frontend_designation" => $current_designation);
            update_field("fac_designation", $designation_data, 'user_' . $profile_id);
        }

        $qualification_grp = get_field('fac_qualification', 'user_' . $profile_id);
        $current_qualification = $qualification_grp['current_qualification'];
        $approved_qualification = $qualification_grp['frontend_qualification'];
        $vc_approval = $qualification_grp['vc_approval'];

        if (($current_qualification !== $approved_qualification) && ($vc_approval === true)) {
            $qualification_data = array("frontend_qualification" => $current_qualification);
            update_field("fac_qualification", $qualification_data, 'user_' . $profile_id);
        }

        $research_interest = get_field('fac_research_interest', 'user_' . $profile_id);
        $current_research_interest = $research_interest['current_research_interest'];
        $approved_research_interest = $research_interest['frontend_research_interest'];
        $vc_approval = $research_interest['vc_approval'];

        if (($current_research_interest !== $approved_research_interest) && ($vc_approval === true)) {
            $research_interest_data = array("frontend_research_interest" => $current_research_interest);
            update_field("fac_research_interest", $research_interest_data, 'user_' . $profile_id);
        }


        $contact_number_grp = get_field('contact_number', 'user_' . $profile_id);

        $current_contact_number = $contact_number_grp['current_contact_number'];
        $approved_contact_number = $contact_number_grp['frontend_contact_number'];
        $vc_approval = $contact_number_grp['vc_approval'];

        if (($current_contact_number !== $approved_contact_number) && ($vc_approval === true)) {
            $contact_number_grp['frontend_contact_number'] = $current_contact_number;

            update_field('contact_number', $contact_number_grp, 'user_' . $profile_id);
        }


        $bio_grp = get_field('fac_bio_grp', 'user_' . $profile_id);
        if ($bio_grp) {
            $current_bio = $bio_grp['current_bio'];
            $approved_bio = $bio_grp['frontend_bio'];
            $vc_approval = $bio_grp['vc_approval'];
            if (($current_bio !==  $approved_bio) && ($vc_approval === true)) {
                $bio_data = array("frontend_bio" => $current_bio);
                update_field('fac_bio_grp', $bio_data, 'user_' . $profile_id);
            }
        }


        $projects_grp = get_field('fac_projects', 'user_' . $profile_id);

        if (!empty($projects_grp)) {
            if (have_rows('fac_projects', 'user_' . $profile_id)) :
                while (have_rows('fac_projects', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');
                    $approval = get_sub_field('approval');
                    $vc_approvals = $approval['vc_approval'];

                    $current_project_name = $current_data['project_name'];
                    $showing_project_name = $showing_data['project_name'];

                    if (($current_project_name != $showing_project_name) && ($vc_approvals === true)) {
                        $project_name_data = array("project_name" => $current_project_name);
                        update_sub_field("showing_data", $project_name_data, 'user_' . $profile_id);
                    }

                    $current_project_des = $current_data['project_description'];
                    $showing_project_des = $showing_data['project_description'];
                    if (($current_project_des != $showing_project_des) && ($vc_approvals === true)) {
                        $project_description_data = array("project_description" => $current_project_des);
                        update_sub_field("showing_data", $project_description_data, 'user_' . $profile_id);
                    }

                    $current_project_pdf = $current_data['project_pdf'];
                    $showing_project_pdf = $showing_data['project_pdf'];
                    if (($current_project_pdf != $showing_project_pdf) && ($vc_approvals === true)) {
                        $project_pdf_data = array("project_pdf" => $current_project_pdf);
                        update_sub_field("showing_data", $project_pdf_data, 'user_' . $profile_id);
                    }
                endwhile;
            endif;
        }





        $awards_recognitions_grp = get_field('awards_recognitions', 'user_' . $profile_id);

        if (!empty($awards_recognitions_grp)) {
            if (have_rows('awards_recognitions', 'user_' . $profile_id)) :
                while (have_rows('awards_recognitions', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');
                    $approval = get_sub_field('approval');
                    $vc_approvals = $approval['vc_approval'];

                    $current_award_name = $current_data['award_name'];
                    $showing_award_name = $showing_data['award_name'];
                    if (($current_data['award_name'] != $showing_data['award_name']) && ($approval['vc_approval'] === true)) {
                        $award_name_data = array("award_name" => $current_award_name);
                        update_sub_field("showing_data", $award_name_data, 'user_' . $profile_id);
                    }

                    $current_award_des = $current_data['award_description'];
                    $showing_award_des = $showing_data['award_description'];
                    if (($current_award_des != $showing_award_des) && ($vc_approvals === true)) {
                        $award_description_data = array("award_description" => $current_award_des);
                        update_sub_field("showing_data", $award_description_data, 'user_' . $profile_id);
                    }

                    $current_award_image = $current_data['award_image'];
                    $showing_award_image = $showing_data['award_image'];
                    if (($current_award_image != $showing_award_image) && ($vc_approvals === true)) {
                        $award_image_data = array("award_image" => $current_award_image);
                        update_sub_field("showing_data", $award_image_data, 'user_' . $profile_id);
                    }

                endwhile;
            endif;
        }

        $news_grp = get_field('fac_news', 'user_' . $profile_id);
        if (!empty($news_grp)) {
            if (have_rows('fac_news', 'user_' . $profile_id)) :
                while (have_rows('fac_news', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');
                    $approval = get_sub_field('approval');
                    $vc_approvals = $approval['vc_approval'];

                    $current_news_title = $current_data['news_title'];
                    $showing_news_title = $showing_data['news_title'];
                    if (($current_news_title != $showing_news_title) && ($vc_approvals === true)) {
                        $news_title_data = array("news_title" => $current_news_title);
                        update_sub_field("showing_data", $news_title_data, 'user_' . $profile_id);
                    }

                    $current_news_link = $current_data['news_link'];
                    $showing_news_link = $showing_data['news_link'];
                    if (($current_news_link != $showing_news_link) && ($vc_approvals === true)) {
                        $news_link_data = array("news_link" => $current_news_link);
                        update_sub_field("showing_data", $news_link_data, 'user_' . $profile_id);
                    }
                endwhile;
            endif;
        }

        $membership_grp = get_field('fac_membership', 'user_' . $profile_id);
        if (!empty($membership_grp)) {
            if (have_rows('fac_membership', 'user_' . $profile_id)) :
                while (have_rows('fac_membership', 'user_' . $profile_id)) : the_row();
                    $current_data = get_sub_field('current_data');
                    $showing_data = get_sub_field('showing_data');
                    $approval = get_sub_field('approval');
                    $vc_approvals = $approval['vc_approval'];

                    $current_membership_title = $current_data['membership_title'];
                    $showing_membership_title = $showing_data['membership_title'];
                    if (($current_data['membership_title'] != $showing_data['membership_title']) && ($approval['vc_approval'] === true)) {
                        $title_data = array("membership_title" => $current_membership_title);
                        update_sub_field("showing_data", $title_data, 'user_' . $profile_id);
                    }


                    $current_membership_des = $current_data['membership_description'];
                    $showing_membership_des = $showing_data['membership_description'];
                    if (($current_membership_des != $showing_membership_des) && ($vc_approvals === true)) {
                        $membership_des_data = array("membership_description" => $current_membership_des);
                        update_sub_field("showing_data", $membership_des_data, 'user_' . $profile_id);
                    }


                endwhile;
            endif;
        }





        $vcfeedback = get_field("fac_feedback", 'user_' . $profile_id);
        $visibility = get_field('display_faculty', 'user_' . $profile_id);

        $recipient = $faculty_data->user_email;
        $profile_link = admin_url("user-edit.php?user_id={$profile_id}");
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = 'Profile Approved';
        $message = ' 
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html 
            xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta http-equiv="X-UA-Compatible" 
            content="IE=edge" /> <meta name="format-detection" content="telephone=no" /> <meta name="viewport" 
            content="width=device-width, initial-scale=1.0" /> <title>Profile Updated</title> <style type="text/css" emogrify="no"> 
            #outlook a { padding: 0; } .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span,
            .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse;
            mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader 
            { display: none !important; mso-hide: all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height:
            0px !important; font-size: 0px !important; } body { width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 
            100%; margin: 0; padding: 0; } img { outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a img 
            { border: none; } table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; } th { font-weight: normal; 
            text-align: left; } *[class="gmail-fix"] { display: none !important; } </style> <style type="text/css" emogrify="no"> @media 
            (max-width: 600px) { .gmx-killpill { content: " \03D1"; } } </style> <style type="text/css" emogrify="no"> @media (max-width: 600px) 
            { .gmx-killpill { content: " \03D1"; } .r0-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px 
            !important; } .r1-i { background-color: #ffffff !important; } .r2-c { box-sizing: border-box !important; text-align: center 
            !important; valign: top !important; width: 100% !important; } .r3-o { border-style: solid !important; margin: 0 auto 0 auto 
            !important; width: 100% !important; } .r4-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 
            15px !important; padding-top: 20px !important; } .r5-c { box-sizing: border-box !important; display: block !important; 
            valign: top !important; width: 100% !important; } .r6-o { border-style: solid !important; width: 100% !important; } .r7-i { 
            background-color: #105c8e !important; padding-bottom: 10px !important; padding-left: 0px !important; padding-right: 0px !important; 
            padding-top: 10px !important; } .r8-o { border-style: solid !important; margin: 0 auto 0 auto !important; margin-bottom: 
            10px !important; margin-top: 10px !important; width: 100% !important; } .r9-i { padding-left: 0px !important; padding-right: 
            0px !important; } .r10-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 
            100% !important; } .r11-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important; } 
            .r12-i { padding-left: 15px !important; padding-right: 15px !important; padding-top: 15px !important; text-align: 
            left !important; } .r13-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important; } 
            .r14-i { background-color: transparent !important; } .r15-i { padding-bottom: 15px !important; padding-left: 25px !important; 
            padding-right: 25px !important; padding-top: 15px !important; text-align: left !important; } .r16-i { color: #3b3f44 
            !important; padding-left: 0px !important; padding-right: 0px !important; } .r17-o { border-style: solid !important; margin: 0 
            auto 0 auto !important; margin-bottom: 15px !important; margin-top: 15px !important; width: 100% !important; } 
            .r18-i { text-align: center !important; } .r19-r { background-color: #092046 !important; border-color: #ffffff !important; 
            border-radius: 8px !important; border-width: 0px !important; box-sizing: border-box; height: initial !important; 
            padding-bottom: 12px !important; padding-left: 5px !important; padding-right: 5px !important; padding-top: 12px !important; 
            text-align: center !important; width: 100% !important; } .r20-i { background-color: transparent !important; color: #3b3f44 
            !important; } .r21-c { box-sizing: border-box !important; width: 100% !important; } .r22-i { color: #3b3f44 !important; 
            font-size: 0px !important; padding-bottom: 15px !important; padding-left: 65px !important; padding-right: 65px !important; 
            padding-top: 15px !important; } .r23-c { box-sizing: border-box !important; width: 32px !important; } .r24-o { 
            border-style: solid !important; margin-right: 8px !important; width: 32px !important; } .r25-i { color: #3b3f44 !important; 
            padding-bottom: 5px !important; padding-top: 5px !important; } .r26-o { border-style: solid !important; margin-right: 0px 
            !important; width: 32px !important; } .r27-i { background-color: #092046 !important; padding-bottom: 20px !important; 
            padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important; } .r28-i { color: #3b3f44 
            !important; padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important; } body 
            { -webkit-text-size-adjust: none; } .nl2go-responsive-hide { display: none; } .nl2go-body-table { min-width: unset !important; }
            .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; 
            border: none !important; } .resp-table { display: inline-table !important; } .magic-resp { display: table-cell !important; } } 
            </style> <style type="text/css"> p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #ffffff; text-decoration: 
            underline; } .nl2go-default-textstyle { color: #3b3f44; font-family: Verdana; font-size: 16px; line-height: 1.5; word-break: 
            break-word; } .default-button { color: #000000; font-family: Verdana; font-size: 16px; font-style: normal; font-weight: bold; 
            line-height: 1.15; text-decoration: none; word-break: break-word; } .default-heading1 { color: #1f2d3d; font-family: Verdana; 
            font-size: 36px; word-break: break-word; } .default-heading2 { color: #1f2d3d; font-family: Verdana; font-size: 32px; word-
            break: break-word; } .default-heading3 { color: #1f2d3d; font-family: Verdana; font-size: 24px; word-break: break-word; } .
            default-heading4 { color: #1f2d3d; font-family: Verdana; font-size: 18px; word-break: break-word; } a[x-apple-data-detectors] 
            { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit 
            !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: n
            one; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: 
            fixed; visibility: hidden; width: 0; } </style> <!--[if mso ]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> <
            o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><! [endif]--> <style type="text/css"> a:link 
            { color: #fff; text-decoration: underline; } </style> </head> <body bgcolor="#ffffff" text="#3b3f44" link="#ffffff" 
            yahoo="fix" style="background-color: #ffffff" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" 
            class="nl2go-body-table" width="100%" style="background-color: #ffffff; width: 100%" > <tr> <td> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="600" align="center" class="r0-o" style="table-layout: fixed; width: 
            600px" > <tr> <td valign="top" class="r1-i" style="background-color: #ffffff"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> 
            <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" 
            border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style=" background-color: #105c8e; 
            font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" 
            style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r7-i" style=" background-color: #105c8e; 
            padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; " > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table cellspacing="0" cellpadding="0" 
            border="0" role="presentation" width="300" class="r8-o" style=" table-layout: fixed; width: 300px; " > 
            <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> <tr> 
            <td style=" font-size: 0px; line-height: 0px; " > <img src="https://img.mailinblue.com/6573358/images/content_library/original/651cf0e620d2f777295fb10c.png" width="300" border="0" style=" display: block; width: 100%; " /> 
            </td> </tr> <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 
            100%" > <tr> <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" 
            cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > 
            <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
            width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
            cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
            cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
            <td align="left" valign="top" class="r12-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 
            16px; line-height: 1.5; word-break: break-word; padding-left: 15px; padding-right: 15px; padding-top: 15px; text-align: left; "

           > <div> <p style="margin: 0">Hi ' . $faculty_data->display_name . ',</p> </div> </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" 

           align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" style=" table-
           layout: fixed; width: 570px; " > <tr> <td height="30" class="r14-i" style=" font-size: 30px; line-height: 30px; background-
           color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r10-c" align="left"> <table cellspacing="0" cellpadding=
           "0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> <td align="left" 
           valign="top" class="r15-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px; word-break: 
           break-word; line-height: 1.5; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 15px; text-align: 

           left; " > <div> <p style="margin: 0"> We are delighted to inform you that your user profile has been approved! If you have any 
           questions or need assistance with anything, please don hesitate to reach out to our support team.<br><br>
           Thank you for being a part of our community, and we wish you a great experience ahead.
           </p> </div> </td> </tr> </table> </td> </tr> 

           </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> <td class="r4-i" 
           style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >
           <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > <table cellspacing="0" cellpadding="0" border="0" 
           role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r16-i" style=" 
           color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 16px; padding-left: 15px; padding-right: 15px; " > <table 
           width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >  <tr> 
          <td class="r13-c" align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td height="30" class="r20-i" style=" font-size: 30px; line-height: 30px; 
          background-color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" align="center"> <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="570" align="center" class="r3-o" style=" table-layout: fixed; width: 570px; " > 
          <tr> <td valign="top"> <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r21-c" 
          style=" display: inline-block; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r6-o" 
          style=" table-layout: fixed; width: 570px; " > <tr> <td class="r22-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 16px; padding-bottom: 15px; padding-left: 206px; padding-right: 206px; padding-top: 15px; " > <table width="100%" cellspacing="0" 
          cellpadding="0" border="0" role="presentation" > <tr> <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > 
          <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 
          100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://facebook.com/SRIHER.Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/facebook_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="42" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://instagram.com/sriher.official" 
          target="_blank" style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/instagram_32px.png" width="32" border="0" style=" display: block; width: 100%; " /></a> </td> 
          <td class="nl2go-responsive-hide" width="10" style=" font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> 
          <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > <table cellspacing="0" 
          cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 100%; " > 
          <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
          padding-bottom: 5px; padding-top: 5px; " > <a href="https://twitter.com/SRIHER_Official" target="_blank" style=" color: #fff; 
          text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/twitter_32px.png" 
          width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
          font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="32" class="r23-c mobshow resp-table" style=" 
          font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r26-o" style=" 
          table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
          font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://youtube.com/channel/UC3c7OTHOy2wT3grS0aVtl6Q" target="_blank" 
          style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/youtube_32px.png" width="32" 
          border="0" style=" display: block; width: 100%; " /></a> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> </td> </tr> 
          </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0"
           cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > 
           <tr> <td class="r27-i" style=" background-color: #092046; padding-bottom: 20px; padding-top: 20px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: 
           normal" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
           width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
           cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
           cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
           <td align="center" valign="top" class="r28-i nl2go-default-textstyle" style=" font-family: Verdana; word-break: break-word; 
           background-image: url("https://www.sriramachandra.edu.in/"); color: #3b3f44; font-size: 18px; line-height: 3; padding-bottom: 0px; 
           padding-top: 0px; text-align: center; " > <div> <p style=" margin: 0; font-size: 14px; " > <span style=" color: #ffffff; font-family: 
           Arial; font-size: 16px; " >© 2023 All Rights Reserved</span ><a href="https://www.sriramachandra.edu.in/" title="Shriher" 
           target="_blank" style=" color: #fff; text-decoration: none; " ><span style=" color: #ffffff; font-family: Arial; font-size: 16px;
            " > sriramachandra.edu.in</span ></a > </p> </div> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> 
            </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </body></html>';


        // wp_mail($recipient, $subject, $message, $headers);
        // }
        $dsubject = "Profile Disapproved";
        $dmessage = ' 
     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html 
     xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
     <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta http-equiv="X-UA-Compatible" 
     content="IE=edge" /> <meta name="format-detection" content="telephone=no" /> <meta name="viewport" 
     content="width=device-width, initial-scale=1.0" /> <title>Profile Updated</title> <style type="text/css" emogrify="no"> 
     #outlook a { padding: 0; } .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span,
     .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse;
     mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader 
     { display: none !important; mso-hide: all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height:
     0px !important; font-size: 0px !important; } body { width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 
     100%; margin: 0; padding: 0; } img { outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a img 
     { border: none; } table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; } th { font-weight: normal; 
     text-align: left; } *[class="gmail-fix"] { display: none !important; } </style> <style type="text/css" emogrify="no"> @media 
     (max-width: 600px) { .gmx-killpill { content: " \03D1"; } } </style> <style type="text/css" emogrify="no"> @media (max-width: 600px) 
     { .gmx-killpill { content: " \03D1"; } .r0-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px 
     !important; } .r1-i { background-color: #ffffff !important; } .r2-c { box-sizing: border-box !important; text-align: center 
     !important; valign: top !important; width: 100% !important; } .r3-o { border-style: solid !important; margin: 0 auto 0 auto 
     !important; width: 100% !important; } .r4-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 
     15px !important; padding-top: 20px !important; } .r5-c { box-sizing: border-box !important; display: block !important; 
     valign: top !important; width: 100% !important; } .r6-o { border-style: solid !important; width: 100% !important; } .r7-i { 
     background-color: #105c8e !important; padding-bottom: 10px !important; padding-left: 0px !important; padding-right: 0px !important; 
     padding-top: 10px !important; } .r8-o { border-style: solid !important; margin: 0 auto 0 auto !important; margin-bottom: 
     10px !important; margin-top: 10px !important; width: 100% !important; } .r9-i { padding-left: 0px !important; padding-right: 
     0px !important; } .r10-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 
     100% !important; } .r11-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important; } 
     .r12-i { padding-left: 15px !important; padding-right: 15px !important; padding-top: 15px !important; text-align: 
     left !important; } .r13-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important; } 
     .r14-i { background-color: transparent !important; } .r15-i { padding-bottom: 15px !important; padding-left: 25px !important; 
     padding-right: 25px !important; padding-top: 15px !important; text-align: left !important; } .r16-i { color: #3b3f44 
     !important; padding-left: 0px !important; padding-right: 0px !important; } .r17-o { border-style: solid !important; margin: 0 
     auto 0 auto !important; margin-bottom: 15px !important; margin-top: 15px !important; width: 100% !important; } 
     .r18-i { text-align: center !important; } .r19-r { background-color: #092046 !important; border-color: #ffffff !important; 
     border-radius: 8px !important; border-width: 0px !important; box-sizing: border-box; height: initial !important; 
     padding-bottom: 12px !important; padding-left: 5px !important; padding-right: 5px !important; padding-top: 12px !important; 
     text-align: center !important; width: 100% !important; } .r20-i { background-color: transparent !important; color: #3b3f44 
     !important; } .r21-c { box-sizing: border-box !important; width: 100% !important; } .r22-i { color: #3b3f44 !important; 
     font-size: 0px !important; padding-bottom: 15px !important; padding-left: 65px !important; padding-right: 65px !important; 
     padding-top: 15px !important; } .r23-c { box-sizing: border-box !important; width: 32px !important; } .r24-o { 
     border-style: solid !important; margin-right: 8px !important; width: 32px !important; } .r25-i { color: #3b3f44 !important; 
     padding-bottom: 5px !important; padding-top: 5px !important; } .r26-o { border-style: solid !important; margin-right: 0px 
     !important; width: 32px !important; } .r27-i { background-color: #092046 !important; padding-bottom: 20px !important; 
     padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important; } .r28-i { color: #3b3f44 
     !important; padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important; } body 
     { -webkit-text-size-adjust: none; } .nl2go-responsive-hide { display: none; } .nl2go-body-table { min-width: unset !important; }
     .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; 
     border: none !important; } .resp-table { display: inline-table !important; } .magic-resp { display: table-cell !important; } } 
     </style> <style type="text/css"> p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #ffffff; text-decoration: 
     underline; } .nl2go-default-textstyle { color: #3b3f44; font-family: Verdana; font-size: 16px; line-height: 1.5; word-break: 
     break-word; } .default-button { color: #000000; font-family: Verdana; font-size: 16px; font-style: normal; font-weight: bold; 
     line-height: 1.15; text-decoration: none; word-break: break-word; } .default-heading1 { color: #1f2d3d; font-family: Verdana; 
     font-size: 36px; word-break: break-word; } .default-heading2 { color: #1f2d3d; font-family: Verdana; font-size: 32px; word-
     break: break-word; } .default-heading3 { color: #1f2d3d; font-family: Verdana; font-size: 24px; word-break: break-word; } .
     default-heading4 { color: #1f2d3d; font-family: Verdana; font-size: 18px; word-break: break-word; } a[x-apple-data-detectors] 
     { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit 
     !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: n
     one; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: 
     fixed; visibility: hidden; width: 0; } </style> <!--[if mso ]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> <
     o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><! [endif]--> <style type="text/css"> a:link 
     { color: #fff; text-decoration: underline; } </style> </head> <body bgcolor="#ffffff" text="#3b3f44" link="#ffffff" 
     yahoo="fix" style="background-color: #ffffff" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" 
     class="nl2go-body-table" width="100%" style="background-color: #ffffff; width: 100%" > <tr> <td> <table cellspacing="0" 
     cellpadding="0" border="0" role="presentation" width="600" align="center" class="r0-o" style="table-layout: fixed; width: 
     600px" > <tr> <td valign="top" class="r1-i" style="background-color: #ffffff"> <table cellspacing="0" cellpadding="0" 
     border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> 
     <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" 
     border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style=" background-color: #105c8e; 
     font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" 
     style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r7-i" style=" background-color: #105c8e; 
     padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; " > <table width="100%" cellspacing="0" 
     cellpadding="0" border="0" role="presentation" > <tr> <td class="r2-c" align="center"> <table cellspacing="0" cellpadding="0" 
     border="0" role="presentation" width="300" class="r8-o" style=" table-layout: fixed; width: 300px; " > 
     <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> <tr> 
     <td style=" font-size: 0px; line-height: 0px; " > <img src="https://img.mailinblue.com/6573358/images/content_library/original/651cf0e620d2f777295fb10c.png" width="300" border="0" style=" display: block; width: 100%; " /> 
     </td> </tr> <tr class="nl2go-responsive-hide"> <td height="10" style=" font-size: 10px; line-height: 10px; " > </td> </tr> 
     </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" 
     cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 
     100%" > <tr> <td class="r4-i" style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" 
     cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > 
     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
     width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
     cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
     cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
     <td align="left" valign="top" class="r12-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 
     16px; line-height: 1.5; word-break: break-word; padding-left: 15px; padding-right: 15px; padding-top: 15px; text-align: left; "

    > <div> <p style="margin: 0">Hi ' . $faculty_data->display_name . ',</p> </div> </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" 

    align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" style=" table-
    layout: fixed; width: 570px; " > <tr> <td height="30" class="r14-i" style=" font-size: 30px; line-height: 30px; background-
    color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r10-c" align="left"> <table cellspacing="0" cellpadding=
    "0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> <td align="left" 
    valign="top" class="r15-i nl2go-default-textstyle" style=" color: #3b3f44; font-family: Verdana; font-size: 16px; word-break: 
    break-word; line-height: 1.5; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 15px; text-align: 

    left; " > <div> <p style="margin: 0"> We regret to inform you that your profile has been disapproved due to specific feedback received are'
            . ' ' . $vcfeedback . '.
    </p> </div> </td> </tr> </table> </td> </tr> 

    </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0" cellpadding="0" border="0" 
    role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > <tr> <td class="r4-i" 
    style="padding-bottom: 20px; padding-top: 20px" > <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >
    <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: normal" > <table cellspacing="0" cellpadding="0" border="0" 
    role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%" > <tr> <td valign="top" class="r16-i" style=" 
    color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 16px; padding-left: 15px; padding-right: 15px; " > <table 
    width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" >  <tr> 
   <td class="r13-c" align="center"> <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r3-o" 
   style=" table-layout: fixed; width: 570px; " > <tr> <td height="30" class="r20-i" style=" font-size: 30px; line-height: 30px; 
   background-color: transparent; " > </td> </tr> </table> </td> </tr> <tr> <td class="r13-c" align="center"> <table cellspacing="0" 
   cellpadding="0" border="0" role="presentation" width="570" align="center" class="r3-o" style=" table-layout: fixed; width: 570px; " > 
   <tr> <td valign="top"> <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r21-c" 
   style=" display: inline-block; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="570" class="r6-o" 
   style=" table-layout: fixed; width: 570px; " > <tr> <td class="r22-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
   font-size: 16px; padding-bottom: 15px; padding-left: 206px; padding-right: 206px; padding-top: 15px; " > <table width="100%" cellspacing="0" 
   cellpadding="0" border="0" role="presentation" > <tr> <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > 
   <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 
   100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
   padding-bottom: 5px; padding-top: 5px; " > <a href="https://facebook.com/SRIHER.Official" target="_blank" style=" color: #fff; 
   text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/facebook_32px.png" 
   width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
   font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="42" class="r23-c mobshow resp-table" style=" 
   font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" 
   table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
   font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://instagram.com/sriher.official" 
   target="_blank" style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/instagram_32px.png" width="32" border="0" style=" display: block; width: 100%; " /></a> </td> 
   <td class="nl2go-responsive-hide" width="10" style=" font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> 
   <th width="42" class="r23-c mobshow resp-table" style=" font-weight: normal; " > <table cellspacing="0" 
   cellpadding="0" border="0" role="presentation" width="100%" class="r24-o" style=" table-layout: fixed; width: 100%; " > 
   <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; font-size: 0px; line-height: 0px; 
   padding-bottom: 5px; padding-top: 5px; " > <a href="https://twitter.com/SRIHER_Official" target="_blank" style=" color: #fff; 
   text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/twitter_32px.png" 
   width="32" border="0" style=" display: block; width: 100%; " /></a> </td> <td class="nl2go-responsive-hide" width="10" style=" 
   font-size: 0px; line-height: 1px; " > </td> </tr> </table> </th> <th width="32" class="r23-c mobshow resp-table" style=" 
   font-weight: normal; " > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r26-o" style=" 
   table-layout: fixed; width: 100%; " > <tr> <td class="r25-i" style=" color: #3b3f44; font-family: arial, helvetica, sans-serif; 
   font-size: 0px; line-height: 0px; padding-bottom: 5px; padding-top: 5px; " > <a href="https://youtube.com/channel/UC3c7OTHOy2wT3grS0aVtl6Q" target="_blank" 
   style=" color: #fff; text-decoration: underline; " > <img src="https://creative-assets.mailinblue.com/editor/social-icons/rounded_bw/youtube_32px.png" width="32" 
   border="0" style=" display: block; width: 100%; " /></a> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> </td> </tr> 
   </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> </table> </td> </tr> </table> <table cellspacing="0"
    cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r3-o" style="table-layout: fixed; width: 100%" > 
    <tr> <td class="r27-i" style=" background-color: #092046; padding-bottom: 20px; padding-top: 20px; " > <table width="100%" 
    cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <th width="100%" valign="top" class="r5-c" style="font-weight: 
    normal" > <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; 
    width: 100%" > <tr> <td valign="top" class="r9-i" style=" padding-left: 15px; padding-right: 15px; " > <table width="100%" 
    cellspacing="0" cellpadding="0" border="0" role="presentation" > <tr> <td class="r10-c" align="left"> <table cellspacing="0" 
    cellpadding="0" border="0" role="presentation" width="100%" class="r11-o" style=" table-layout: fixed; width: 100%; " > <tr> 
    <td align="center" valign="top" class="r28-i nl2go-default-textstyle" style=" font-family: Verdana; word-break: break-word; 
    background-image: url("https://www.sriramachandra.edu.in/"); color: #3b3f44; font-size: 18px; line-height: 3; padding-bottom: 0px; 
    padding-top: 0px; text-align: center; " > <div> <p style=" margin: 0; font-size: 14px; " > <span style=" color: #ffffff; font-family: 
    Arial; font-size: 16px; " >© 2023 All Rights Reserved</span ><a href="https://www.sriramachandra.edu.in/" title="Shriher" 
    target="_blank" style=" color: #fff; text-decoration: none; " ><span style=" color: #ffffff; font-family: Arial; font-size: 16px;
     " > sriramachandra.edu.in</span ></a > </p> </div> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </th> </tr> 
     </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </body></html>';

        $overall_approval = get_field('overall_approval', 'user_' . $profile_id);

        if ($overall_approval === true) {
            overall_approvals('user_' . $profile_id);
        } else {
            echo "Condition not met.";
        }

        if ($visibility && empty($vcfeedback)) {
            wp_mail($recipient, $subject, $message, $headers);
        } elseif (!empty($vcfeedback)) {
            wp_mail($recipient, $dsubject, $dmessage, $headers);
        }
    }


    if (is_admin() && in_array('vc', (array) $current_user->roles)) {

        hod_vc_disapproval($profile_id);
    }
}
