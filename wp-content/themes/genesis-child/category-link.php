<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<section id="primary">
			<div id="content" role="main">
			<?php if ( have_posts() ) : ?>

				<header class="page-header">

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content-links', get_post_format() );

						$link = get_link_source($post);
						global $post;
						$custom = get_post_custom($post->ID);
						$url = $custom["url"][0];

					if ( isset($url) ){ ?>

						<div id="custom-source">
						<div class="source">source: <a href="<?php echo $link['url']  ?>"><?php echo $link['host'] ?></a></div>
						<? 	echo("<a class=\"read-more\" href=\"" . $link['url'] . "\">Read Original Source</a>"); ?>
						</div><?php
					} ?>

				<?php endwhile; ?>


				<?php //twentyeleven_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->

				</article><!-- #post-0 -->

			<?php endif;
			
			global $wp_query,$wpex_query;
			if ( $wpex_query ) {
			   $total = $wpex_query->max_num_pages;
			} else {
			   $total = $wp_query->max_num_pages;
			}			
			
			//wpex_pagination();			
			
			 ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
