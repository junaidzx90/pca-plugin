<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="paired_analysis">

    <?php
    if(isset($_POST['wpforms'])){
        echo do_shortcode('[wpforms id="'.$atts['form'].'"]');
    }else{
        ?>
        <!--  Form  -->
        <div id="items__form">
            <div id="lines">

                <div class="item__line">
                    <input data-id="1" type="text" class="item__input">
                </div>

            </div>

            <button id="item__submit">Submit</button>
        </div>
        <!--  Questions  -->
        <div class="noned" id="items_questions">
            <ul id="list__items"> </ul>
        </div>
        <?php
    }
    ?>
    
</div>