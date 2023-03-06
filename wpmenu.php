<?php
// Add this code to functions.php

function wp_get_menu_array($current_menu) {
    $menu_name = $current_menu;
    $locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
    $array_menu = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
    $menu = array();
    foreach ($array_menu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['ID']          =   $m->ID;
            $menu[$m->ID]['title']       =   $m->title;
            $menu[$m->ID]['url']         =   $m->url;
            $menu[$m->ID]['children']    =   array();
        }
    }
    $submenu = array();
    foreach ($array_menu as $m) {
        if ($m->menu_item_parent) {
            $submenu[$m->ID] = array();
            $submenu[$m->ID]['ID']       =   $m->ID;
            $submenu[$m->ID]['title']    =   $m->title;
            $submenu[$m->ID]['url']      =   $m->url;
            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
        }
    }
    return $menu;
}

?>

<!-- Call this function in your theme like this. -->

<?php $menu_items = wp_get_menu_array('header'); ?>
<div id="navbar-collapse" class="collapse navbar-collapse">
    <ul class="nav navbar-nav mr-auto">
        <?php foreach ($menu_items as $item) : ?>
            <?php if( !empty($item['children']) ){ ?>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?= $item['url'] ?>" title="<?= $item['title'] ?>" data-toggle="dropdown"><?= $item['title'] ?> <i class="fa fa-angle-down"></i> </a>
            <?php }else{ ?>
                <li class="nav-item">
                <a class="nav-link" href="<?= $item['url'] ?>" title="<?= $item['title'] ?>"><?= $item['title'] ?></a>
            <?php } ?>
            <?php if( !empty($item['children']) ):?>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach($item['children'] as $child): ?>
                    <li>
                        <a href="<?= $child['url'] ?>" title="<?= $child['title'] ?>"><?= $child['title'] ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <ul>
</div>
