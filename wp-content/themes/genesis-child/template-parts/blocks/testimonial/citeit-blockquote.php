<?php
/**
 * Block Name: Testimonial
 *
 * This is the template that displays the testimonial block.
 */

// get image field (array)
$avatar = get_field('avatar');

// create id attribute for specific styling
$id = 'citeit_blockquote-' . $block['id'];

// create align class ("alignwide") from block setting ("wide")
$align_class = $block['align'] ? 'align' . $block['align'] : '';

?>
<blockquote cite="<?php the_field('url'); ?>" id="<?php echo $id; ?>" class="citeit <?php echo $align_class; ?>">
    <p><?php the_field('quote'); ?></p>
	
	<cite><?php the_field('author'); ?></cite>
</blockquote>
