<?php
/**
 * REST API Endpoints for LGPD Consent
 *
 * @package Tecnoinfor
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('tecnoinfor/v1', '/lgpd-consent', array(
        'methods'             => 'POST',
        'callback'            => 'tecnoinfor_save_lgpd_consent',
        'permission_callback' => '__return_true',
    ));
});

function tecnoinfor_save_lgpd_consent(WP_REST_Request $request) {
    $choice = sanitize_text_field($request->get_param('choice'));
    
    if (!in_array($choice, array('accepted', 'rejected', 'customized'), true)) {
        return new WP_Error('invalid', 'Invalid choice', array('status' => 400));
    }
    
    // Definir cookie httpOnly via PHP (seguro contra XSS)
    setcookie(
        'lgpd_consent',
        $choice,
        array(
            'expires'  => time() + YEAR_IN_SECONDS,
            'path'     => '/',
            'secure'   => is_ssl(),
            'httponly' => true,
            'samesite' => 'Lax',
        )
    );
    
    return array('status' => 'ok', 'choice' => $choice);
}
