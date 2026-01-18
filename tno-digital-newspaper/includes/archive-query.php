<?php
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('digital_edition')) {

        $query->set('post_status', 'publish');

        if (!empty($_GET['year'])) {
            $query->set('year', intval($_GET['year']));
        }

        if (!empty($_GET['monthnum'])) {
            $query->set('monthnum', intval($_GET['monthnum']));
        }
    }
});
