<?php if (!defined('ABSPATH')) exit; ?>
<main class="template-main">
    <div class="container">
        <h1><?php esc_html_e('Contacto', 'cursos-online-wp'); ?></h1>
        <p><?php esc_html_e('Escríbenos sobre tus necesidades de capacitación. Estamos aquí para ayudarte.', 'cursos-online-wp'); ?></p>

        <?php if (isset($_GET['contact_form_status']) && $_GET['contact_form_status'] === 'success'): ?>
            <div class="contact-message success"><?php esc_html_e('¡Mensaje enviado con éxito! Te responderemos pronto.', 'cursos-online-wp'); ?></div>
        <?php elseif (isset($_GET['contact_form_status']) && $_GET['contact_form_status'] === 'error'): ?>
            <div class="contact-message error"><?php esc_html_e('Por favor completa todos los campos antes de enviar.', 'cursos-online-wp'); ?></div>
        <?php elseif (isset($_GET['contact_form_status']) && $_GET['contact_form_status'] === 'failed'): ?>
            <div class="contact-message error"><?php esc_html_e('Hubo un error al enviar el mensaje. Intenta nuevamente más tarde.', 'cursos-online-wp'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="contact-form">
            <?php wp_nonce_field('cursos_online_contact_form', 'cursos_online_contact_nonce'); ?>

            <label for="contact_name"><?php esc_html_e('Nombre completo', 'cursos-online-wp'); ?></label>
            <input type="text" name="contact_name" id="contact_name" required>

            <label for="contact_email"><?php esc_html_e('Correo electrónico', 'cursos-online-wp'); ?></label>
            <input type="email" name="contact_email" id="contact_email" required>

            <label for="contact_subject"><?php esc_html_e('Asunto', 'cursos-online-wp'); ?></label>
            <input type="text" name="contact_subject" id="contact_subject" required>

            <label for="contact_message"><?php esc_html_e('Mensaje', 'cursos-online-wp'); ?></label>
            <textarea name="contact_message" id="contact_message" rows="6" required></textarea>

            <button type="submit" name="cursos_online_contact_submit"><?php esc_html_e('Enviar mensaje', 'cursos-online-wp'); ?></button>
        </form>

        <div class="contact-detalles">
            <h2><?php esc_html_e('Datos de contacto', 'cursos-online-wp'); ?></h2>
            <ul>
                <li>Email: contacto@pccurico.cl</li>
                <li>WhatsApp: <?php echo esc_html(get_theme_mod('cursos_online_whatsapp_phone', '+56912345678')); ?></li>
                <li><?php esc_html_e('Oficina: Curicó, Chile', 'cursos-online-wp'); ?></li>
            </ul>
        </div>
    </div>
</main>
