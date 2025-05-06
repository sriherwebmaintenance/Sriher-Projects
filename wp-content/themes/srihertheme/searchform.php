<form method="get" class="searchform" action="<?php bloginfo('url'); ?>/">
	<div class="expandright">
		<input type="text" value="<?php the_search_query(); ?>" name="s" class="search stext" placeholder="Type and Press Enter to Search" />
		<button type="submit" class="button searchsubmit">
			<img src="<?php echo get_template_directory_uri(); ?>/images/search.svg" alt="Search" />
		</button>
	</div>
	<button type="button" class="button searchbutton">
		<img src="<?php echo get_template_directory_uri(); ?>/images/search.svg" alt="Search" />
	</button>
</form> 
						