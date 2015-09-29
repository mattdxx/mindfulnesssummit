<?php
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterEmails::instance();


if ($controls->is_action('theme')) {
    $controls->merge($module->themes->get_options($controls->data['theme']));
    $module->save_options($controls->data);
}


if ($controls->data == null) {
    $controls->data = $module->get_options();
}

function newsletter_emails_update_options($options) {
    add_option('newsletter_emails', '', null, 'no');
    update_option('newsletter_emails', $options);
}

function newsletter_emails_update_theme_options($theme, $options) {
    $x = strrpos($theme, '/');
    if ($x !== false) {
        $theme = substr($theme, $x + 1);
    }
    add_option('newsletter_emails_' . $theme, '', null, 'no');
    update_option('newsletter_emails_' . $theme, $options);
}

function newsletter_emails_get_options() {
    $options = get_option('newsletter_emails', array());
    return $options;
}

function newsletter_emails_get_theme_options($theme) {
    $x = strrpos($theme, '/');
    if ($x !== false) {
        $theme = substr($theme, $x + 1);
    }
    $options = get_option('newsletter_emails_' . $theme, array());
    return $options;
}

$themes = $module->themes->get_all_with_data();

/* @var $wp_theme WP_Theme */
/*
$wp_theme = wp_get_theme();
$dir = $wp_theme->get_template_directory() . '/newsletter/emails/themes';
$handle = @opendir($dir);

if ($handle !== false) {
    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (isset($list[$file])) {
            continue;
        }
        if (!is_file($dir . '/' . $file . '/theme.php')) {
            continue;
        }
        $data = get_file_data($dir . '/' . $file . '/theme.php', array('name' => 'Name', 'type' => 'Type', 'description' => 'Description'));
        $data['id'] = $file;
        if (empty($data['name'])) {
            $data['name'] = $file;
        }
        if (empty($data['type'])) {
            $data['type'] = 'standard';
        }
        $screenshot = $dir . '/' . $file . '/screenshot.png';
        if (is_file($screenshot)) {
            $data['screenshot'] = $this->get_theme_url($file) . '/screenshot.png';
        } else {
            $data['screenshot'] = plugins_url('newsletter') . '/images/theme-screenshot.png';
        }
        $list[$file] = $data;
    }
    closedir($handle);
}
$themes = array_merge($themes, $list);
*/
?>

<div class="wrap">

    <?php $help_url = 'http://www.thenewsletterplugin.com/plugins/newsletter/newsletters-module'; ?>
    <?php include NEWSLETTER_DIR . '/header-new.php'; ?>

    <div id="newsletter-title">
        <h2><?php _e('Select a theme', 'newsletter-emails') ?>
            <a class="add-new-h2" href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-themes" target="_blank">Custom themes</a>
        </h2>
    </div>

    <div class="newsletter-separator"></div>

    <?php $controls->show(); ?>

    <form method="post" id="newsletter-form" action="<?php echo $module->get_admin_page_url('new'); ?>">
        <?php $controls->init(); ?>
        <?php $controls->hidden('theme'); ?>
        <?php foreach ($themes as $key => &$data) { ?>
            <div class="tnp-theme-preview">
                <p><?php echo $data['name']; ?></p>
                <a href="#" onclick="var f = document.getElementById('newsletter-form');
                        f.act.value = 'theme';
                        f.elements['options[theme]'].value = '<?php echo $data['id']; ?>';
                        f.submit();
                        return false;" style="margin-right: 20px; margin-bottom: 20px">
                    <img src="<?php echo $data['screenshot'] ?>" width="200" height="200">
                </a>
            </div>
        <?php } ?>
    </form>
</div>
