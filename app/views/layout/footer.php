<footer>
    <div class="footer-grid">
        <div class="fb">
            <a href="/Tripverse/index.php" class="logo">
                <span class="logo-ball">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </span>
                <span style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:#fff;">TripVerse</span>
            </a>
            <p>Your journey, your story. Explore the world with us.</p>
            <div class="socials">
                <a href="#">f</a><a href="#">ig</a><a href="#">𝕏</a><a href="#">pt</a>
            </div>
        </div>
        <div class="fc">
            <h4>Company</h4>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="fc">
            <h4>Support</h4>
            <ul>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="fc">
            <h4>More</h4>
            <ul>
                <li><a href="#">Destinations</a></li>
                <li><a href="#">Travel Guides</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>
        <div class="fc">
            <h4>Newsletter</h4>
            <p style="font-size:.82rem;color:rgba(255,255,255,.5);margin-bottom:13px;">Subscribe for updates</p>
            <div class="nl">
                <input type="email" id="emailIn" placeholder="Enter your email">
                <button onclick="sub()">→</button>
            </div>
        </div>
    </div>
    <div class="fb-bottom">&copy; 2025 TripVerse. All rights reserved.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Nav scroll
    const nav = document.getElementById('nav');
    window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60));

    // Dropdown
    function toggleDD() {
        const dd = document.getElementById('dropdown');
        if (dd) dd.classList.toggle('open');
    }
    document.addEventListener('click', e => {
        const dd = document.getElementById('dropdown');
        if (dd && !e.target.closest('.user-menu')) dd.classList.remove('open');
    });

    // Newsletter
    function sub() {
        const el = document.getElementById('emailIn');
        if (!el.value.includes('@')) {
            el.style.outline = '2px solid #ef4444';
            setTimeout(() => el.style.outline = '', 1500);
            return;
        }
        el.value = '';
        el.placeholder = '✓ Subscribed!';
        setTimeout(() => el.placeholder = 'Enter your email', 3000);
    }
</script>
</body>
</html>