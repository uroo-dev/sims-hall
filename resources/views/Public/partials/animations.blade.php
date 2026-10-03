{{-- Public GSAP & ScrollTrigger Animations Partial --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined') return;

        // Register ScrollTrigger plugin if available
        if (typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }

        // ============================================================
        // 1. HERO ENTRANCE ANIMATIONS
        // ============================================================
        const heroSection = document.getElementById('hero') || document.querySelector('section#produk') || document.querySelector('section#kesiswaan');
        if (heroSection) {
            const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });

            // Text elements stagger
            const heroTexts = heroSection.querySelectorAll('span.uppercase, span.text-brand-blue, h1, p, .pt-2, .pt-4');
            if (heroTexts.length > 0) {
                heroTl.from(heroTexts, {
                    y: 25,
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.12,
                    clearProps: 'transform,opacity'
                });
            }

            // Visual elements (image/video/card collage)
            const heroVisual = heroSection.querySelector('video, img, .aspect-\\[4\\/3\\], .relative.w-full.max-w-\\[500px\\]');
            if (heroVisual) {
                heroTl.from(heroVisual, {
                    scale: 0.94,
                    opacity: 0,
                    duration: 0.9,
                    clearProps: 'transform,opacity'
                }, '-=0.5');
            }

            // Subtle floating effect on decorative background items
            const floatingShapes = document.querySelectorAll('.dot-pattern, .plus-tex');
            floatingShapes.forEach((shape, i) => {
                gsap.to(shape, {
                    y: (i % 2 === 0 ? -10 : 10),
                    duration: 3 + (i * 0.5),
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });
            });
        }

        // ============================================================
        // 2. SCROLLTRIGGER SECTION ANIMATIONS (Only if ScrollTrigger loaded)
        // ============================================================
        if (typeof ScrollTrigger !== 'undefined') {

            // Section Titles & Subtitles reveal
            document.querySelectorAll('section').forEach(section => {
                const header = section.querySelector('.text-center.max-w-3xl, .mb-10.scroll-mt-28, .mb-14, .mb-16');
                if (header) {
                    gsap.from(header.children, {
                        scrollTrigger: {
                            trigger: header,
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        },
                        y: 25,
                        opacity: 0,
                        duration: 0.7,
                        stagger: 0.15,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            });

            // Jurusan Cards on Landing Page
            const jurusanGrid = document.querySelector('#jurusan .grid');
            if (jurusanGrid && jurusanGrid.children.length > 0) {
                gsap.from(jurusanGrid.children, {
                    scrollTrigger: {
                        trigger: jurusanGrid,
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    },
                    y: 40,
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    ease: 'back.out(1.2)',
                    clearProps: 'transform,opacity'
                });
            }

            // Sambutan Kepala Sekolah
            const sambutanSec = document.getElementById('sambutan');
            if (sambutanSec) {
                const sambutanImg = sambutanSec.querySelector('img');
                const sambutanText = sambutanSec.querySelector('.space-y-6, .lg\\:col-span-8');
                if (sambutanImg && sambutanText) {
                    gsap.from(sambutanImg, {
                        scrollTrigger: { trigger: sambutanSec, start: 'top 80%' },
                        x: -35,
                        opacity: 0,
                        duration: 0.9,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                    gsap.from(sambutanText, {
                        scrollTrigger: { trigger: sambutanSec, start: 'top 80%' },
                        x: 35,
                        opacity: 0,
                        duration: 0.9,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            }

            // News / Berita Cards
            const beritaSec = document.getElementById('berita');
            if (beritaSec) {
                const beritaCards = beritaSec.querySelectorAll('article, .bg-white.rounded-2xl, .bg-white.rounded-3xl');
                if (beritaCards.length > 0) {
                    gsap.from(beritaCards, {
                        scrollTrigger: { trigger: beritaSec, start: 'top 80%' },
                        y: 35,
                        opacity: 0,
                        duration: 0.75,
                        stagger: 0.12,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            }

            // Fasilitas Cards
            const fasilitasSec = document.getElementById('fasilitas');
            if (fasilitasSec) {
                const fCards = fasilitasSec.querySelectorAll('.grid > div');
                if (fCards.length > 0) {
                    gsap.from(fCards, {
                        scrollTrigger: { trigger: fasilitasSec, start: 'top 85%' },
                        y: 30,
                        opacity: 0,
                        duration: 0.7,
                        stagger: 0.1,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            }

            // Mitra DUDI Logos
            const mitraGrids = document.querySelectorAll('#mitra .grid, section .grid.items-center.justify-items-center');
            mitraGrids.forEach(grid => {
                if (grid.children.length > 0) {
                    gsap.from(grid.children, {
                        scrollTrigger: { trigger: grid, start: 'top 90%' },
                        scale: 0.85,
                        opacity: 0,
                        duration: 0.6,
                        stagger: 0.06,
                        ease: 'back.out(1.5)',
                        clearProps: 'transform,opacity'
                    });
                }
            });

            // Ekstrakurikuler Cards in Kesiswaan Page
            const eskulGrid = document.querySelector('#ekstrakurikuler .grid');
            if (eskulGrid && eskulGrid.children.length > 0) {
                gsap.from(eskulGrid.children, {
                    scrollTrigger: { trigger: eskulGrid, start: 'top 85%' },
                    y: 30,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.08,
                    ease: 'power2.out',
                    clearProps: 'transform,opacity'
                });
            }

            // Prestasi Cards in Kesiswaan Page
            const prestasiGrid = document.querySelector('#prestasi .grid.grid-cols-1');
            if (prestasiGrid && prestasiGrid.children.length > 0) {
                gsap.from(prestasiGrid.children, {
                    scrollTrigger: { trigger: prestasiGrid, start: 'top 85%' },
                    y: 30,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.08,
                    ease: 'power2.out',
                    clearProps: 'transform,opacity'
                });
            }

            // Product Cards in Produk Unggulan Page
            const productGrids = document.querySelectorAll('[id^="jurusan-"] .grid');
            productGrids.forEach(grid => {
                if (grid.children.length > 0) {
                    gsap.from(grid.children, {
                        scrollTrigger: { trigger: grid, start: 'top 85%' },
                        y: 30,
                        opacity: 0,
                        duration: 0.7,
                        stagger: 0.07,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            });

            // Counter Animations for numbers
            document.querySelectorAll('[data-counter-target]').forEach(counter => {
                const targetValue = parseInt(counter.getAttribute('data-counter-target'), 10) || 0;
                gsap.fromTo(counter, 
                    { innerText: 0 }, 
                    {
                        innerText: targetValue,
                        duration: 1.5,
                        ease: 'power2.out',
                        scrollTrigger: { trigger: counter, start: 'top 90%' },
                        snap: { innerText: 1 },
                        onUpdate: function () {
                            counter.innerText = Math.floor(counter.innerText);
                        }
                    }
                );
            });
        }
    });
</script>
