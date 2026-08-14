<?php
/**
 * Role and Company columns on the WP users screen, plus sorting and search.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// make user role column sortable in the users admin dashboard
function add_role_column( $columns ) {
    $columns['role'] = 'Role';
    return $columns;
}
add_filter( 'manage_users_columns', 'add_role_column' );
function display_role_column_content( $value, $column_name, $user_id ) {
    $user = get_userdata( $user_id );
    $role = $user->roles ? array_shift( $user->roles ) : '';
    return $column_name === 'role' ? $role : $value;
}
add_action( 'manage_users_custom_column', 'display_role_column_content', 10, 3 );
function make_role_column_sortable( $columns ) {
    $columns['role'] = 'role';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'make_role_column_sortable' );

// add a company column to the users admin dashboard
function add_company_column( $columns ) {
    $columns['company'] = 'Company';
    return $columns;
}
add_filter( 'manage_users_columns', 'add_company_column' );

// 'company' is the single source of truth: the registration form writes it and the
// discount logic and quote API read it. Woo's 'billing_company' is a per-order
// address field and deliberately not consulted here.
function display_company_column_content( $value, $column_name, $user_id ) {
    if ( $column_name !== 'company' ) {
        return $value;
    }
    $company = trim( (string) get_user_meta( $user_id, 'company', true ) );
    return $company !== '' ? esc_html( $company ) : '&mdash;';
}
add_action( 'manage_users_custom_column', 'display_company_column_content', 10, 3 );

function make_company_column_sortable( $columns ) {
    $columns['company'] = 'company';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'make_company_column_sortable' );

// sort on the 'company' meta key only, keeping users without it in the list
function sort_users_by_company( $query ) {
    if ( ! is_admin() || $query->get( 'orderby' ) !== 'company' ) {
        return;
    }
    $query->set( 'meta_query', array(
        'relation'       => 'OR',
        'company_clause' => array( 'key' => 'company', 'compare' => 'EXISTS' ),
        array( 'key' => 'company', 'compare' => 'NOT EXISTS' ),
    ) );
    $query->set( 'orderby', 'company_clause' );
}
add_action( 'pre_get_users', 'sort_users_by_company' );

// the users search only covers wp_users columns, so company meta is invisible to it.
// stash the columns core settled on so we can rebuild its search clause verbatim below.
function user_search_columns_store( $columns = null ) {
    static $stored = array();
    if ( $columns !== null ) {
        $stored = $columns;
    }
    return $stored;
}
function capture_user_search_columns( $columns, $search, $query ) {
    user_search_columns_store( $columns );
    return $columns;
}
add_filter( 'user_search_columns', 'capture_user_search_columns', 999, 3 );

function search_users_by_company( $query ) {
    global $wpdb;

    $raw = $query->get( 'search' );
    if ( ! is_admin() || ! is_string( $raw ) || $raw === '' ) {
        return;
    }
    $columns = user_search_columns_store();
    if ( ! $columns ) {
        return;
    }

    // mirror how core derives the wildcard mode and trims the term
    $leading  = strpos( $raw, '*' ) === 0;
    $trailing = substr( $raw, -1 ) === '*';
    if ( $leading && $trailing ) {
        $wild = 'both';
    } elseif ( $leading ) {
        $wild = 'leading';
    } elseif ( $trailing ) {
        $wild = 'trailing';
    } else {
        $wild = false;
    }
    $search = $wild ? trim( $raw, '*' ) : $raw;

    $original = $query->get_search_sql( $search, $columns, $wild );
    if ( strpos( $query->query_where, $original ) === false ) {
        return; // core built it differently than expected; leave the query alone
    }

    $company_where = $wpdb->prepare(
        "$wpdb->users.ID IN ( SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'company' AND meta_value LIKE %s )",
        '%' . $wpdb->esc_like( $search ) . '%'
    );

    // widen core's search group in place rather than appending an OR to the whole
    // WHERE, so the role filters and include/exclude clauses keep applying
    $widened            = ' AND (' . substr( $original, 6, -1 ) . ' OR ' . $company_where . ')';
    $query->query_where = str_replace( $original, $widened, $query->query_where );
}
add_action( 'pre_user_query', 'search_users_by_company' );
