<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SetupAssets extends BaseController
{
    public function index()
    {
        echo "<h1>Setup Assets</h1>";
        
        // Array direktori yang perlu dibuat
        $directories = [
            ROOTPATH . 'public/assets/css',
            ROOTPATH . 'public/assets/js',
            ROOTPATH . 'public/assets/img',
            ROOTPATH . 'public/uploads/buku',
            ROOTPATH . 'public/uploads/profile',
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0777, true)) {
                    echo "<p>Direktori berhasil dibuat: {$dir}</p>";
                } else {
                    echo "<p class='text-danger'>Gagal membuat direktori: {$dir}</p>";
                }
            } else {
                echo "<p>Direktori sudah ada: {$dir}</p>";
            }
        }
        
        // Buat file CSS
        $cssFiles = [
            'decorations.css' => $this->getDecorationsCSS(),
            'animations.css' => $this->getAnimationsCSS(),
            'dark-mode.css' => $this->getDarkModeCSS(),
        ];
        
        foreach ($cssFiles as $filename => $content) {
            $filepath = ROOTPATH . 'public/assets/css/' . $filename;
            if (file_put_contents($filepath, $content)) {
                echo "<p>File CSS berhasil dibuat: {$filepath}</p>";
            } else {
                echo "<p class='text-danger'>Gagal membuat file CSS: {$filepath}</p>";
            }
        }
        
        // Buat file JavaScript
        $jsFiles = [
            'main.js' => $this->getMainJS(),
        ];
        
        foreach ($jsFiles as $filename => $content) {
            $filepath = ROOTPATH . 'public/assets/js/' . $filename;
            if (file_put_contents($filepath, $content)) {
                echo "<p>File JavaScript berhasil dibuat: {$filepath}</p>";
            } else {
                echo "<p class='text-danger'>Gagal membuat file JavaScript: {$filepath}</p>";
            }
        }
        
        // Buat file SVG
        $svgFiles = [
            'decor-books.svg' => $this->getDecorBooksSVG(),
            'decor-leaves.svg' => $this->getDecorLeavesSVG(),
            'decor-dots.svg' => $this->getDecorDotsSVG(),
            'decor-corner.svg' => $this->getDecorCornerSVG(),
        ];
        
        foreach ($svgFiles as $filename => $content) {
            $filepath = ROOTPATH . 'public/assets/img/' . $filename;
            if (file_put_contents($filepath, $content)) {
                echo "<p>File SVG berhasil dibuat: {$filepath}</p>";
            } else {
                echo "<p class='text-danger'>Gagal membuat file SVG: {$filepath}</p>";
            }
        }
        
        echo "<p><a href='" . base_url('admin/dashboard') . "' class='btn btn-primary'>Kembali ke Dashboard</a></p>";
        
        return;
    }
    
    private function getDecorationsCSS()
    {
        return '/* Dekorasi untuk website perpustakaan */
.decor-books {
    background-image: url(\'/assets/img/decor-books.svg\');
    background-repeat: repeat-x;
    background-size: 60px;
    height: 30px;
    opacity: 0.2;
}

.decor-leaves {
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background-image: url(\'/assets/img/decor-leaves.svg\');
    background-repeat: no-repeat;
    background-size: contain;
    opacity: 0.1;
    z-index: 0;
    pointer-events: none;
}

.decor-dots {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 180px;
    height: 180px;
    background-image: url(\'/assets/img/decor-dots.svg\');
    background-repeat: no-repeat;
    background-size: contain;
    opacity: 0.1;
    z-index: 0;
    pointer-events: none;
}

.page-title-decoration {
    position: relative;
    padding-bottom: 10px;
}

.page-title-decoration::after {
    content: \'\';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 60px;
    background: linear-gradient(90deg, #795548 0%, #FFCC80 100%);
    border-radius: 10px;
}

.decorated-card {
    position: relative;
    overflow: hidden;
}

.decorated-card::before {
    content: \'\';
    position: absolute;
    top: 0;
    right: 0;
    width: 50px;
    height: 50px;
    background-image: url(\'/assets/img/decor-corner.svg\');
    background-repeat: no-repeat;
    background-size: contain;
    opacity: 0.2;
    z-index: 1;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.floating-decor {
    animation: float 5s ease-in-out infinite;
}';
    }
    
    private function getAnimationsCSS()
    {
        return '/* Animasi dan efek JavaScript */

/* Fade-in */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Notifikasi */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 25px 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.notification.success {
    background-color: #DFF2BF;
    color: #4F8A10;
    border-left: 4px solid #4F8A10;
}

.notification.error {
    background-color: #FFBABA;
    color: #D8000C;
    border-left: 4px solid #D8000C;
}

.notification.info {
    background-color: #BDE5F8;
    color: #00529B;
    border-left: 4px solid #00529B;
}

.notification.fadeout {
    opacity: 0;
    transform: translateY(-20px);
}

.notification-close {
    position: absolute;
    top: 8px;
    right: 8px;
    cursor: pointer;
    font-size: 16px;
    color: inherit;
}

/* Tooltip */
.custom-tooltip {
    position: absolute;
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 1000;
    pointer-events: none;
    animation: fadeIn 0.2s ease;
    white-space: nowrap;
}

.custom-tooltip:before {
    content: "";
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 0 6px 6px 6px;
    border-style: solid;
    border-color: transparent transparent rgba(0, 0, 0, 0.8) transparent;
}

/* Ripple effect */
.btn-ripple {
    position: relative;
    overflow: hidden;
}

.ripple {
    position: absolute;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    width: 100px;
    height: 100px;
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Mobile sidebar */
#sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

#sidebar.sidebar-open {
    transform: translateX(0);
}

/* Image lazy loading */
img.img-loaded {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}';
    }
    
    private function getDarkModeCSS()
    {
        return '/* Dark Mode Styles */
body.dark-mode {
    background-color: #1e1e1e !important;
    color: #f0f0f0 !important;
}

body.dark-mode .bg-white,
body.dark-mode .bg-background-light {
    background-color: #2d2d2d !important;
    color: #f0f0f0;
}

body.dark-mode .bg-background {
    background-color: #1e1e1e !important;
}

body.dark-mode .text-gray-800,
body.dark-mode .text-dark {
    color: #f0f0f0 !important;
}

body.dark-mode .text-gray-700,
body.dark-mode .text-gray-600,
body.dark-mode .text-gray-500 {
    color: #b0b0b0 !important;
}

body.dark-mode .border-gray-300,
body.dark-mode .border-gray-200,
body.dark-mode .border-gray-100 {
    border-color: #444 !important;
}

body.dark-mode input,
body.dark-mode select,
body.dark-mode textarea {
    background-color: #333 !important;
    color: #f0f0f0 !important;
    border-color: #555 !important;
}

body.dark-mode table thead {
    background-color: #333 !important;
}

body.dark-mode tr:hover {
    background-color: #333 !important;
}

body.dark-mode .dark-toggle {
    background-color: #795548;
}

body.dark-mode .dark-toggle .toggle-circle {
    transform: translateX(20px);
    background-color: #fff;
}

/* Toggle Switch Styling */
.dark-toggle {
    width: 50px;
    height: 24px;
    background-color: #ccc;
    border-radius: 12px;
    padding: 2px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dark-toggle i {
    font-size: 12px;
    color: white;
    margin: 0 4px;
    z-index: 1;
}

.toggle-circle {
    width: 20px;
    height: 20px;
    background-color: white;
    border-radius: 50%;
    position: absolute;
    left: 2px;
    transition: transform 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

body.dark-mode .dark-toggle {
    background-color: #795548;
}

body.dark-mode .dark-toggle .toggle-circle {
    transform: translateX(26px);
}

/* Chart styling for dark mode */
body.dark-mode canvas {
    filter: invert(0.85) hue-rotate(180deg);
}';
    }
    
    private function getMainJS()
    {
        return '/**
 * Main JavaScript file untuk Perpustakaan Fachri
 */

// Fungsi untuk animasi fade-in elemen
function fadeInElements() {
    const fadeElements = document.querySelectorAll(\'.fade-in\');
    fadeElements.forEach((element, index) => {
        const delay = index * 100; // 100ms delay antar elemen
        setTimeout(() => {
            element.classList.add(\'visible\');
        }, delay);
    });
}

// Fungsi untuk menangani notifikasi
function initNotifications() {
    const notifications = document.querySelectorAll(\'.notification\');
    
    notifications.forEach(notification => {
        // Auto-dismiss setelah 5 detik
        setTimeout(() => {
            notification.classList.add(\'fadeout\');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 5000);
        
        // Tombol tutup notifikasi
        const closeBtn = notification.querySelector(\'.notification-close\');
        if (closeBtn) {
            closeBtn.addEventListener(\'click\', () => {
                notification.classList.add(\'fadeout\');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            });
        }
    });
}

// Inisialisasi tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll(\'[data-tooltip]\');
    
    tooltips.forEach(tooltip => {
        tooltip.addEventListener(\'mouseenter\', (e) => {
            const text = e.target.getAttribute(\'data-tooltip\');
            
            const tooltipEl = document.createElement(\'div\');
            tooltipEl.className = \'custom-tooltip\';
            tooltipEl.textContent = text;
            
            document.body.appendChild(tooltipEl);
            
            const rect = e.target.getBoundingClientRect();
            tooltipEl.style.top = `${rect.bottom + window.scrollY + 5}px`;
            tooltipEl.style.left = `${rect.left + window.scrollX + (rect.width / 2) - (tooltipEl.offsetWidth / 2)}px`;
        });
        
        tooltip.addEventListener(\'mouseleave\', () => {
            const tooltips = document.querySelectorAll(\'.custom-tooltip\');
            tooltips.forEach(el => el.remove());
        });
    });
}

// Efek ripple untuk tombol
function addRippleEffect() {
    const buttons = document.querySelectorAll(\'.btn-ripple\');
    
    buttons.forEach(button => {
        button.addEventListener(\'click\', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const circle = document.createElement(\'span\');
            circle.classList.add(\'ripple\');
            circle.style.top = y + \'px\';
            circle.style.left = x + \'px\';
            
            this.appendChild(circle);
            
            setTimeout(() => {
                circle.remove();
            }, 600);
        });
    });
}

// Fungsi untuk animasi counter
function animateCounters() {
    const counters = document.querySelectorAll(\'.counter\');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute(\'data-count\'), 10);
        if (isNaN(target)) return;
        
        const duration = 1500; // 1.5 detik
        const stepTime = 30;
        const steps = duration / stepTime;
        const increment = target / steps;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, stepTime);
    });
}

// Toggle sidebar mobile
function initSidebar() {
    const sidebarToggle = document.getElementById(\'sidebar-toggle\');
    const sidebar = document.getElementById(\'sidebar\');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener(\'click\', () => {
            sidebar.classList.toggle(\'sidebar-open\');
        });
        
        // Tutup sidebar saat klik di luar
        document.addEventListener(\'click\', (e) => {
            if (sidebar.classList.contains(\'sidebar-open\') && 
                !sidebar.contains(e.target) && 
                e.target !== sidebarToggle) {
                sidebar.classList.remove(\'sidebar-open\');
            }
        });
    }
}

// Dark mode toggle
function initDarkMode() {
    const darkModeToggle = document.getElementById(\'darkModeToggle\');
    if (!darkModeToggle) return;
    
    const body = document.body;
    
    // Memperbarui toggle berdasarkan localStorage
    const isDarkMode = localStorage.getItem(\'darkMode\') === \'true\';
    if (isDarkMode) {
        body.classList.add(\'dark-mode\');
    }
    
    // Tambahkan ikon jika belum ada
    if (!darkModeToggle.querySelector(\'.fa-sun\') && !darkModeToggle.querySelector(\'.fa-moon\')) {
        const sunIcon = document.createElement(\'i\');
        sunIcon.className = \'fas fa-sun\';
        const moonIcon = document.createElement(\'i\');
        moonIcon.className = \'fas fa-moon\';
        
        darkModeToggle.prepend(sunIcon);
        darkModeToggle.append(moonIcon);
    }
    
    darkModeToggle.addEventListener(\'click\', function() {
        body.classList.toggle(\'dark-mode\');
        localStorage.setItem(\'darkMode\', body.classList.contains(\'dark-mode\'));
    });
}

// Lazy loading images
function lazyLoadImages() {
    if (\'IntersectionObserver\' in window) {
        const imgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute(\'data-src\');
                    
                    if (src) {
                        img.src = src;
                        img.removeAttribute(\'data-src\');
                        img.classList.add(\'img-loaded\');
                    }
                    
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll(\'img[data-src]\').forEach(img => {
            imgObserver.observe(img);
        });
    } else {
        // Fallback for browsers without IntersectionObserver
        document.querySelectorAll(\'img[data-src]\').forEach(img => {
            img.src = img.getAttribute(\'data-src\');
            img.classList.add(\'img-loaded\');
        });
    }
}

// Inisialisasi semua fungsi saat DOM sudah siap
document.addEventListener(\'DOMContentLoaded\', function() {
    fadeInElements();
    initNotifications();
    initTooltips();
    addRippleEffect();
    animateCounters();
    initSidebar();
    initDarkMode();
    lazyLoadImages();
    
    console.log(\'Perpustakaan Fachri JS initialized! ✨\');
});';
    }
    
    private function getDecorBooksSVG()
    {
        return '<svg width="60" height="30" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
  <g fill="#795548">
    <rect x="2" y="5" width="8" height="25" rx="1" />
    <rect x="12" y="7" width="6" height="23" rx="1" />
    <rect x="20" y="3" width="10" height="27" rx="1" />
    <rect x="32" y="8" width="7" height="22" rx="1" />
    <rect x="41" y="5" width="9" height="25" rx="1" />
    <rect x="52" y="6" width="6" height="24" rx="1" />
  </g>
</svg>';
    }
    
    private function getDecorLeavesSVG()
    {
        return '<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
  <path d="M160,40 C130,10 90,20 80,60 C70,20 30,10 0,40 C0,100 40,140 80,180 C120,140 160,100 160,40Z" fill="#795548" />
  <path d="M150,50 C125,25 95,30 85,65 C75,30 45,25 20,50 C20,100 50,135 85,170 C120,135 150,100 150,50Z" fill="#FFCC80" />
</svg>';
    }
    
    private function getDecorDotsSVG()
    {
        return '<svg width="180" height="180" viewBox="0 0 180 180" xmlns="http://www.w3.org/2000/svg">
  <g fill="#795548">
    <circle cx="10" cy="10" r="4" />
    <circle cx="30" cy="10" r="4" />
    <circle cx="50" cy="10" r="4" />
    <circle cx="70" cy="10" r="4" />
    <circle cx="90" cy="10" r="4" />
    <circle cx="110" cy="10" r="4" />
    <circle cx="130" cy="10" r="4" />
    <circle cx="150" cy="10" r="4" />
    <circle cx="170" cy="10" r="4" />
    
    <circle cx="10" cy="30" r="4" />
    <circle cx="10" cy="50" r="4" />
    <circle cx="10" cy="70" r="4" />
    <circle cx="10" cy="90" r="4" />
    <circle cx="10" cy="110" r="4" />
    <circle cx="10" cy="130" r="4" />
    <circle cx="10" cy="150" r="4" />
    <circle cx="10" cy="170" r="4" />
    
    <circle cx="30" cy="30" r="4" />
    <circle cx="50" cy="50" r="4" />
    <circle cx="70" cy="70" r="4" />
    <circle cx="90" cy="90" r="4" />
    <circle cx="110" cy="110" r="4" />
    <circle cx="130" cy="130" r="4" />
    <circle cx="150" cy="150" r="4" />
    <circle cx="170" cy="170" r="4" />
  </g>
</svg>';
    }
    
    private function getDecorCornerSVG()
    {
        return '<svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
  <path d="M0,0 L50,0 L50,50 C25,50 0,25 0,0 Z" fill="#795548" />
</svg>';
    }
}
