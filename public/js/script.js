// ТОВ "ТеплоЭнергоСтрой" - Enhanced JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ТОВ "ТеплоЭнергоСтрой" loaded!');

    // =================== Scroll Animations ===================
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.service-card, .review-card, .stat-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-on-scroll', 'visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        elements.forEach(el => observer.observe(el));
    };

    animateOnScroll();

    // =================== Scroll to Top Button ===================
    const createScrollToTopButton = () => {
        const button = document.createElement('button');
        button.className = 'scroll-to-top';
        button.innerHTML = '↑';
        button.setAttribute('aria-label', 'Scroll to top');
        document.body.appendChild(button);

        // Show/hide button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        });

        // Scroll to top on click
        button.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    };

    createScrollToTopButton();

    // =================== Smooth Scroll for Anchor Links ===================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offset = 80; // Header height
                const targetPosition = target.offsetTop - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // =================== Enhanced Form Validation ===================
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');

        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });

        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();

                // Show error message
                showNotification('Будь ласка, заповніть всі обов\'язкові поля', 'error');

                // Focus on first error field
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Remove previous error
        field.classList.remove('error');
        const existingError = field.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Це поле обов\'язкове';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Введіть правильну email адресу';
            }
        }

        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\d\s\+\-\(\)]+$/;
            if (!phoneRegex.test(value) || value.length < 10) {
                isValid = false;
                errorMessage = 'Введіть правильний номер телефону';
            }
        }

        // Min length validation
        if (field.hasAttribute('minlength') && value) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (value.length < minLength) {
                isValid = false;
                errorMessage = `Мінімум ${minLength} символів`;
            }
        }

        if (!isValid) {
            field.classList.add('error');
            const error = document.createElement('div');
            error.className = 'error-message';
            error.style.color = '#dc3545';
            error.style.fontSize = '13px';
            error.style.marginTop = '5px';
            error.textContent = errorMessage;
            field.parentElement.appendChild(error);
        }

        return isValid;
    }

    // =================== Auto-hide Alerts ===================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);

        // Allow manual closing
        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = 'float: right; cursor: pointer; font-size: 24px; line-height: 1; margin-left: 15px;';
        closeBtn.onclick = () => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        };
        alert.insertBefore(closeBtn, alert.firstChild);
    });

    // =================== Notification System ===================
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; max-width: 400px; animation: fadeIn 0.3s ease-out;';
        notification.textContent = message;

        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = 'float: right; cursor: pointer; font-size: 24px; line-height: 1; margin-left: 15px;';
        closeBtn.onclick = () => notification.remove();
        notification.insertBefore(closeBtn, notification.firstChild);

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Make notification available globally
    window.showNotification = showNotification;

    // =================== Slug Generator for Admin Forms ===================
    const titleInput = document.querySelector('#title');
    const slugInput = document.querySelector('#slug');

    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[а-яґєії]/g, char => {
                    const dict = {
                        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'ґ': 'g', 'д': 'd',
                        'е': 'e', 'є': 'ye', 'ж': 'zh', 'з': 'z', 'и': 'y', 'і': 'i',
                        'ї': 'yi', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                        'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
                        'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'ts', 'ч': 'ch',
                        'ш': 'sh', 'щ': 'shch', 'ь': '', 'ю': 'yu', 'я': 'ya'
                    };
                    return dict[char] || char;
                })
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

            slugInput.value = slug;
        });
    }

    // =================== Number Counter Animation ===================
    const animateCounter = (element, target, duration = 2000) => {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    };

    // Animate stat numbers when visible
    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                const target = parseInt(entry.target.textContent);
                if (!isNaN(target)) {
                    entry.target.textContent = '0';
                    animateCounter(entry.target, target);
                }
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-card h3').forEach(el => {
        statObserver.observe(el);
    });

    // =================== Mobile Menu Toggle ===================
    const createMobileMenu = () => {
        const nav = document.querySelector('nav');
        if (!nav) return;

        const menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.innerHTML = '☰';
        menuToggle.style.cssText = 'display: none; font-size: 28px; background: none; border: none; cursor: pointer; color: var(--primary);';

        menuToggle.onclick = () => {
            nav.querySelector('ul').classList.toggle('active');
            menuToggle.innerHTML = menuToggle.innerHTML === '☰' ? '✕' : '☰';
        };

        // Add media query style for mobile
        if (window.innerWidth <= 768) {
            nav.parentElement.insertBefore(menuToggle, nav);
            menuToggle.style.display = 'block';

            const navUl = nav.querySelector('ul');
            navUl.style.cssText = 'display: none;';

            navUl.classList.add('mobile-menu');
            const style = document.createElement('style');
            style.textContent = `
                .mobile-menu.active {
                    display: flex !important;
                    flex-direction: column;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: white;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                    padding: 20px;
                    z-index: 1000;
                }
            `;
            document.head.appendChild(style);
        }
    };

    if (window.innerWidth <= 768) {
        createMobileMenu();
    }

    // =================== Loading State for Forms ===================
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !form.querySelector('.error')) {
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading"></span> Відправка...';

                // Re-enable after 5 seconds (in case submission takes long)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                }, 5000);
            }
        });
    });

    // =================== Image Lazy Loading ===================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // =================== Parallax Effect ===================
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const hero = document.querySelector('.hero');
                if (hero) {
                    const scrolled = window.pageYOffset;
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
                ticking = false;
            });
            ticking = true;
        }
    });

    // =================== Copy to Clipboard ===================
    document.querySelectorAll('[data-copy]').forEach(element => {
        element.style.cursor = 'pointer';
        element.addEventListener('click', function() {
            const text = this.dataset.copy || this.textContent;
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Скопійовано в буфер обміну!', 'success');
            });
        });
    });

    // =================== Rating Stars Interactive ===================
    document.querySelectorAll('.stars').forEach(starsContainer => {
        starsContainer.style.cursor = 'pointer';
        starsContainer.title = 'Оцінка';
    });

    console.log('✅ All enhancements loaded successfully!');
});

// =================== Service Worker for PWA (Optional) ===================
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Uncomment to enable PWA
        // navigator.serviceWorker.register('/sw.js');
    });
}
