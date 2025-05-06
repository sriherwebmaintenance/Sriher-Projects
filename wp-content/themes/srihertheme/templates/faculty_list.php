<?php

/* Template Name: faculty list Page
*/
get_header();

$faculty = array(
	"role" => "faculty",
	'order' => 'ASC'
);
$vc = array(
	"role" => "vc",
	'order' => 'ASC'
);
$hod = array(
	"role" => "hod",
	'order' => 'ASC'
);
$avatar = get_theme_file_uri('/images/faculty-img/avatar.jpg');
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
			<div class="contentFull">
				<div class="facultyGrid">
					<?php $user_query = new WP_User_Query($vc);
					if (!empty($user_query->get_results())) {
						foreach ($user_query->get_results() as $user) {
							$user_ID = $user->ID;

							$display_name = $user->display_name;
							$first_name = $user->first_name;
							$last_name = $user->last_name;
							$vcImgid = get_field('fac_image', 'user_' . $user_ID);
							$image = wp_get_attachment_image_url($vcImgid);
							if (get_field('fac_designation', 'user_' . $user_ID)) {
								$designationId = get_field("fac_designation", 'user_' . $user_ID);
								$designation = '';
								foreach ($designationId as $id) {
									$designations = get_term($id, 'designations');
									$designation .= $designations->name . ' ';
								}
							}

					?>
							<div class="facultyBox" id="<?php echo $user_ID; ?>">
								<a class="facultyThumb" href="<?php echo get_author_posts_url($user_ID); ?>">
									<img src="<?php echo empty($image) ? $avatar : $image; ?>" alt="<?php echo $display_name; ?>" class="profile-img">
								</a>
								<div class="facultyCaption">
									<h4><a href="<?php echo get_author_posts_url($user_ID); ?>">
											<?php echo $first_name . ' ' . $last_name; ?>
										</a></h4>
									<p><?php echo $designation; ?></p>
								</div>
							</div>
						<?php
						}
					}

					$user_query = new WP_User_Query($hod);
					if (!empty($user_query->get_results())) {
						foreach ($user_query->get_results() as $user) {
							$user_ID = $user->ID;

							$display_name = $user->display_name;
							$first_name = $user->first_name;
							$last_name = $user->last_name;
							$hodImg = get_field('users_image', 'user_' . $user_ID);
							// $image = wp_get_attachment_image_url($hodImgid);
							if (get_field('fac_designation', 'user_' . $user_ID)) {
								$designationId = get_field("fac_designation", 'user_' . $user_ID);
								$designation = '';
								foreach ($designationId as $id) {
									$designations = get_term($id, 'designations');
									$designation .= $designations->name . ' ';
								}
							}
						?>
							<div class="facultyBox" id="<?php echo $user_ID; ?>">
								<a href="<?php echo get_author_posts_url($user_ID); ?>" class="facultyThumb">
									<img src="<?php echo empty($hodImg) ? $avatar : $hodImg; ?>" alt="<?php echo $display_name; ?>" class="profile-img">
								</a>
								<div class="facultyCaption">
									<h4>
										<a href="<?php echo get_author_posts_url($user_ID); ?>">
											<?php echo $first_name . ' ' . $last_name; ?>
										</a>
									</h4>
									<p><?php echo $designation; ?></p>
								</div>
							</div>
							<?php
						}
					}

					$user_query = new WP_User_Query($faculty);
					if (!empty($user_query->get_results())) {
						foreach ($user_query->get_results() as $user) {
							$user_ID = $user->ID;

							$designation = "";
							$visibility = get_field('display_faculty', 'user_' . $user_ID);
							if ($visibility) {
								$display_name = $user->display_name;

								if (get_field('fac_image', 'user_' . $user_ID)) {
									$profImgGrp = get_field('fac_image', 'user_' . $user_ID);
									if ($profImgGrp['frontend_profile_image'] && $profImgGrp['vc_approval']) {
										$profImg = $profImgGrp['frontend_profile_image'];
									} else {
										$profImg = get_template_directory_uri() . '/images/faculty-img/avatar.jpg';
									}
								}

								if (get_field('fac_designation', 'user_' . $user_ID)) {
									$designation_field = get_field('fac_designation', 'user_' . $user_ID);
									if ($designation_field['vc_approval']) {
										$designationId = $designation_field['frontend_designation'];
										$designation = '';
										foreach ($designationId as $id) {
											$designations = get_term($id, 'designations');
											$designation .= $designations->name . ' ';
										}
									}
								}

							?>
								<div class="facultyBox" id="<?php echo $user_ID; ?>">
									<a href="<?php echo get_author_posts_url($user_ID); ?>" class="facultyThumb">
										<img src="<?php echo $profImg; ?>" alt="<?php echo $display_name; ?>" class="profile-img">
									</a>
									<div class="facultyCaption">
										<h4>
											<a href="<?php echo get_author_posts_url($user_ID); ?>">
												<?php echo $display_name; ?>
											</a>
										</h4>
										<?php if ($designation) { ?>
											<p><?php echo $designation;
												?></p>
										<?php } ?>
									</div>
								</div>
					<?php
							}
						}
					} ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer();

?>