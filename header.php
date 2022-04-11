<?php
/**
 * This file generates the header for the back-end and also for the default
 * template.
 *
 * Other checks for user level are performed later to generate the different
 * menu items, and the content of the page that called this file.
 *
 * @package ProjectSend
 * @see check_for_session
 * @see can_see_content
 */
// Check for an active session
check_for_session();

// Check if the current user has permission to view this page.
can_see_content($allowed_levels);

global $flash;

/** If no page title is defined, revert to a default one */
if (!isset($page_title)) { $page_title = __('System Administration','cftp_admin'); }

if (!isset($body_class)) { $body_class = array(); }

if ( !empty( $_COOKIE['menu_contracted'] ) && $_COOKIE['menu_contracted'] == 'true' ) {
    $body_class[] = 'menu_contracted';
}

$body_class[] = 'menu_hidden';

/**
 * Silent updates that are needed even if no user is logged in.
 */
require_once INCLUDES_DIR . DS .'core.update.silent.php';

// Run required database upgrades
$db_upgrade = new \ProjectSend\Classes\DatabaseUpgrade;
$db_upgrade->upgradeDatabase(false);

/**
 * Call the database update file to see if any change is needed,
 * but only if logged in as a system user.
 */
$core_update_allowed = array(9,8,7);
if (current_role_in($core_update_allowed)) {
    //require_once INCLUDES_DIR . DS . 'core.update.php';
}

// Redirect if password needs to be changed
password_change_required();
?>
<!doctype html>
<html lang="<?php echo SITE_LANG; ?>">
<head>
    <meta charset="<?php echo(CHARSET); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo html_output( $page_title . ' &raquo; ' . htmlspecialchars(get_option('this_install_title'), ENT_QUOTES, CHARSET) ); ?></title>
    <?php meta_favicon(); ?>

    <?php
        require_once INCLUDES_DIR . DS . 'assets.php';

        load_js_header_files();
        load_css_files();
    ?>
</head>

<body <?php echo add_body_class( $body_class ); ?> <?php if (!empty($page_id)) { echo add_page_id($page_id); } ?>>
    <div class="container-custom">
        <header id="header" class="navbar navbar-static-top navbar-fixed-top">
            <ul class="nav pull-left nav_toggler">
                <li>
                    <a href="#" class="toggle_main_menu"><i class="fa fa-bars" aria-hidden="true"></i><span><?php _e('Toggle menu', 'cftp_admin'); ?></span></a>
                </li>
            </ul>

            <div class="navbar-header">
                <span class="navbar-brand"><a href="<?php echo SYSTEM_URI; ?>" target="_blank"><?php include_once 'assets/img/ps-icon.svg'; ?></a> <?php echo html_output(get_option('this_install_title')); ?></span>
            </div>

            <ul class="nav pull-right nav_account">
                <li id="header_welcome">
                    <span><?php echo CURRENT_USER_NAME; ?></span>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php _e('Language', 'cftp_admin'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php
                                // scan for language files
                                $available_langs = get_available_languages();
                                foreach ($available_langs as $filename => $lang_name) {
                            ?>
                                    <li>
                                        <a href="<?php echo BASE_URI.'process.php?do=change_language&language='.$filename; ?>">
                                            <?php echo $lang_name; ?>
                                        </a>
                                    </li>
                            <?php
                                }
                            ?>
                        </ul>
                </li>
                <li>
                    <?php $my_account_link = (CURRENT_USER_LEVEL == 0) ? 'clients-edit.php' : 'users-edit.php'; ?>
                    <a href="<?php echo BASE_URI.$my_account_link; ?>?id=<?php echo CURRENT_USER_ID; ?>" class="my_account"><i class="fa fa-user-circle" aria-hidden="true"></i> <?php _e('My Account', 'cftp_admin'); ?></a>
                </li>
                <li>
                    <a href="<?php echo BASE_URI; ?>process.php?do=logout" ><i class="fa fa-sign-out" aria-hidden="true"></i> <?php _e('Logout', 'cftp_admin'); ?></a>
                </li>
            </ul>
        </header>

        <?php include_once 'includes' . DS . 'main-menu.php'; ?>

        <div class="main_content">
            <div class="container-fluid">
                <?php
                    // Gets the mark up and values for the System Updated and errors messages.
                    include_once INCLUDES_DIR . DS . 'updates.messages.php';

                    include_once INCLUDES_DIR . DS . 'header-messages.php';
                ?>

                <div class="row">
                    <div class="col-xs-12">
                        <div id="section_title">
                            <h2><?php echo $page_title; ?></h2>
                        </div>
                    </div>
                </div>

                <?php
                    // Flash messages
                    if ($flash->hasMessages()) {
                        echo $flash;
                    }
