<div class="resource-card">
    <div class="resources-card-image"<?php get_background_image_by_post(get_the_ID(), 'resource-thumb', true);?>></div>

	<div class="resources-card-content">
		<div class="resources-card-meta">
			<i class="fa fa-lightbulb-o"></i><?php get_post_type(get_the_ID()); ?>
		</div>
		<h3><?php the_title();?></h3>

		<p>
			<?php the_content(); ?>
		</p>

		<div class="resources-card-meta">
			<i class="fa fa-user"></i>Kyle<i class="fa fa-clock-o"></i>Sep 23, 2014<i class="fa fa-comment"></i>5 comments
		</div>
	</div>
</div>