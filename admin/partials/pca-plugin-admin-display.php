<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="research__wrapper">

    <?php
    $type = get_post_meta($post->ID, 'research__type_value', true);
    $research_texts = get_post_meta($post->ID, 'research_texts', true);
    $research_images = get_post_meta($post->ID, 'research_images', true);
    ?>

    <div class="research_input">
        <label for="research__type_value">Select research type</label>
        <select id="research__type_value" name="research__type_value">
            <option <?php echo (($type === 'text') ? 'selected': '') ?> value="text">Text</option>
            <option <?php echo (($type === 'image') ? 'selected': '') ?> value="image">Image</option>
        </select>
    </div>

    <h4>Researches</h4>
    <div class="__researches">
        <div id="research_contents">
            <div id="rTexts">

                <?php
                switch ($type) {
                    case 'text':
                        if(is_array($research_texts) && sizeof($research_texts) > 0){
                            foreach($research_texts as $text){
                                ?>
                                <div class="textInput">
                                    <input class="text__input" placeholder="text" type="text" name="research_texts[]" value="<?php echo $text ?>">
                                </div>
                                <?php
                            }
                        }else{
                           ?>
                            <div class="textInput">
                                <input class="text__input" placeholder="text" type="text" name="research_texts[]" value="">
                            </div>
                           <?php
                        }
                        break;
                    case 'image':
                        ?>
                        <div id="rImages" class="rImages">
                            <?php
                            if(is_array($research_images) && sizeof($research_images) > 0){
                                foreach($research_images as $key => $image){
                                    ?>
                                    <div class="image_research"> 
                                        <span class="removeImg">+</span> 
                                        <div class="previewBox"> 
                                            <img src="<?php echo $image['image'] ?>" class="imgPreview"> 
                                            <input type="hidden" name="research_images[<?php echo $key ?>][img]" value="<?php echo $image['image'] ?>"> 
                                            <button class="uploadImg button-secondary">Upload</button> 
                                        </div> 
                                        <textarea name="research_images[<?php echo $key ?>][desc]"><?php echo $image['desc'] ?></textarea> 
                                    </div>
                                    <?php
                                }
                            }else{
                                ?>
                                <div class="image_research"> 
                                    <span class="removeImg">+</span> 
                                    <div class="previewBox"> 
                                        <img src="" class="imgPreview"> 
                                        <input type="hidden" name="research_images[][img]" value=""> 
                                        <button class="uploadImg button-secondary">Upload</button> 
                                    </div>
                                    <textarea name="research_images[][desc]"></textarea> </div>
                                <?php
                            }
                            ?>
                        </div> 
                        <button id="addImgField" class="button-secondary">Add research item</button>
                        <?php
                        break;
                    default:
                        ?>
                        <div class="textInput">
                            <input class="text__input" placeholder="text" type="text" name="research_texts[]" value="">
                        </div>
                        <?php
                        break;
                }
                ?>
                
            </div>
        </div>
    </div>
</div>