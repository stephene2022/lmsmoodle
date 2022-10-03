<?php
// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingdnsclms', get_string('configtitle', 'theme_dnsclms'));

    /*
    * ----------------------
    * General settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_dnsclms_general', get_string('generalsettings', 'theme_dnsclms'));

    // Logo file setting.
    $name = 'theme_dnsclms/logo';
    $title = get_string('logo', 'theme_dnsclms');
    $description = get_string('logodesc', 'theme_dnsclms');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $page->add($setting);

    // Favicon setting.
    $name = 'theme_dnsclms/favicon';
    $title = get_string('favicon', 'theme_dnsclms');
    $description = get_string('favicondesc', 'theme_dnsclms');
    $opts = array('accepted_types' => array('.ico'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $page->add($setting);

    // Preset.
    $name = 'theme_dnsclms/preset';
    $title = get_string('preset', 'theme_dnsclms');
    $description = get_string('preset_desc', 'theme_dnsclms');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_dnsclms', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_dnsclms/presetfiles';
    $title = get_string('presetfiles', 'theme_dnsclms');
    $description = get_string('presetfiles_desc', 'theme_dnsclms');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 10, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Login page background image.
    $name = 'theme_dnsclms/loginbgimg';
    $title = get_string('loginbgimg', 'theme_dnsclms');
    $description = get_string('loginbgimg_desc', 'theme_dnsclms');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_dnsclms/brandcolor';
    $title = get_string('brandcolor', 'theme_dnsclms');
    $description = get_string('brandcolor_desc', 'theme_dnsclms');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#2A5500');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-header-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_dnsclms/secondarymenucolor';
    $title = get_string('secondarymenucolor', 'theme_dnsclms');
    $description = get_string('secondarymenucolor_desc', 'theme_dnsclms');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#2A5500');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $fontsarr = [
        'Roboto' => 'Roboto',
        'Poppins' => 'Poppins',
        'Montserrat' => 'Montserrat',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Raleway' => 'Raleway',
        'Inter' => 'Inter',
        'Nunito' => 'Nunito',
        'Encode Sans' => 'Encode Sans',
        'Work Sans' => 'Work Sans',
        'Oxygen' => 'Oxygen',
        'Manrope' => 'Manrope',
        'Sora' => 'Sora',
        'Epilogue' => 'Epilogue'
    ];

    $name = 'theme_dnsclms/fontsite';
    $title = get_string('fontsite', 'theme_dnsclms');
    $description = get_string('fontsite_desc', 'theme_dnsclms');
    $setting = new admin_setting_configselect($name, $title, $description, 'Roboto', $fontsarr);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_dnsclms/enablecourseindex';
    $title = get_string('enablecourseindex', 'theme_dnsclms');
    $description = get_string('enablecourseindex_desc', 'theme_dnsclms');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_dnsclms_advanced', get_string('advancedsettings', 'theme_dnsclms'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_dnsclms/scsspre',
        get_string('rawscsspre', 'theme_dnsclms'), get_string('rawscsspre_desc', 'theme_dnsclms'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_dnsclms/scss', get_string('rawscss', 'theme_dnsclms'),
        get_string('rawscss_desc', 'theme_dnsclms'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_dnsclms/googleanalytics';
    $title = get_string('googleanalytics', 'theme_dnsclms');
    $description = get_string('googleanalyticsdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    $page = new admin_settingpage('theme_dnsclms_frontpage', get_string('frontpagesettings', 'theme_dnsclms'));

    // Disable teachers from cards.
    $name = 'theme_dnsclms/disableteacherspic';
    $title = get_string('disableteacherspic', 'theme_dnsclms');
    $description = get_string('disableteacherspicdesc', 'theme_dnsclms');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Slideshow.
    $name = 'theme_dnsclms/slidercount';
    $title = get_string('slidercount', 'theme_dnsclms');
    $description = get_string('slidercountdesc', 'theme_dnsclms');
    $default = 0;
    $options = array();
    for ($i = 0; $i < 13; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $slidercount = get_config('theme_dnsclms', 'slidercount');

    if (!$slidercount) {
        $slidercount = $default;
    }

    if ($slidercount) {
        for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
            $fileid = 'sliderimage' . $sliderindex;
            $name = 'theme_dnsclms/sliderimage' . $sliderindex;
            $title = get_string('sliderimage', 'theme_dnsclms');
            $description = get_string('sliderimagedesc', 'theme_dnsclms');
            $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
            $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
            $page->add($setting);

            $name = 'theme_dnsclms/slidertitle' . $sliderindex;
            $title = get_string('slidertitle', 'theme_dnsclms');
            $description = get_string('slidertitledesc', 'theme_dnsclms');
            $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
            $page->add($setting);

            $name = 'theme_dnsclms/slidercap' . $sliderindex;
            $title = get_string('slidercaption', 'theme_dnsclms');
            $description = get_string('slidercaptiondesc', 'theme_dnsclms');
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $page->add($setting);
        }
    }

    $setting = new admin_setting_heading('slidercountseparator', '', '<hr>');
    $page->add($setting);

    $name = 'theme_dnsclms/displaymarketingbox';
    $title = get_string('displaymarketingboxes', 'theme_dnsclms');
    $description = get_string('displaymarketingboxesdesc', 'theme_dnsclms');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $displaymarketingbox = get_config('theme_dnsclms', 'displaymarketingbox');

    if ($displaymarketingbox) {
        // Marketingheading.
        $name = 'theme_dnsclms/marketingheading';
        $title = get_string('marketingsectionheading', 'theme_dnsclms');
        $default = 'Awesome App Features';
        $setting = new admin_setting_configtext($name, $title, '', $default);
        $page->add($setting);

        // Marketingcontent.
        $name = 'theme_dnsclms/marketingcontent';
        $title = get_string('marketingsectioncontent', 'theme_dnsclms');
        $default = 'dnsclms is a Moodle template based on Boost with modern and creative design.';
        $setting = new admin_setting_confightmleditor($name, $title, '', $default);
        $page->add($setting);

        for ($i = 1; $i < 5; $i++) {
            $filearea = "marketing{$i}icon";
            $name = "theme_dnsclms/$filearea";
            $title = get_string('marketingicon', 'theme_dnsclms', $i . '');
            $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
            $setting = new admin_setting_configstoredfile($name, $title, '', $filearea, 0, $opts);
            $page->add($setting);

            $name = "theme_dnsclms/marketing{$i}heading";
            $title = get_string('marketingheading', 'theme_dnsclms', $i . '');
            $default = 'Lorem';
            $setting = new admin_setting_configtext($name, $title, '', $default);
            $page->add($setting);

            $name = "theme_dnsclms/marketing{$i}content";
            $title = get_string('marketingcontent', 'theme_dnsclms', $i . '');
            $default = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.';
            $setting = new admin_setting_confightmleditor($name, $title, '', $default);
            $page->add($setting);
        }

        $setting = new admin_setting_heading('displaymarketingboxseparator', '', '<hr>');
        $page->add($setting);
    }

    // Enable or disable Numbers sections settings.
    $name = 'theme_dnsclms/numbersfrontpage';
    $title = get_string('numbersfrontpage', 'theme_dnsclms');
    $description = get_string('numbersfrontpagedesc', 'theme_dnsclms');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $numbersfrontpage = get_config('theme_dnsclms', 'numbersfrontpage');

    if ($numbersfrontpage) {
        $name = 'theme_dnsclms/numbersfrontpagecontent';
        $title = get_string('numbersfrontpagecontent', 'theme_dnsclms');
        $description = get_string('numbersfrontpagecontentdesc', 'theme_dnsclms');
        $default = get_string('numbersfrontpagecontentdefault', 'theme_dnsclms');
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);
    }

    // Enable FAQ.
    $name = 'theme_dnsclms/faqcount';
    $title = get_string('faqcount', 'theme_dnsclms');
    $description = get_string('faqcountdesc', 'theme_dnsclms');
    $default = 0;
    $options = array();
    for ($i = 0; $i < 11; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    $faqcount = get_config('theme_dnsclms', 'faqcount');

    if ($faqcount > 0) {
        for ($i = 1; $i <= $faqcount; $i++) {
            $name = "theme_dnsclms/faqquestion{$i}";
            $title = get_string('faqquestion', 'theme_dnsclms', $i . '');
            $setting = new admin_setting_configtext($name, $title, '', '');
            $page->add($setting);

            $name = "theme_dnsclms/faqanswer{$i}";
            $title = get_string('faqanswer', 'theme_dnsclms', $i . '');
            $setting = new admin_setting_confightmleditor($name, $title, '', '');
            $page->add($setting);
        }

        $setting = new admin_setting_heading('faqseparator', '', '<hr>');
        $page->add($setting);
    }

    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_dnsclms_footer', get_string('footersettings', 'theme_dnsclms'));

    // Website.
    $name = 'theme_dnsclms/website';
    $title = get_string('website', 'theme_dnsclms');
    $description = get_string('websitedesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mobile.
    $name = 'theme_dnsclms/mobile';
    $title = get_string('mobile', 'theme_dnsclms');
    $description = get_string('mobiledesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mail.
    $name = 'theme_dnsclms/mail';
    $title = get_string('mail', 'theme_dnsclms');
    $description = get_string('maildesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_dnsclms/facebook';
    $title = get_string('facebook', 'theme_dnsclms');
    $description = get_string('facebookdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_dnsclms/twitter';
    $title = get_string('twitter', 'theme_dnsclms');
    $description = get_string('twitterdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_dnsclms/linkedin';
    $title = get_string('linkedin', 'theme_dnsclms');
    $description = get_string('linkedindesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_dnsclms/youtube';
    $title = get_string('youtube', 'theme_dnsclms');
    $description = get_string('youtubedesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_dnsclms/instagram';
    $title = get_string('instagram', 'theme_dnsclms');
    $description = get_string('instagramdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Whatsapp url setting.
    $name = 'theme_dnsclms/whatsapp';
    $title = get_string('whatsapp', 'theme_dnsclms');
    $description = get_string('whatsappdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Telegram url setting.
    $name = 'theme_dnsclms/telegram';
    $title = get_string('telegram', 'theme_dnsclms');
    $description = get_string('telegramdesc', 'theme_dnsclms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    $settings->add($page);
}
