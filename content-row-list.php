<div class="row-list-container">
    <div class="large-4 columns row-image"<?php get_background_image_by_post(get_the_ID(), 'resource-thumb', true);?>></div>

    <div class="large-8 columns content-container">
        <h2 id="<?php echo sanitize_title(get_the_title());?>"><?php echo get_the_title();?></h2>
        <div class="row-content"><?php echo wpautop(get_the_content());?></div>
    </div>
</div>