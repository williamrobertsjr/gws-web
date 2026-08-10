<?php
/**
 * Single source of truth for the company-based pricing exemptions.
 *
 * These lists were previously duplicated in functions.php (which feeds the Rapid Quote
 * JS) and views/woo/discounts.php (which prices WooCommerce), and they had drifted:
 * discounts.php carried 'US Tool Group Test', which matched no user at all, so the four
 * real 'US Tool Group' users got the 7% rollback in Rapid Quote but not on product
 * pages. Defining them once is what stops that recurring.
 *
 * Every value here MUST be spelled exactly as it appears in the gws_distributors table,
 * because both call sites compare against the `company` user meta with in_array() and
 * that meta is normalized to gws_distributors. Verify before adding:
 *
 *   wp db query "SELECT company_name, status FROM gws_distributors WHERE company_name = 'X'"
 */

/**
 * Companies exempt from the 7% price increase.
 *
 * @return string[]
 */
function gws_exempt_companies() {
    return apply_filters( 'gws_exempt_companies', array(
        'US Tool Group', // gws_distributors id 1482, National, tier 1
    ) );
}

/**
 * Companies that get the +25% adjustment instead of the plain 7% rollback.
 *
 * Dropped when this was centralized: 'Ewie' and 'Ewie Company' are not rows in
 * gws_distributors and no user has ever held either value, so they matched nothing.
 * The real distributor row is 'EGC - Ewie' (id 441, erp_id EWI001).
 *
 * @return string[]
 */
function gws_plus_25_companies() {
    return apply_filters( 'gws_plus_25_companies', array(
        'EGC - Ewie', // gws_distributors id 441, National, tier 1
    ) );
}
