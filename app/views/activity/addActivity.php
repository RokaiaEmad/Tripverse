<?php

session_start();

$version_id = $_GET['version_id'] ?? '';
$itinerary_id = $_GET['itinerary_id'] ?? '';
$selected_date = $_GET['date'] ?? date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Activity</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-deep:     #060d1f;
      --bg-card:     #0d1935;
      --bg-input:    #091228;
      --accent:      #00e5a0;
      --accent-dim:  #00b87a;
      --accent-glow: rgba(0, 229, 160, 0.18);
      --accent-soft: rgba(0, 229, 160, 0.08);
      --text-primary: #e8f0ff;
      --text-muted:   #5d7aaa;
      --border:       rgba(0, 229, 160, 0.15);
      --border-hover: rgba(0, 229, 160, 0.45);
      --error-bg:     rgba(255, 80, 100, 0.1);
      --error-border: rgba(255, 80, 100, 0.4);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: var(--bg-deep);
      color: var(--text-primary);
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 16px;
      background-image:
        radial-gradient(ellipse 60% 40% at 20% 10%, rgba(0,229,160,0.06) 0%, transparent 70%),
        radial-gradient(ellipse 40% 50% at 80% 90%, rgba(0,100,255,0.05) 0%, transparent 70%);
    }
    /* ── Transport Picker ── */
.transport-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.transport-option {
  position: relative;
  cursor: pointer;
}

.transport-option input[type="radio"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.transport-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 18px 12px;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 14px;
  transition: border-color .2s, background .2s, box-shadow .2s, transform .15s;
  user-select: none;
}

.transport-card:hover {
  border-color: var(--border-hover);
  background: #0a1530;
  transform: translateY(-1px);
}

.transport-option input:checked ~ .transport-card {
  border-color: var(--accent);
  background: var(--accent-soft);
  box-shadow: 0 0 0 3px var(--accent-glow);
}

.transport-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(93, 122, 170, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .2s;
}

.transport-option input:checked ~ .transport-card .transport-icon {
  background: rgba(0, 229, 160, 0.15);
}

.transport-icon svg {
  width: 22px;
  height: 22px;
  color: var(--text-muted);
  transition: color .2s;
}

.transport-option input:checked ~ .transport-card .transport-icon svg {
  color: var(--accent);
}

.transport-label {
  font-size: 0.8rem;
  font-weight: 500;
  color: var(--text-muted);
  letter-spacing: 0.04em;
  transition: color .2s;
}

.transport-option input:checked ~ .transport-card .transport-label {
  color: var(--accent);
}

.transport-check {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transform: scale(0.6);
  transition: opacity .2s, transform .2s;
}

.transport-option input:checked ~ .transport-card .transport-check {
  opacity: 1;
  transform: scale(1);
}

.transport-check svg { width: 10px; height: 10px; color: #060d1f; }
    /* ── Card ── */
    .form-card {
      width: 100%;
      max-width: 560px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 44px 40px 36px;
      box-shadow:
        0 0 0 1px rgba(0,229,160,0.04),
        0 24px 60px rgba(0,0,0,0.5),
        0 0 80px var(--accent-glow);
      animation: fadeUp .45s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0);    }
    }

    /* ── Header ── */
    .form-header {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 36px;
    }

    .header-icon {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      background: var(--accent-soft);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .header-icon svg { width: 22px; height: 22px; color: var(--accent); }

    .form-title {
      font-family: 'Syne', sans-serif;
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--text-primary);
      letter-spacing: -0.02em;
      line-height: 1;
    }

    .form-subtitle {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-top: 3px;
      letter-spacing: 0.01em;
    }

    /* ── Error ── */
    .alert-error {
      background: var(--error-bg);
      border: 1px solid var(--error-border);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 0.85rem;
      color: #ff8090;
      margin-bottom: 24px;
    }

    /* ── Field ── */
    .field { margin-bottom: 22px; }

    .field-label {
      display: block;
      font-size: 0.75rem;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 8px;
    }

    .field-input {
      width: 100%;
      background: var(--bg-input);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 16px;
      color: var(--text-primary);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      outline: none;
      transition: border-color .2s, box-shadow .2s, background .2s;
      appearance: none;
    }

    .field-input::placeholder { color: var(--text-muted); }

    .field-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px var(--accent-glow);
      background: #0a1530;
    }

    /* ── Time Row ── */
    .time-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 22px;
    }

    /* ── Hour Picker ── */
    .hour-picker-wrap {
      position: relative;
    }

    .hour-picker-wrap select {
      cursor: pointer;
      padding-right: 40px;
      background-image: none;
    }

    .hour-picker-wrap::after {
      content: '';
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 0;
      height: 0;
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 6px solid var(--text-muted);
      pointer-events: none;
      transition: border-top-color .2s;
    }

    .hour-picker-wrap:hover::after { border-top-color: var(--accent); }

    select.field-input option {
      background: #0d1935;
      color: var(--text-primary);
    }

    /* ── Date badge ── */
    .date-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: var(--accent-soft);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.8rem;
      color: var(--accent);
      font-weight: 500;
      margin-bottom: 22px;
    }

    .date-badge svg { width: 14px; height: 14px; flex-shrink: 0; }

    /* ── Divider ── */
    .divider {
      height: 1px;
      background: var(--border);
      margin: 28px 0;
    }

    /* ── Submit ── */
    .btn-create {
      width: 100%;
      padding: 14px;
      background: var(--accent);
      color: #060d1f;
      border: none;
      border-radius: 12px;
      font-family: 'Syne', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      cursor: pointer;
      transition: background .2s, transform .15s, box-shadow .2s;
      box-shadow: 0 4px 20px rgba(0, 229, 160, 0.25);
      position: relative;
      overflow: hidden;
    }

    .btn-create::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
    }

    .btn-create:hover {
      background: var(--accent-dim);
      transform: translateY(-1px);
      box-shadow: 0 8px 28px rgba(0, 229, 160, 0.35);
    }

    .btn-create:active { transform: translateY(0); }

    /* ── Responsive ── */
    @media (max-width: 480px) {
      .form-card { padding: 32px 24px 28px; }
      .time-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<div class="form-card">

  <!-- Header -->
  <div class="form-header">
    <div class="header-icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
      </svg>
    </div>
    <div>
      <div class="form-title">Add Activity</div>
      <div class="form-subtitle">Schedule a new event for your itinerary</div>
    </div>
  </div>

  <?php if (isset($_SESSION['activity_error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['activity_error']); ?></div>
    <?php unset($_SESSION['activity_error']); ?>
  <?php endif; ?>

  <!-- Date Badge (locked, read-only) -->
  <div class="date-badge">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    <?= htmlspecialchars(date('l, F j, Y', strtotime($selected_date))) ?>
  </div>

  <form method="POST" action="../../controllers/ActivityController.php?action=create" id="activityForm">

    <input type="hidden" name="version_id"    value="<?= htmlspecialchars($version_id) ?>">
    <input type="hidden" name="itinerary_id"  value="<?= htmlspecialchars($itinerary_id) ?>">
    <input type="hidden" name="selected_date" value="<?= htmlspecialchars($selected_date) ?>">

    <!-- Hidden datetime fields sent to server -->
    <input type="hidden" name="start_time" id="start_time_hidden">
    <input type="hidden" name="end_time"   id="end_time_hidden">

    <!-- Title -->
    <div class="field">
      <label class="field-label">Title</label>
      <input type="text" name="title" class="field-input" placeholder="e.g. City tour, Museum visit…" required>
    </div>

    <!-- Location -->
    <div class="field">
      <label class="field-label">Location</label>
      <input type="text" name="location" class="field-input" placeholder="e.g. Cairo Museum, Giza…" required>
    </div>

    <!-- Transport Mode — icon card picker -->
<div class="field">
  <label class="field-label">Transport Mode</label>

  <!-- Real select (hidden, synced by JS) -->
  <select name="transport_mode" id="transport_mode" style="display:none">
    <option value="walking">Walking</option>
    <option value="car">Car</option>
    <option value="train">Train</option>
  </select>

  <div class="transport-grid">

    <label class="transport-option">
      <input type="radio" name="transport_ui" value="walking" checked>
      <div class="transport-card">
        <div class="transport-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="5" r="1.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.5l1.5 4.5-2 4m5.5-9l.5 4-2 1.5m-1-6L10.5 6"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 18l1-3.5M15 18l-1-3.5"/>
          </svg>
        </div>
        <span class="transport-label">Walking</span>
        <div class="transport-check">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </label>

    <label class="transport-option">
      <input type="radio" name="transport_ui" value="car">
      <div class="transport-card">
        <div class="transport-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 17H3v-5l2-5h14l2 5v5h-2M5 17a2 2 0 004 0M15 17a2 2 0 004 0"/>
            <path stroke-linecap="round" d="M3 12h18"/>
          </svg>
        </div>
        <span class="transport-label">Car</span>
        <div class="transport-check">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </label>

    <label class="transport-option">
      <input type="radio" name="transport_ui" value="train">
      <div class="transport-card">
        <div class="transport-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="4" y="3" width="16" height="14" rx="4"/>
            <path stroke-linecap="round" d="M4 10h16"/>
            <circle cx="8.5" cy="15.5" r="1"/>
            <circle cx="15.5" cy="15.5" r="1"/>
            <path stroke-linecap="round" d="M7 21l2-4M17 21l-2-4"/>
          </svg>
        </div>
        <span class="transport-label">Train</span>
        <div class="transport-check">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </label>

  </div>
</div>

    <div class="divider"></div>

    <!-- Time pickers -->
    <div class="time-row">
      <div class="field" style="margin-bottom:0">
        <label class="field-label">Start Hour</label>
        <div class="hour-picker-wrap">
          <select id="start_hour" class="field-input" required>
            <?php for ($h = 0; $h < 24; $h++): 
              $val  = str_pad($h, 2, '0', STR_PAD_LEFT);
              $disp = ($h === 0 ? '12 AM' : ($h < 12 ? "$h AM" : ($h === 12 ? '12 PM' : ($h-12).' PM')));
              $sel  = ($h === 9) ? 'selected' : '';
            ?>
              <option value="<?= $val ?>" <?= $sel ?>><?= $disp ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div class="field" style="margin-bottom:0">
        <label class="field-label">End Hour</label>
        <div class="hour-picker-wrap">
          <select id="end_hour" class="field-input" required>
            <?php for ($h = 0; $h < 24; $h++):
              $val  = str_pad($h, 2, '0', STR_PAD_LEFT);
              $disp = ($h === 0 ? '12 AM' : ($h < 12 ? "$h AM" : ($h === 12 ? '12 PM' : ($h-12).' PM')));
              $sel  = ($h === 10) ? 'selected' : '';
            ?>
              <option value="<?= $val ?>" <?= $sel ?>><?= $disp ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="divider"></div>

    <button type="submit" class="btn-create">Create Activity</button>

  </form>
</div>

<script>
  const date = "<?= htmlspecialchars($selected_date) ?>";

  function buildDatetime(hour) {
    return `${date}T${hour}:00`;
  }

  function syncHiddenFields() {
    const sh = document.getElementById('start_hour').value;
    const eh = document.getElementById('end_hour').value;
    document.getElementById('start_time_hidden').value = buildDatetime(sh);
    document.getElementById('end_time_hidden').value   = buildDatetime(eh);
  }

  document.getElementById('start_hour').addEventListener('change', syncHiddenFields);
  document.getElementById('end_hour').addEventListener('change', syncHiddenFields);

  // Validate end > start on submit
  document.getElementById('activityForm').addEventListener('submit', function(e) {
    syncHiddenFields();
    const sh = parseInt(document.getElementById('start_hour').value);
    const eh = parseInt(document.getElementById('end_hour').value);
    if (eh <= sh) {
      e.preventDefault();
      alert('End hour must be after start hour.');
    }
  });


  document.querySelectorAll('input[name="transport_ui"]').forEach(radio => {
  radio.addEventListener('change', () => {
    document.getElementById('transport_mode').value = radio.value;
  });
});

  // Initialise on load
  syncHiddenFields();
</script>

</body>
</html>