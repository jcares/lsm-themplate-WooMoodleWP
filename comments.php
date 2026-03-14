<?php
/**
 * Comments Template
 * Muestra y gestiona comentarios de posts
 */
defined('ABSPATH') || exit;

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-wrapper">
    
    <?php if (have_comments()) : ?>
        
        <h3 class="comments-title" style="font-size: 20px; margin-bottom: 30px;">
            <?php
            $comment_count = number_format_i18n(get_comments_number());
            printf(
                esc_html(_n('%s comentario', '%s comentarios', get_comments_number(), 'cursos-online-wp')),
                $comment_count
            );
            ?>
        </h3>

        <!-- Lista de comentarios -->
        <ol class="comment-list" style="list-style: none; padding: 0; margin: 0;">
            <?php
            wp_list_comments([
                'style'      => 'div',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback'   => function($comment, $args, $depth) {
                    echo '<li id="comment-' . $comment->comment_ID . '" class="comment-item" style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 10px;">';
                    
                    echo '<div class="comment-author-avatar" style="float: left; margin-right: 15px;">';
                    echo get_avatar($comment, 60, '', '', ['class' => 'avatar', 'style' => 'border-radius: 50%;']);
                    echo '</div>';
                    
                    echo '<div class="comment-author-info" style="overflow: hidden;">';
                    echo '<strong class="comment-author" style="display: block; margin-bottom: 5px;">';
                    echo esc_html($comment->comment_author);
                    echo '</strong>';
                    
                    echo '<span class="comment-date" style="color: #999; font-size: 12px;">';
                    echo esc_html(get_comment_date(get_option('date_format'), $comment));
                    echo '</span>';
                    
                    echo '</div>';
                    
                    echo '<div class="comment-content" style="clear: left; margin-top: 15px;">';
                    echo wp_kses_post($comment->comment_content);
                    echo '</div>';
                    
                    echo '<div class="comment-actions" style="margin-top: 15px;">';
                    comment_reply_link([
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                        'reply_text' => esc_html__('Responder', 'cursos-online-wp'),
                        'before'     => '<a href="#" style="color: #667eea; text-decoration: none; font-weight: 600;">',
                        'after'      => '</a>',
                    ]);
                    echo '</div>';
                    
                    // El </li> se cierra automáticamente
                }
            ]);
            ?>
        </ol>

        <!-- Paginación de comentarios -->
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <div class="comment-pagination" style="text-align: center; margin: 30px 0;">
                <?php paginate_comments_links(); ?>
            </div>
        <?php endif; ?>

    <?php endif; // if (have_comments()) ?>

    <!-- Formulario de comentarios -->
    <div class="comment-respond" style="margin-top: 40px; padding-top: 40px; border-top: 1px solid #eee;">
        <?php
        $comment_form_args = [
            'title_reply' => esc_html__('Dejar un Comentario', 'cursos-online-wp'),
            'comment_field' => '<div class="form-group"><label for="comment" style="display: block; margin-bottom: 10px; font-weight: 600;">' . 
                esc_html__('Comentario', 'cursos-online-wp') . 
                ' <span style="color: #ff6b6b;">*</span></label>' .
                '<textarea id="comment" name="comment" cols="45" rows="8" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;"></textarea></div>',
            'fields' => [
                'author' => '<div class="form-group" style="width: 48%; display: inline-block; margin-right: 2%;"><label for="author" style="display: block; margin-bottom: 5px; font-weight: 600;">' .
                    esc_html__('Nombre', 'cursos-online-wp') . 
                    ' <span style="color: #ff6b6b;">*</span></label>' .
                    '<input id="author" name="author" type="text" value="" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"/></div>',
                'email' => '<div class="form-group" style="width: 48%; display: inline-block;"><label for="email" style="display: block; margin-bottom: 5px; font-weight: 600;">' .
                    esc_html__('Email', 'cursos-online-wp') . 
                    ' <span style="color: #ff6b6b;">*</span></label>' .
                    '<input id="email" name="email" type="email" value="" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"/></div>',
            ],
            'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="submit" style="background: #667eea; color: white; padding: 12px 30px; border: 0; border-radius: 5px; cursor: pointer; font-weight: 600;">%4$s</button>',
            'label_submit' => esc_html__('Enviar Comentario', 'cursos-online-wp'),
        ];
        
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $comment_form_args['logged_in_as'] = '<p style="margin-bottom: 20px;">' . 
                sprintf(
                    esc_html__('Conectado como %s. <a href="%s">Cerrar sesión</a>', 'cursos-online-wp'),
                    '<strong>' . esc_html($current_user->display_name) . '</strong>',
                    esc_url(wp_logout_url(get_permalink()))
                ) . 
                '</p>';
        }
        
        comment_form($comment_form_args);
        ?>
    </div>

</div><!-- #comments -->
