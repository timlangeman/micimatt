<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<nav id="nav-single">
					<!--h3 class="assistive-text"><?php _e( 'Post navigation', 'genesis' ); ?></h3-->
					<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
				</nav><!-- #nav-single -->

				<?php while ( have_posts() ) : the_post(); ?>
				<article class="transcript">

					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'genesis' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'genesis' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'genesis' ) . '</span>', 'after' => '</div>' ) ); ?>
		

					<?php get_template_part( 'content', 'single' );
						$link = get_link_source($post)
					 ?>
					<div id="custom-source">
					<div class="source">source: 
						<a href="<?php echo $link['url']  ?>"><?php echo $link['host'] ?></a>
	  				  	<?php the_tags( '<p class="tags">Tags: ', ', ', '</p>'); ?>

					</div>

					<?
					echo("<a class=\"read-more\" href=\"" . $link['url'] . "\">Read Original Source</a>");
					?></div>


					<?php comments_template( '', true ); ?>

				</article>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>