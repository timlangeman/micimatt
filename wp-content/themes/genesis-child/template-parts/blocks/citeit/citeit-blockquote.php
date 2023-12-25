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

*************CiteIt Block Quote************************
URL: <?php the_field('url'); ?>

<blockquote cite="<?php the_field('url'); ?>" id="<?php echo $id; ?>" class="citeit <?php echo $align_class; ?>">
    Testimonial: <p><?php the_field('testimonial'); ?></p>
    <p><?php the_field('quote_text'); ?></p>
	
	<cite><?php the_field('author'); ?></cite>
</blockquote>


***footer****