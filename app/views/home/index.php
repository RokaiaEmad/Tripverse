<?php require __DIR__ . '/../layout/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <?php if (isset($is_logged_in) && $is_logged_in): ?>
            <div class="hero-greeting">
                <p>
                    <?= isset($greet) ? $greet : 'Hello' ?>,
                    <?= htmlspecialchars($user_name ?? 'User') ?>
                </p>
            </div>
            <h1 class="hero-title">
                <?php if (($total_trips ?? 0) > 0): ?>
                    Welcome back 👋<br>Ready for your next adventure?
                <?php else: ?>
                    First time here?<br>Your journey starts here
                <?php endif; ?>
            </h1>
            <?= (($total_trips ?? 0) > 0)
                ? "Continue building your unforgettable trips ✈️"
                : "Discover amazing places and start planning your first trip ✨"
            ?>
            </p>

            <a href="<?= (($total_trips ?? 0) > 0)
                            ? '#myTrip'   // My Trips page
                            : '/Tripverse/app/views/trip/addTrip.php' // Create trip page
                        ?>" class="btn-cta">

                <?= (($total_trips ?? 0) > 0)
                    ? "View My Trips →"
                    : "Start Planning →"
                ?>
            </a>

        <?php else: ?>
            <div class="hero-badge">✈️ &nbsp;Explore the World</div>
            <h1 class="hero-title">
                Your Next Adventure<br>Starts Right Here
            </h1>
            <p class="hero-subtitle">
                Plan trips, track budgets, and discover amazing destinations — all in one place.
            </p>
            <div style="display:flex;gap:14px;flex-wrap:wrap;justify-content:center;">
                <a href="/Tripverse/app/views/auth/register.php" class="btn-cta">Create Free Account →</a>
                <a href="/Tripverse/app/views/auth/login.php" class="btn-cta" style="background:transparent;border:1.5px solid rgba(255,255,255,.5);color:#fff;">Sign In</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- STATS BAR (logged-in with trips only) -->
<?php if (($is_logged_in ?? false) && ($total_trips ?? 0) > 0): ?>
    <div class="stats-bar">
        <div style="color:rgba(255,255,255,.55);font-size:.85rem;font-family:'Playfair Display',serif;font-style:italic;flex:1;">
            Your journey so far
        </div>
        <div class="stat-item text-center">
            <div class="stat-num"><?= $total_trips ?? 0 ?> </div>
            <div class="stat-label">Total Trips</div>
        </div>
        <div class="stat-item text-center">
            <div class="stat-num"><?= $upcoming_count ?? 0 ?></div>
            <div class="stat-label">Upcoming</div>
        </div>
    </div>
<?php endif; ?>

<!-- MAIN SECTION -->
<section class="main-section">
    <div class="section-header">
        <div class="title-row">
            <span style="font-size:1.5rem;">✈️</span>
            <?php if (($is_logged_in ?? false) && ($total_trips ?? 0) > 0): ?>
                <h2 class="sec-h">My <span class="gold">Trips</span></h2>
            <?php else: ?>
                <h2 class="sec-h">Popular <span>Destinations</span></h2>
            <?php endif; ?>
        </div>
        <?php if (($is_logged_in ?? false) && ($total_trips ?? 0) > 0): ?>
            <a href="/Tripverse/app/views/trip/addTrip.php" class="btn-cta" style="padding:10px 24px;font-size:.88rem;">+ Add Trip</a>
        <?php else: ?>
            <div class="carousel-btns">
                <button class="cbtn" id="prev">&#8592;</button>
                <button class="cbtn on" id="next">&#8594;</button>
            </div>
        <?php endif; ?>
    </div>

    <?php if (($is_logged_in ?? false) && ($total_trips ?? 0) > 0): ?>
        <!-- MY TRIPS GRID -->
        <div class="trips-grid" id="myTrip">
            <div class="row g-4">
                <?php
                $default_imgs = [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80',
                    'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&q=80',
                    'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&q=80',
                ];

                $today = date('Y-m-d');
                ?>

                <?php foreach (($trips ?? []) as $i => $trip):

                    $img = !empty($trip['image'])
                        ? $trip['image']
                        : $default_imgs[$i % count($default_imgs)];

                    $is_upcoming = $trip['start_date'] > $today;

                    $badge_cls = $is_upcoming ? 'upcoming' : 'done';
                    $badge_txt = $is_upcoming ? 'Upcoming' : 'Completed';

                    $days = (new DateTime($trip['start_date']))
                        ->diff(new DateTime($trip['end_date']))->days;
                ?>

                    <div class="col-12 col-sm-6 col-lg-4"
                        style="opacity:0;transform:translateY(24px);
             transition:opacity .45s <?= $i * .08 ?>s,
                        transform .45s <?= $i * .08 ?>s">

                        <a
href="/Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=<?= $trip['itinerary_id'] ?>"
class="text-decoration-none text-light">

                        <div class="trip-card">

                            <img src="<?= $img ?>"
                                alt="<?= htmlspecialchars($trip['trip_name']) ?>"
                                loading="lazy">

                            <div class="trip-card-ov"></div>

                            <div class="trip-card-info">
                                <h3><?= htmlspecialchars($trip['trip_name']) ?></h3>

                                <p>📍 <?= htmlspecialchars($trip['destination']) ?></p>

                                <p>
                                    📅 <?= date('M d, Y', strtotime($trip['start_date'])) ?>
                                    · <?= $days ?> days
                                </p>

                                <div class="trip-meta">

                                    <?php if (!empty($trip['budget'])): ?>
                                        <span class="trip-date">
                                            💰 $<?= number_format($trip['budget'], 0) ?>
                                        </span>
                                    <?php else: ?>
                                        <span></span>
                                    <?php endif; ?>

                                    <span class="trip-badge <?= $badge_cls ?>">
                                        <?= $badge_txt ?>
                                    </span>

                                </div>
                            </div>      
                        </div>
                                    </a>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        </div>

    <?php else: ?>
        <!-- POPULAR DESTINATIONS CAROUSEL -->
        <div class="carousel-outer">
            <div class="cards-track" id="track">
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=500&q=82" alt="Maldives" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Maldives</h3>
                        <p>Paradise on Earth</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=500&q=82" alt="Santorini" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Santorini</h3>
                        <p>Greece</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&q=82" alt="Switzerland" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Switzerland</h3>
                        <p>The Alps</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500&q=82" alt="Bali" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Bali</h3>
                        <p>Indonesia</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=500&q=82" alt="Paris" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Paris</h3>
                        <p>France</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=500&q=82" alt="Rome" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Rome</h3>
                        <p>Italy</p>
                    </div>
                </div>
                <div class="dest-card"><img src="https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=500&q=82" alt="Tokyo" loading="lazy">
                    <div class="card-ov"></div>
                    <div class="card-info">
                        <h3>Tokyo</h3>
                        <p>Japan</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- WHY CHOOSE US -->
<section class="why-section">
    <div class="section-header">
        <div class="title-row">
            <span style="font-size:1.5rem;">✨</span>
            <h2 class="sec-h">Why <span>Choose Us</span></h2>
        </div>
    </div>
    <p class="why-subtitle">Everything you need to plan the perfect trip — in one place.</p>
    <div class="why-bento">
        <div class="why-big-card">
            <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=85" alt="Planning" loading="lazy">
            <div class="why-big-ov"></div>
            <div class="why-big-body">
                <div class="why-tag">All-in-One</div>
                <h3>Your Complete Travel Companion</h3>
                <p>Plan trips, track budgets, discover destinations — everything seamlessly in one beautiful app.</p>
            </div>
        </div>
        <div class="why-small-grid">
            <div class="why-small-card">
                <div class="why-small-icon">🗺️</div>
                <h4>Smart Planning</h4>
                <p>Intuitive itinerary builder that saves you hours of prep time.</p>
            </div>
            <div class="why-small-card">
                <div class="why-small-icon">💰</div>
                <h4>Budget Control</h4>
                <p>Set limits, log expenses, and never overspend on a trip again.</p>
            </div>
            <div class="why-small-card">
                <div class="why-small-icon">🔒</div>
                <h4>Safe & Private</h4>
                <p>Your data is yours — fully encrypted and never shared.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section">
    <div class="section-header">
        <div class="title-row">
            <span style="font-size:1.5rem;">🚀</span>
            <h2 class="sec-h">How It <span>Works</span></h2>
        </div>
    </div>
    <p class="why-subtitle">From idea to adventure in two simple steps.</p>
    <div class="how-steps">
        <div class="how-step">
            <div class="how-step-img">
                <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&q=82" alt="Choose destination" loading="lazy">
                <div class="how-step-num">01</div>
            </div>
            <div class="how-step-text">
                <div class="step-line"></div>
                <h3>Choose Your Destination</h3>
                <p>Pick where you want to go, set your travel dates, and invite your friends to join the adventure.</p>
                <a href="<?= $is_logged_in
                                ? '/Tripverse/app/views/trip/addTrip.php'
                                : '/Tripverse/app/views/auth/register.php' ?>"
                    class="step-link">
                    Get started →
                </a>
            </div>
        </div>
        <div class="how-step how-step-rev">
            <div class="how-step-img">
                <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&q=82" alt="Set budget" loading="lazy">
                <div class="how-step-num">02</div>
            </div>
            <div class="how-step-text">
                <div class="step-line"></div>
                <h3>Set Your Budget</h3>
                <p>Define your total budget, split it by category, and track every expense so you always know where you stand.</p>
                <a href="<?= $is_logged_in
                                ? '/Tripverse/app/views/trip/addTrip.php'
                                : '/Tripverse/app/views/auth/register.php' ?>"
                    class="step-link">
                    Get started →
                </a>
            </div>
        </div>
        <div class="how-step">
            <div class="how-step-img">
                <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&q=82" alt="Plan activities" loading="lazy">
                <div class="how-step-num">03</div>
            </div>
            <div class="how-step-text">
                <div class="step-line"></div>
                <h3>Plan Your Activities</h3>
                <p>Add day-by-day activities, book accommodations, and organize everything neatly — all in one place.</p>
                <a href="<?= $is_logged_in
                                ? '/Tripverse/app/views/trip/addTrip.php'
                                : '/Tripverse/app/views/auth/register.php' ?>"
                    class="step-link">
                    Get started →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Carousel + Cards animation -->
<script>
    // Carousel
    const track = document.getElementById('track');
    const prevB = document.getElementById('prev');
    const nextB = document.getElementById('next');
    if (track && nextB) {
        let pos = 0;
        const STEP = 240;

        function maxPos() {
            return track.scrollWidth - track.parentElement.offsetWidth;
        }
        nextB.addEventListener('click', () => {
            pos = Math.min(pos + STEP, maxPos());
            track.style.transform = `translateX(-${pos}px)`;
            prevB.classList.toggle('on', pos > 0);
            nextB.classList.toggle('on', pos < maxPos());
        });
        prevB.addEventListener('click', () => {
            pos = Math.max(pos - STEP, 0);
            track.style.transform = `translateX(-${pos}px)`;
            prevB.classList.toggle('on', pos > 0);
            nextB.classList.toggle('on', pos < maxPos());
        });
        document.querySelectorAll('.dest-card').forEach((c, i) => {
            c.style.opacity = '0';
            c.style.transform = 'translateY(20px)';
            c.style.transition = `opacity .48s ${i * .07}s, transform .48s ${i * .07}s`;
            new IntersectionObserver(([e]) => {
                if (e.isIntersecting) {
                    c.style.opacity = '1';
                    c.style.transform = 'translateY(0)';
                }
            }, {
                threshold: 0.1
            }).observe(c);
        });
    }

    // Trip cards animation
    document.querySelectorAll('.col-12').forEach(c => {
        new IntersectionObserver(([e]) => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
            }
        }, {
            threshold: 0.1
        }).observe(c);
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
