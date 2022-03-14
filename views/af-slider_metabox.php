<?php
    $meta = get_post_meta( $post->ID,  );
?>

<table class="form-table af-slider-metabox"> 
    <input type="hidden" name="af_slider_nonce" value="<?php echo wp_create_nonce( 'af_slider_nonce' ); ?>"   >
    <tr>
        <th>
            <label for="af_slider_link_text"><?php esc_html_e( 'Link Text', 'af-slider' ); ?></label>
        </th>
        <td>
            <input 
                type="text" 
                name="af_slider_link_text" 
                id="af_slider_link_text" 
                class="regular-text link-text"
                value="<?php echo ( isset( $meta['af_slider_link_text'][0] ) ) ? esc_html( $meta['af_slider_link_text'][0] ) : ''; ?>"
                required
            >
        </td>
    </tr>
    <tr>
        <th>
            <label for="af_slider_link_url"><?php esc_html_e( 'Link URL', 'af-slider' ) ?></label>
        </th>
        <td>
            <input 
                type="url" 
                name="af_slider_link_url" 
                id="af_slider_link_url" 
                class="regular-text link-url"
                value="<?php echo ( isset( $meta['af_slider_link_url'][0] ) ) ? esc_url( $meta['af_slider_link_url'][0] ) : ''; ?>"
                required
            >
        </td>
    </tr>               
</table>