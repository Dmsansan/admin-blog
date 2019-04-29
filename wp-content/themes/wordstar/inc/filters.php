<?php /**
 * WordStar Filters file
 *
 * @category WordPress
 * @package  WordStar
 * @author   Linesh Jose <lineshjos@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://linesh.com/projects/wordstar/
 *
 */

// Adding body class --------------->
add_filter('body_class', function( $classes ) 
{
    $classes[] = get_theme_mod( 'wordstar_columns', 'multi' ) . '-column';
    
    if (get_header_image() ) {
        $classes[] = 'custom-header';
    }
    
    if (get_background_image() ) { 
        $classes[] = 'custom-background-image';
    }
    if (is_multi_author() ) {
        $classes[] = 'group-blog';
    }else{
        $classes[] = 'single-author';
    }
    if (!wordstar_active_sidebars()) {
        $classes[] = 'no-sidebar';
    }
    if (!is_singular() ) {
        $classes[] = 'hfeed';
        $classes[] = 'h-feed';
        $classes[] = 'feed';
    }
    return $classes;
});


// Adds custom classes to the array of post classes. ------------------>
add_filter( 'post_class', function ( $classes ) 
{
    $classes[] = 'h-entry';
    $classes[] = 'hentry';

    if ( get_post_type() === 'page' ) {
        $classes[] = 'h-as-page';
    }
    if ( !get_post_format() && 'post' === get_post_type() ) {
        $classes[] = 'h-as-article';
    }

    switch ( get_post_format() ) 
    {
        case 'aside':
        case 'status':
            $classes[] = 'h-as-note';
            break;
        case 'audio':
            $classes[] = 'h-as-audio';
            break;
        case 'video':
            $classes[] = 'h-as-video';
            break;
        case 'gallery':
        case 'image':
            $classes[] = 'h-as-image';
            break;
        case 'link':
            $classes[] = 'h-as-bookmark';
            break;
    }
    return array_unique( $classes );
}, 99 );



// Adding comment class --------------->
add_filter( 'comment_class', function ( $classes ) {
    $classes[] = 'h-as-comment';
    $classes[] = 'h-entry';
    $classes[] = 'h-cite';
    $classes[] = 'p-comment';
    $classes[] = 'comment';
    return array_unique( $classes );
}, 99 );


// Avatar class -------------->
add_filter( 'pre_get_avatar_data', function( $args, $id_or_email ) {
    if ( ! isset( $args['class'] ) ) {
        $args['class'] = array();
    }
    // Adds a class for microformats v2
    $args['class'] = array_unique( array_merge( $args['class'], array( 'u-photo' ) ) );
    $args['extra_attr'] = 'itemprop="image"';
    return $args;
}, 99, 2 );


// Excerpt more --------------->
add_filter('excerpt_more', function ( $more ) 
{
    if(! is_admin()) {
        /* translators: %s: Name of current post */
        $link = sprintf('<a href="%1$s" class="more-link read-more" rel="bookmark">%2$s</a>', esc_url(get_permalink(get_the_ID())), sprintf(__('Continue Reading %s', 'wordstar'), '<span class="screen-reader-text">'.get_the_title(get_the_ID()).'</span><i class="fa fa-arrow-right"></i>'));
        return '&hellip; ' . $link;
    }
});


// Excerpt character length --------------->
    add_filter('excerpt_length', function ( $length ) {
    return 50;
}, 999);






 

// Social media profile links fields in user profile page  ------------------>
add_filter('user_contactmethods', function ($profile_fields) {
    $profile_fields['wordstar_twitter'] = __('Twitter Username', 'wordstar');
    $profile_fields['wordstar_facebook'] = __('Facebook URL', 'wordstar');
    $profile_fields['wordstar_instagram'] = __('Instagram URL', 'wordstar');
    $profile_fields['wordstar_gplus'] = __('Google+ URL', 'wordstar');
    $profile_fields['wordstar_flickr'] =__( 'Flickr URL', 'wordstar');
    $profile_fields['wordstar_github'] =__( 'Github URL', 'wordstar');
    $profile_fields['wordstar_pinterest'] =__( 'Pinterest URL', 'wordstar');
    $profile_fields['wordstar_tumblr'] =__( 'Tumblr URL', 'wordstar');
    $profile_fields['wordstar_medium'] =__( 'Medium URL', 'wordstar');
    return $profile_fields;
} );

    
// Archive title --------------------->
add_filter('get_the_archive_title', function($title )
{
    $rss='';
    if (is_search()) {
        $title = '<span>'. __('Searching for:', 'wordstar').'</span><strong>"'.get_search_query().'"</strong>' ;
    }elseif (is_category() ) {
        $title = '<strong>'.single_cat_title('', false).'</strong><span>'. __('Category', 'wordstar').'</span>' ;
        $rss=get_category_feed_link(get_query_var('cat'));
    } elseif (is_tag() ) {
        $title = '<strong>'.single_tag_title('', false).'</strong><span>'. __('Tag Archive', 'wordstar').'</span>' ;
        $rss=get_tag_feed_link(get_query_var('tag_id')); 
    } elseif (is_author() ) {
        $title = '<strong class="vcard">' . get_the_author() . '</strong><span>'. __('Author', 'wordstar').'</span>' ;
        $rss= get_author_feed_link(get_the_author_meta('ID'));
    } elseif (is_year() ) {
        $title = '<strong>' .get_the_date(__('Y', 'wordstar'))  . '</strong><span>'. __('Yearly Archives', 'wordstar').'</span>' ;
    } elseif (is_month() ) {
        $title = '<strong>' .get_the_date(__('F Y', 'wordstar'))  . '</strong><span>'. __('Monthly Archives ', 'wordstar').'</span>' ;
    } elseif (is_day() ) {
        $title = '<strong>' .get_the_date(__('F j, Y', 'wordstar'))  . '</strong><span>'. __('Daily Archives', 'wordstar').'</span>' ;
    } elseif (is_post_type_archive() ) {
        $title = '<strong>' .post_type_archive_title('', false)  . '</strong>' ;
        $rss=get_post_type_archive_feed_link(get_query_var('post_type'));
    } elseif (is_tax() ) {
        $tax = get_taxonomy(get_queried_object()->taxonomy);
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $title = '<strong>'.single_term_title('', false).'</strong><span>'.$tax->labels->singular_name.'</span>' ;
        $rss=get_term_feed_link($term->term_id, get_query_var('taxonomy'));

    } else {
        $title = '' ;//__( '<span>Blog Archives:</span> <strong>All Posts</strong>' );
        $rss=get_bloginfo('rss2_url');
    }
    if($title && $rss) {
        $title=$title.'<a href="'.$rss.'" title="'.esc_attr(__('Subscribe this', 'wordstar')).'" class="subscribe" rel="noopener noreferrer" target="_blank"><i class="fa fa-rss"></i><srong class="">'.__('Subscribe', 'wordstar').'</srong></a>   ';
    }
    return $title;
});  

// add the filter for search widget title ....
add_filter( 'widget_title', function( $instance_title, $instance,$this_id_base ) { 
    if($this_id_base=='search'){
        $instance_title = ! empty( $instance['title'] ) ? $instance['title'] :__('Search','canary');
    }  
    return $instance_title; 
} , 10, 3 )  

?>