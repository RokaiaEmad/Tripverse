<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TripVerse – Plan Your Trip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/Tripverse/app/assets/css/addTrip.css">
</head>

<body>
    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <!-- HERO -->
    <section class="plan-hero">
        <div class="plan-hero-bg"></div>
        <div class="plan-hero-content">
            <p class="plan-hero-tag">✈️ New Adventure</p>
            <h1>Plan Your <span>Perfect Trip</span></h1>
            <p class="plan-hero-sub">Fill in the details below and let TripVerse take care of the rest.</p>
        </div>
    </section>

    <!-- FORM SECTION -->
    <section class="form-section">

        <!-- STEPS INDICATOR -->
        <div class="steps-indicator">
            <div class="step-dot active" data-step="1">
                <div class="dot-circle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                        <circle cx="12" cy="9" r="2.5" />
                    </svg>
                </div>
                <span>Destination</span>
            </div>
            <div class="step-line-bar"></div>
            <div class="step-dot" data-step="2">
                <div class="dot-circle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <span>Dates</span>
            </div>
            <div class="step-line-bar"></div>
            <div class="step-dot" data-step="3">
                <div class="dot-circle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <span>Budget</span>
            </div>
            <div class="step-line-bar"></div>
            <div class="step-dot" data-step="4">
                <div class="dot-circle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <span>Summary</span>
            </div>
        </div>

        <!-- FORM -->
        <form
action="../../controllers/TripController.php"
method="POST"
enctype="multipart/form-data"
id="tripForm">

    <input
    type="hidden"
    name="action"
    value="create_trip">

            <!-- STEP 1: Destination -->
            <div class="form-step active" id="step1">
                <div class="step-header">
                    <h2>Where are you headed? 🌍</h2>
                    <p>Enter your destination and give your trip a name.</p>
                </div>
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Trip Name</label>
                        <input type="text" name="trip_name" placeholder="e.g. Summer Adventure 2025" required />
                    </div>
                    <div class="form-group full">
                        <label>Destination</label>
                        <input type="text" name="destination" id="destinationInput" placeholder="e.g. Paris, France" autocomplete="off" required />
                        <div class="suggestions-box" id="suggestionsBox"></div>
                    </div>
                    <div class="form-group full">
                        <label>Trip Image <span class="optional">(optional)</span></label>
                        <div class="file-upload" id="fileUpload">
                            <input type="file" name="image" id="imageInput" accept="image/*" />
                            <div class="file-upload-inner" id="uploadInner">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                                <p>Click to upload a cover photo</p>
                                <span>JPG, PNG up to 5MB</span>
                            </div>
                            <img id="imagePreview" src="" alt="preview" style="display:none;" />
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <span></span>
                    <button type="button" class="btn-next" onclick="nextStep(1)">Next — Dates <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></button>
                </div>
            </div>

            <!-- STEP 2: Dates -->
            <div class="form-step" id="step2">
                <div class="step-header">
                    <h2>When are you going? 📅</h2>
                    <p>Set your travel dates so we can plan everything around them.</p>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="startDate" required />
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="endDate" required />
                    </div>
                    <div class="form-group full">
                        <div class="date-summary" id="dateSummary" style="display:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span id="dateSummaryText"></span>
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-back" onclick="prevStep(2)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg> Back</button>
                    <button type="button" class="btn-next" onclick="nextStep(2)">Next — Budget <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></button>
                </div>
            </div>

            <!-- STEP 3: Budget -->
            <div class="form-step" id="step3">
                <div class="step-header">
                    <h2>What's your budget? 💰</h2>
                    <p>Set a total budget and we'll help you stay on track.</p>
                </div>
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Total Budget (USD)</label>
                        <div class="budget-input-wrap">
                            <span class="currency-sign">$</span>
                            <input type="number" name="budget" id="budgetInput" placeholder="0.00" min="0" step="0.01" required />
                        </div>
                    </div>
                    <div class="form-group full">
                        <div class="budget-presets">
                            <button type="button" class="preset-btn" onclick="setBudget(500)">$500</button>
                            <button type="button" class="preset-btn" onclick="setBudget(1000)">$1,000</button>
                            <button type="button" class="preset-btn" onclick="setBudget(2500)">$2,500</button>
                            <button type="button" class="preset-btn" onclick="setBudget(5000)">$5,000</button>
                            <button type="button" class="preset-btn" onclick="setBudget(10000)">$10,000+</button>
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-back" onclick="prevStep(3)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg> Back</button>
                    <button type="button" class="btn-next" onclick="nextStep(3)">Next — Summary <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></button>
                </div>
            </div>


            <!-- STEP 4: Summary -->
            <div class="form-step" id="step4">
                <div class="step-header">
                    <h2>Trip Summary 🗺️</h2>
                    <p>Review your trip details before creating.</p>
                </div>
                <div class="form-grid">
                    <div class="form-group full">
                        <div class="summary-card">
                            <h4>Trip Summary</h4>
                            <div class="summary-row">
                                <span>Trip Name</span>
                                <strong id="sum-name">—</strong>
                            </div>
                            <div class="summary-row">
                                <span>Destination</span>
                                <strong id="sum-dest">—</strong>
                            </div>
                            <div class="summary-row">
                                <span>Dates</span>
                                <strong id="sum-dates">—</strong>
                            </div>
                            <div class="summary-row">
                                <span>Budget</span>
                                <strong id="sum-budget">—</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-back" onclick="prevStep(4)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg> Back
                    </button>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Create My Trip
                    </button>
                </div>
            </div>
            </div>


        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Nav scroll
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60));

        // Step navigation
        let currentStep = 1;
        const totalSteps = 4;

        async function nextStep(from) {
            // if (!validateStep(from)) return;
            // goToStep(from + 1);
            if (!(await validateStep(from))) {
                return;
            }

            goToStep(from + 1);

        }

        function prevStep(from) {
            goToStep(from - 1);
        }

        function goToStep(n) {
            document.getElementById('step' + currentStep).classList.remove('active');
            document.querySelectorAll('.step-dot').forEach((d, i) => {
                d.classList.toggle('active', i < n);
                d.classList.toggle('done', i < n - 1);
            });
            document.querySelectorAll('.step-line-bar').forEach((l, i) => {
                l.classList.toggle('active', i < n - 1);
            });
            currentStep = n;
            document.getElementById('step' + currentStep).classList.add('active');
            if (n === 4) updateSummary();
            window.scrollTo({
                top: document.querySelector('.form-section').offsetTop - 80,
                behavior: 'smooth'
            });
        }

        // function validateStep(step) {
        //     if (step === 1) {
        //         const name = document.querySelector('[name=trip_name]').value.trim();
        //         const dest = document.querySelector('[name=destination]').value.trim();
        //         if (!name || !dest) {
        //             showError('Please fill in trip name and destination.');
        //             return false;
        //         }
        //     }
        //     if (step === 2) {
        //         const s = document.getElementById('startDate').value;
        //         const e = document.getElementById('endDate').value;
        //         if (!s || !e) {
        //             showError('Please select both start and end dates.');
        //             return false;
        //         }
        //         if (new Date(e) < new Date(s)) {
        //             showError('End date must be after start date.');
        //             return false;
        //         }
        //     }
        //     if (step === 3) {
        //         const b = document.getElementById('budgetInput').value;
        //         if (!b || b <= 0) {
        //             showError('Please enter a valid budget.');
        //             return false;
        //         }
        //     }
        //     return true;
        // }
        async function validateStep(step) {

            if (step === 1) {

                const name =
                    document.querySelector('[name=trip_name]')
                    .value.trim();

                const dest =
                    document.querySelector('[name=destination]')
                    .value.trim();

                if (!name || !dest) {

                    showError(
                        'Please fill in trip name and destination.'
                    );

                    return false;
                }

                showError(
                    'Checking destination...'
                );

                try {

                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(dest)}`
                    );

                    const data = await response.json();
                    console.log(data);
                    const valid =
                        Array.isArray(data) &&
                        data.length > 0 &&
                        data[0].display_name
                        .toLowerCase()
                        .includes(dest.toLowerCase());

                    if (!valid) {

                        showError(
                            'Please enter a valid destination.'
                        );

                        return false;
                    }

                } catch (error) {

                    showError(
                        'Error checking destination.'
                    );

                    return false;
                }
            }

            if (step === 2) {

                const s =
                    document.getElementById('startDate').value;

                const e =
                    document.getElementById('endDate').value;

                if (!s || !e) {

                    showError(
                        'Please select both start and end dates.'
                    );

                    return false;
                }

                if (new Date(e) < new Date(s)) {

                    showError(
                        'End date must be after start date.'
                    );

                    return false;
                }
            }

            if (step === 3) {

                const b =
                    document.getElementById('budgetInput').value;

                if (!b || b <= 0) {

                    showError(
                        'Please enter a valid budget.'
                    );

                    return false;
                }
            }

            return true;
        }

        // function showError(msg) {
        //     const existing = document.querySelector('.form-error');
        //     if (existing) existing.remove();
        //     const el = document.createElement('div');
        //     el.className = 'form-error';
        //     el.textContent = msg;
        //     document.getElementById('step' + currentStep).querySelector('.step-actions').before(el);
        //     const inputs = stepEl.querySelectorAll('input, textarea, select');
        //     inputs.forEach(input => {
        //         input.addEventListener('input', () => el.remove(), {
        //             once: true
        //         });
        //     });
        //     //  setTimeout(() => el.remove(), 3000);
        // }
        function showError(msg) {
            const existing = document.querySelector('.form-error');
            if (existing) existing.remove();

            const el = document.createElement('div');
            el.className = 'form-error';
            el.textContent = msg;

            const stepEl = document.getElementById('step' + currentStep);
            stepEl.querySelector('.step-actions').before(el);

            // ✅ Remove error when user starts typing/interacting
            const inputs = stepEl.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', () => el.remove(), {
                    once: true
                });
            });

            // setTimeout(() => el.remove(), 3000);
        }

        // Destination suggestions
        const destinations = [
            'Paris, France', 'Rome, Italy', 'Tokyo, Japan', 'Bali, Indonesia',
            'Santorini, Greece', 'Maldives', 'New York, USA', 'London, UK',
            'Barcelona, Spain', 'Dubai, UAE', 'Istanbul, Turkey', 'Cairo, Egypt',
            'Sydney, Australia', 'Bangkok, Thailand', 'Amsterdam, Netherlands',
            'Prague, Czech Republic', 'Lisbon, Portugal', 'Vienna, Austria',
            'Singapore', 'Marrakech, Morocco'
        ];

        const destInput = document.getElementById('destinationInput');
        const sugBox = document.getElementById('suggestionsBox');

        destInput.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            sugBox.innerHTML = '';
            if (!val) {
                sugBox.style.display = 'none';
                return;
            }
            const matches = destinations.filter(d => d.toLowerCase().includes(val)).slice(0, 5);
            if (!matches.length) {
                sugBox.style.display = 'none';
                return;
            }
            matches.forEach(m => {
                const d = document.createElement('div');
                d.className = 'suggestion-item';
                d.textContent = m;
                d.onclick = () => {
                    destInput.value = m;
                    sugBox.style.display = 'none';
                };
                sugBox.appendChild(d);
            });
            sugBox.style.display = 'block';
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('#destinationInput') && !e.target.closest('#suggestionsBox')) {
                sugBox.style.display = 'none';
            }
        });

        // Image upload preview
        document.getElementById('imageInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const prev = document.getElementById('imagePreview');
                const inner = document.getElementById('uploadInner');
                prev.src = e.target.result;
                prev.style.display = 'block';
                inner.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        // Date summary
        function updateDateSummary() {
            const s = document.getElementById('startDate').value;
            const e = document.getElementById('endDate').value;
            if (!s || !e) return;
            const days = Math.round((new Date(e) - new Date(s)) / (1000 * 60 * 60 * 24));
            if (days < 0) return;
            const fmt = d => new Date(d).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            document.getElementById('dateSummaryText').textContent = `${fmt(s)} → ${fmt(e)} · ${days} day${days !== 1 ? 's' : ''}`;
            document.getElementById('dateSummary').style.display = 'flex';
        }
        document.getElementById('startDate').addEventListener('change', updateDateSummary);
        document.getElementById('endDate').addEventListener('change', updateDateSummary);

        // Budget presets
        function setBudget(val) {
            document.getElementById('budgetInput').value = val;
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Summary
        function updateSummary() {
            const fmt = d => d ? new Date(d).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) : '—';
            document.getElementById('sum-name').textContent = document.querySelector('[name=trip_name]').value || '—';
            document.getElementById('sum-dest').textContent = document.querySelector('[name=destination]').value || '—';
            const s = document.getElementById('startDate').value;
            const e = document.getElementById('endDate').value;
            document.getElementById('sum-dates').textContent = s && e ? `${fmt(s)} → ${fmt(e)}` : '—';
            const b = document.getElementById('budgetInput').value;
            document.getElementById('sum-budget').textContent = b ? `$${Number(b).toLocaleString()}` : '—';
        }
    </script>
</body>
<?php require __DIR__ . '/../layout/footer.php'; ?>

</html>