<?php
/**
 * Custom search form
 *
 * The reason for this code is to add a button that supports icons.
 */

if ( 'html5' === $format ) {
            $form = '<form role="search" ' . $aria_label . 'method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
                <label>
                    <span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>
                    <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" />
                </label>
                <button class="search-button"></button>
				<input type="submit" class="screen-reader-text" value="' . esc_attr_x( 'Search', 'submit button' ) . '" />
				</form>';
} else {
            $form = '<form role="search" ' . $aria_label . 'method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
                <div>
                    <label class="screen-reader-text" for="s">' . _x( 'Search for:', 'label' ) . '</label>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
                    <input type="submit" id="searchsubmit" value="' . esc_attr_x( 'Search', 'submit button' ) . '" />
                </div>
				</form>';
}

echo $form;

?>