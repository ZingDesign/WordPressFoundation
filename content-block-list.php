<li>

    <a href="<?php echo get_permalink(get_the_ID());?>">
        <div class="feature-image"<?php get_background_image_by_post(get_the_ID(), 'product-thumb', true);?>>
        </div>
    </a>

    <div class="feature-name" data-equalizer>
        <h2><a href="<?php echo get_permalink(get_the_ID());?>" data-equalizer-watch>
                <?php echo get_the_title();?>
        </a></h2>
    </div>
</li>

