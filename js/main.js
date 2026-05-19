document.addEventListener('DOMContentLoaded', function() {
    // Biblioteca WOW.js
    if (typeof WOW !== 'undefined') {
        new WOW({
            offset: 100,
            duration: 1000,
            delay: 500,
            mobile: true,
        }).init();
    }

    // Animação countup
    const countupElements = document.querySelectorAll('.countup');
    if (countupElements.length > 0) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const targetCount = parseInt(el.getAttribute('data-count'), 10) || 0;
                    const duration = 2000;
                    const frameDuration = 1000 / 60;
                    const totalFrames = Math.round(duration / frameDuration);
                    let frame = 0;

                    const counter = setInterval(() => {
                        frame++;
                        const progress = frame / totalFrames;
                        const currentCount = Math.round(targetCount * progress);
                        el.textContent = currentCount.toLocaleString('pt-BR');

                        if (frame === totalFrames) {
                            clearInterval(counter);
                            el.textContent = targetCount.toLocaleString('pt-BR');
                        }
                    }, frameDuration);

                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        countupElements.forEach(el => observer.observe(el));
    }

    // Contact Form 7 - Loading Feedback
    document.addEventListener('wpcf7beforesubmit', function(event) {
        const form = event.target;
        const submitBtn = form.querySelector('.wpcf7-submit');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.setAttribute('data-original-html', submitBtn.innerHTML || submitBtn.value);
            if (submitBtn.tagName === 'INPUT') {
                submitBtn.value = 'Enviando...';
            } else {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando...';
            }
        }
    }, false);

    const restoreSubmitButton = function(event) {
        const form = event.target;
        const submitBtn = form.querySelector('.wpcf7-submit');
        if (submitBtn) {
            submitBtn.disabled = false;
            const originalHtml = submitBtn.getAttribute('data-original-html');
            if (originalHtml) {
                if (submitBtn.tagName === 'INPUT') {
                    submitBtn.value = originalHtml;
                } else {
                    submitBtn.innerHTML = originalHtml;
                }
            }
        }
    };
    document.addEventListener('wpcf7submit', restoreSubmitButton, false);

    // LGPD Consent Banner
    const lgpdBanner = document.getElementById('lgpd-consent-banner');
    if (lgpdBanner) {
        const consentCookie = document.cookie.split('; ').find(row => row.startsWith('lgpd_consent='));
        if (!consentCookie) {
            lgpdBanner.classList.remove('d-none');
        } else {
            lgpdBanner.style.display = 'none';
        }

        const acceptBtn = document.getElementById('lgpd-accept');
        const rejectBtn = document.getElementById('lgpd-reject');

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function() {
                // Set cookie for 1 year
                const d = new Date();
                d.setTime(d.getTime() + (365*24*60*60*1000));
                document.cookie = "lgpd_consent=accepted; expires=" + d.toUTCString() + "; path=/; SameSite=Lax";
                lgpdBanner.style.display = 'none';
            });
        }

        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                // Set cookie for session or shorter
                document.cookie = "lgpd_consent=rejected; path=/; SameSite=Lax";
                lgpdBanner.style.display = 'none';
            });
        }
    }

    // Animação de entrada nos cards de notícias
    const newsCards = document.querySelectorAll('.news .card');
    if (newsCards.length > 0) {
        const newsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        newsCards.forEach(card => newsObserver.observe(card));
    }
});

jQuery(document).ready(function($) {
    $('.read-more').on('click keypress', function(e) {
        if (e.type === 'click' || e.which === 13) {
            var $btn  = $(this);
            var $text = $btn.prev('.testimonial-text');
            var fullText  = $text.data('full-text');
            // Texto localizado injetado via wp_localize_script()
            var readMore = (typeof tecnoinforStrings !== 'undefined') ? tecnoinforStrings.readMore : 'Read more';
            var readLess = (typeof tecnoinforStrings !== 'undefined') ? tecnoinforStrings.readLess : 'Read less';

            if ($text.hasClass('expanded')) {
                $text.data('short-text', $text.data('short-text') || $text.text());
                $text.text($text.data('short-text')).removeClass('expanded');
                $btn.text(readMore);
            } else {
                if (!$text.data('short-text')) {
                    $text.data('short-text', $text.text());
                }
                $text.text(fullText).addClass('expanded');
                $btn.text(readLess);
            }
        }
    });
});