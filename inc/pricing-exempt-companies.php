<?php
/**
 * Single source of truth for the company-based pricing exemptions.
 *
 * These lists were previously duplicated in functions.php (which feeds the Rapid Quote
 * JS) and inc/discounts.php (which prices WooCommerce), and they had drifted:
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
 * Intentionally EMPTY. That 7% increase was a former, one-off increase that no longer
 * applies, so no company should get the rollback. 'US Tool Group' was listed here
 * historically; as of 2026-08-10 'EGC - Ewie' is the only company with special pricing.
 *
 * Kept as a list rather than deleted, because the branch it feeds also serves the
 * unrelated role-based path (sales/administrator on the MSC_PL tier), which is not
 * company-driven and must keep working.
 *
 * @return string[]
 */
function gws_exempt_companies() {
    return apply_filters( 'gws_exempt_companies', array() );
}

/**
 * Companies whose price is their tier discount with 25% then ADDED to that price.
 *
 * price = list * (1 - tier_rate) * 1.25 -- a surcharge, so the customer pays MORE; it is
 * not a further discount. At t1 (0.55) a $100 list part is $45.00 * 1.25 = $56.25.
 * No 7% rollback is involved.
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
