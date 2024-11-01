<?php
/*
 * WpSimpleFaqs Admin Settings View
 */
$headingColor = get_option('wpsimplefaqs_headingcolor');
$headingTxtColor = get_option('wpsimplefaqs_headingtxtcolor');
$headingContentColor = get_option('wpsimplefaqs_headingcontentcolor');
$groupedSetting = get_option('wpsimplefaqs_grouped');
?>

<div class="wrap">
    <h1>FAQs Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('wpsimplefaqs_settings'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="wpsimplefaqs_headingcolor">Headings Background Color</label></th>
                    <td><input name="wpsimplefaqs_headingcolor" type="color" id="wpsimplefaqs_headingcolor" value="<?php echo (!empty($headingColor)) ? $headingColor : '#42464b'; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpsimplefaqs_headingtxtcolor">Headings Text Color</label></th>
                    <td><input name="wpsimplefaqs_headingtxtcolor" type="color" id="wpsimplefaqs_headingtxtcolor" value="<?php echo (!empty($headingTxtColor)) ? $headingTxtColor : '#ffffff'; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpsimplefaqs_headingcontentcolor">Content Text Color</label></th>
                    <td><input name="wpsimplefaqs_headingcontentcolor" type="color" id="wpsimplefaqs_headingcontentcolor" value="<?php echo (!empty($headingContentColor)) ? $headingContentColor : '#6c7d8e'; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpsimplefaqs_grouped">Enable FAQs Categories view</label></th>
                    <td><input name="wpsimplefaqs_grouped" type="checkbox" id="wpsimplefaqs_grouped" <?php checked('on', $groupedSetting, true); ?>></td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>

