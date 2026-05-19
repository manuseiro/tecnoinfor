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

    // Toggle Topbar on Scroll
    const navbarTop = document.querySelector('.navbar-top');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            if (navbarTop) navbarTop.classList.add('hidden');
        } else {
            if (navbarTop) navbarTop.classList.remove('hidden');
        }
    });

    // Animação countup and progress highlight bars
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

                    // Animate the associated progress bar inside the stat item
                    const parentCard = el.closest('.stat-item');
                    if (parentCard) {
                        const progressBar = parentCard.querySelector('.stat-progress');
                        if (progressBar) {
                            setTimeout(() => {
                                progressBar.style.width = '70%';
                            }, 100);
                        }
                    }

                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.2 });

        countupElements.forEach(el => observer.observe(el));
    }

    // Software Carousel progress linear bar
    const softwareCarousel = document.getElementById('softwareCarousel');
    const softwareProgressBar = document.querySelector('.software-progress-bar');
    if (softwareCarousel && softwareProgressBar) {
        // Start initial width animation
        setTimeout(() => {
            softwareProgressBar.style.width = '100%';
        }, 100);

        softwareCarousel.addEventListener('slide.bs.carousel', function() {
            softwareProgressBar.style.transition = 'none';
            softwareProgressBar.style.width = '0%';
        });

        softwareCarousel.addEventListener('slid.bs.carousel', function() {
            // Force browser reflow to reset transition
            softwareProgressBar.offsetHeight;
            softwareProgressBar.style.transition = 'width 5s linear';
            softwareProgressBar.style.width = '100%';
        });
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
        const closeBtn = document.getElementById('lgpd-close');

        const setConsent = function(value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = "lgpd_consent=" + value + expires + "; path=/; SameSite=Lax";
            lgpdBanner.style.display = 'none';
        };

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function() {
                setConsent('accepted', 365);
            });
        }

        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                setConsent('rejected', 30);
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                lgpdBanner.style.display = 'none';
            });
        }
    }

    // AJAX Newsletter Submission
    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterMessage = document.getElementById('newsletter-message');
    if (newsletterForm && newsletterMessage) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = newsletterForm.querySelector('input[name="newsletter_email"]');
            const email = emailInput.value;
            
            newsletterMessage.style.display = 'none';
            newsletterMessage.className = 'small mt-2 text-warning';
            newsletterMessage.textContent = 'Processando...';
            newsletterMessage.style.display = 'block';
            
            const formData = new FormData();
            formData.append('action', 'subscribe_newsletter');
            formData.append('email', email);
            
            const ajaxurl = (typeof tecnoinforStrings !== 'undefined') ? tecnoinforStrings.ajaxurl : '/wp-admin/admin-ajax.php';
            
            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    newsletterMessage.className = 'small mt-2 text-warning';
                    newsletterMessage.textContent = data.data;
                    newsletterForm.reset();
                } else {
                    newsletterMessage.className = 'small mt-2 text-danger';
                    newsletterMessage.textContent = data.data;
                }
            })
            .catch(() => {
                newsletterMessage.className = 'small mt-2 text-danger';
                newsletterMessage.textContent = 'Erro ao processar inscrição.';
            });
        });
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