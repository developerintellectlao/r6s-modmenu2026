<?php

/**
 * Social share
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/post-type/social-share.php
 *
 * @package WP_Carousel_Pro
 */

if ( ! $wpcp_post_social_show ) {
	return;
}
$social_icon_shape = 'wpcpro_circle';
?>
<div class="wpcpro-social-share">
<?php do_action( 'wpcpro_add_first_socials' ); ?>
<a title="Facebook"  href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="wpcpro-social-icon wpcpro-facebook <?php echo esc_attr( $social_icon_shape ); ?>" onClick="window.open('https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>','Facebook','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" ><i class="fa fa-facebook"></i></a>

<a title="Twitter" onClick="window.open('https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php echo get_the_title(); ?>','Twitter share','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php echo get_the_title(); ?>" class="wpcpro-social-icon wpcpro-twitter <?php echo esc_attr( $social_icon_shape ); ?>"> <i class="fa fa-twitter"></i></a>

<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>" title="linkedIn" class="wpcpro-social-icon wpcpro-linkedin <?php echo esc_attr( $social_icon_shape ); ?>" onClick="window.open('https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>','Linkedin','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>"> <i class="fa fa-linkedin"></i></a>

<a href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;https://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());' class="wpcpro-social-icon wpcpro-pinterest <?php echo esc_attr( $social_icon_shape ); ?>" title="Pinterest"> <i class="fa fa-pinterest"></i></a>

<a href="mailto:?Subject=<?php echo get_the_title(); ?>&amp;Body=<?php the_permalink(); ?>" title="Email" class="wpcpro-social-icon wpcpro-envelope <?php echo esc_attr( $social_icon_shape ); ?>"> <i class="fa fa-envelope"></i></a>

<a title="Instagram" onClick="window.open('https://instagram.com/?url=<?php the_permalink(); ?>&amp;text=<?php echo get_the_title(); ?>','Twitter share','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="https://instagram.com/?url=<?php the_permalink(); ?>&amp;text=<?php echo get_the_title(); ?>" class="wpcpro-social-icon wpcpro-instagram <?php echo esc_attr( $social_icon_shape ); ?>"> <i class="fa fa-instagram" aria-hidden="true"></i></a>

<?php
do_action( 'wpcpro_add_last_socials' );
?>
</div>
<?php
