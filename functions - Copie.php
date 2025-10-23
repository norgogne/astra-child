<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

function astra_child_custom_css() {
    wp_enqueue_style('astra-custom-style', get_stylesheet_directory_uri() . '/style-custom.css', array(), null);
}
add_action('wp_enqueue_scripts', 'astra_child_custom_css');


/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/** Paramétrage du lire la suite */
function astra_custom_excerpt_more( $more ) {
    global $post;
    return '<br> <br> <a class="read-more" href="' . get_permalink( $post->ID ) . '">Lire la suite →</a>';
}
add_filter( 'excerpt_more', 'astra_custom_excerpt_more' );



// ==== FORMULAIRE PERSONNALISÉ ASTRA : PAGE PROTÉGÉE PAR MOT DE PASSE ====

// Filtre pour personnaliser le formulaire
add_filter('the_password_form', function () {
    ob_start();
    ?>

    <div class="password-form-wrapper">
        <form class="post-password-form" 
              action="<?php echo esc_url(site_url('wp-login.php?action=postpass', 'login_post')); ?>" 
              method="post">

            <div class="password-form-header">
                <!-- LOGO -->
                <img src="<?php echo esc_url(get_site_icon_url(128)); ?>" 
                     alt="Logo du site" 
                     class="custom-logo" />
                <p class="password-form-title">Cette page est protégée par mot de passe</p>
            </div>

            <label for="pwbox-<?php echo esc_attr(get_the_ID()); ?>">Entrez votre mot de passe :</label>

            <div class="password-input-container" style="position: relative; display: inline-block;">
                <input
                    name="post_password"
                    id="pwbox-<?php echo esc_attr(get_the_ID()); ?>"
                    type="password"
                    size="20"
                    placeholder="Mot de passe"
                    class="ast-input"
                    required
                />
                <button type="button" class="toggle-password-btn" aria-label="Afficher le mot de passe">👁️</button>
            </div>

            <input type="submit" name="Submit" value="Entrer" class="ast-button" />
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".password-input-container").forEach(container => {
            const input = container.querySelector("input[type='password'], input[type='text']");
            const toggleBtn = container.querySelector(".toggle-password-btn");
            if (input && toggleBtn) {
                toggleBtn.addEventListener("click", () => {
                    const isPassword = input.type === "password";
                    input.type = isPassword ? "text" : "password";
                    toggleBtn.textContent = isPassword ? "🙈" : "👁️";
                });
            }
        });
    });
    </script>

    <?php
    return ob_get_clean();
});

// Supprime le cookie (demande le mot de passe à chaque visite)
function reset_post_password_cookie() {
    if (isset($_COOKIE['wp-postpass_' . COOKIEHASH])) {
        setcookie('wp-postpass_' . COOKIEHASH, '', time() - 3600, COOKIEPATH);
    }
}
add_action('template_redirect', 'reset_post_password_cookie');

// ==== FIN FORMULAIRE PERSONNALISÉ ASTRA : PAGE PROTÉGÉE PAR MOT DE PASSE ====




