<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="quiz" v-for="(item, index) in quizes" :key="item.id">
    <h4 class="title">{{ item.quiz_name }}</h4>
    <ul class="actions">
        <li><button type="button" class="flat_button" :data-id="item.id"><?php echo $this->get_svg("edit"); ?></button></li>
        <li><button type="button" class="flat_button"><?php echo $this->get_svg("delete"); ?></button></li>
    </ul>
</div>