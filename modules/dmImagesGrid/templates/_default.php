<div style="<?php echo $container_style; ?>" class="dmImagesGrid dmImagesGridTemplateDefault dmImagesGridStyle<?php echo ucfirst($display_style); ?>">
    <?php 
        $i = 0;
        foreach ($media_items as $media_item): 
        $i++;
    ?>
    
    
    <div class="dmImagesGridImageContainer" style="width: <?php echo $media_item['width'] ?>px;">
        <div class="dmImagesGridImage">
            <?php if ($media_item['link']): ?><a class="<?php echo $css_class_links; ?>" href="<?php echo _link($media_item['link'])->getHref(); ?>"><?php endif; ?>
                <img class="<?php echo $css_class_images; ?>" src="<?php echo $media_item['thumbnail']; ?>" title="<?php echo $media_item['title']; ?>" alt="<?php echo $media_item['title']; ?>" width="<?php echo $media_item['width']; ?>" height="<?php echo $media_item['height']; ?>" />
            <?php if ($media_item['link']): ?></a><?php endif; ?>
        </div>
        <?php if ($show_title): ?>
        <div class="dmImagesGridTitle">
            <?php echo ($media_item['title']) ? $media_item['title'] : "&nbsp;"; ?>
        </div>
        <?php endif; ?>
    </div>
    
    
    <?php 
        if (($display_style == 'grid' && $i%$display_per_row == 0) || sizeof($media_items) == $i) echo '<div class="dmImagesGridSeparator clearfix"></div>';
        endforeach; 
    ?>
</div>