// Search SQL filter for matching against post title only.
function __search_by_title_only( $search, $wp_query )
{
global $wpdb;

if ( empty( $search ) )
return $search; // skip processing - no search term in query

$q = $wp_query->query_vars;
$n = ! empty( $q['exact'] ) ? '' : '%';

$search =
$searchand = '';

foreach ( (array) $q['search_terms'] as $term ) {
$term = esc_sql( like_escape( $term ) );

$search .= "{$searchand}($wpdb->posts.post_title REGEXP '[[:<:]]{$term}[[:>:]]')";

$searchand = ' AND ';
}

if ( ! empty( $search ) ) {
$search = " AND ({$search}) ";
if ( ! is_user_logged_in() )
$search .= " AND ($wpdb->posts.post_password = '') ";
}

return $search;
}

add_filter( 'posts_search', '__search_by_title_only', 1000, 2 );

/*
You can change this to search for exact words in titles plus content both, by modifying the line 18 to
$search .= "{$searchand}($wpdb->posts.post_title REGEXP '[[:<:]]{$term}[[:>:]]') OR ($wpdb->posts.post_content REGEXP '[[:<:]]{$term}[[:>:]]')";
*/
