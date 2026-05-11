<?php
require_once __DIR__ . '/../../models/RSVP.php';
$activity_error   = $_SESSION['activity_error']   ?? '';
$activity_success = $_SESSION['activity_success'] ?? '';

unset($_SESSION['activity_error']);
unset($_SESSION['activity_success']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($trip['trip_name']) ?> — Itinerary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Tripverse/app/assets/css/itinerary/index.css">
</head>
<body>
<div class="container py-5">

  <?php if ($activity_error): ?>
    <div class="alert alert-danger mt-4"><?= $activity_error ?></div>
  <?php endif; ?>

  <?php if ($activity_success): ?>
    <div class="alert alert-success mt-4"><?= $activity_success ?></div>
  <?php endif; ?>

  <!-- ══ HERO ══ -->
  <div class="hero fade-up">
    <img
      src="<?= $trip['image'] ?: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=1400' ?>"
      alt="<?= htmlspecialchars($trip['trip_name']) ?>">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1><?= htmlspecialchars($trip['trip_name']) ?></h1>
      <div class="hero-dest">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
        </svg>
        <?= htmlspecialchars($trip['destination']) ?>
      </div>
      <div class="hero-dates">
        <?= date('d M Y', strtotime($trip['start_date'])) ?> — <?= date('d M Y', strtotime($trip['end_date'])) ?>
      </div>
    </div>
  </div>

  

  <!-- ══ TRIP BAR ══ -->
  <div class="trip-bar fade-up fade-up-1">

    <div>
      <div class="trip-bar-label">Travellers</div>
      <div class="members-list">

        <?php foreach ($members as $m): ?>
          <div class="member-wrap">
            <div class="member-avatar">
              <?= strtoupper(substr($m['name'], 0, 1)) ?>
            </div>
            <div class="member-card">
              <div class="member-card-avatar">
                <?= strtoupper(substr($m['name'], 0, 1)) ?>
              </div>
              <div class="member-card-name">
                <?= htmlspecialchars($m['name']) ?>
              </div>
              <?php if ($isLeader && $m['id'] != $_SESSION['user_id']): ?>
                <form
                  method="POST"
                  action="/Tripverse/app/controllers/TripMemberController.php?action=remove"
                  onsubmit="return confirm('Remove <?= htmlspecialchars($m['name']) ?> from this trip?')">
                  <input type="hidden" name="trip_id"   value="<?= $trip['id'] ?>">
                  <input type="hidden" name="member_id" value="<?= $m['id'] ?>">
                  <button type="submit" class="member-remove-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" width="13" height="13">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                    </svg>
                    Remove
                  </button>
                </form>
              <?php else: ?>
                <span class="member-card-role">
                  <?= ($m['id'] == $_SESSION['user_id']) ? 'You' : 'Member' ?>
                </span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if ($isLeader): ?>
          <form method="POST" action="/Tripverse/app/controllers/TripMemberController.php?action=invite">
            <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
            <button type="submit" class="btn-invite">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
              Invite
            </button>
          </form>
        <?php endif; ?>

      </div>
    </div>

    <div class="budget-chip">
      <span class="currency">Budget</span>
      <span class="amount">$<?= number_format($trip['budget']) ?></span>
    </div>

  </div>

 <!-- ══ VERSION HISTORY TRIGGER BUTTON ══ -->
  <!-- Place this button wherever you want it on the page (e.g. in trip-bar or panel-header) -->
  <div class="version-trigger-wrap">
    <button class="btn-version-history" onclick="openVersionModal()">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="2" width="15" height="15">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      Version History
      <span class="version-count-badge"><?= count($versions) ?></span>
    </button>
  </div>

  <!-- ══ VERSION HISTORY MODAL ══ -->
  <div class="vmodal-backdrop" id="versionModal" onclick="closeVersionModal(event)">
    <div class="vmodal">

      <!-- Modal header -->
      <div class="vmodal-header">
        <div class="vmodal-title">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" stroke-width="2" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Version History
        </div>
        <button class="vmodal-close" onclick="closeVersionModal()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" stroke-width="2.5" width="16" height="16">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Table -->
      <div class="vmodal-body">
        <table class="version-table">
          <thead>
            <tr>
              <th>Version</th>
              <th>Created by</th>
              <th>Change note</th>
              <th>Status</th>
              <?php if ($isLeader): ?><th>Action</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($versions as $v): ?>
              <tr class="<?= $v['is_active'] ? 'row-active' : '' ?>">

                <td>
                  <span class="vtable-version">v<?= $v['version_number'] ?></span>
                </td>

                <td>
                  <div class="vtable-author">
                    <div class="vtable-avatar">
                      <?= strtoupper(substr($v['creator_name'], 0, 1)) ?>
                    </div>
                    <?= htmlspecialchars($v['creator_name']) ?>
                  </div>
                </td>

                <td>
                  <span class="vtable-note">
                    <?= !empty($v['change_note']) ? htmlspecialchars($v['change_note']) : '—' ?>
                  </span>
                </td>

                <td>
                  <?php if ($v['is_active']): ?>
                    <span class="vtable-badge vtable-badge-active">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor" stroke-width="3" width="9" height="9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                      Active
                    </span>
                  <?php else: ?>
                    <span class="vtable-badge vtable-badge-inactive">Inactive</span>
                  <?php endif; ?>
                </td>

                <?php if ($isLeader): ?>
                  <td>
                    <?php if (!$v['is_active']): ?>
                      <form method="POST" action="/Tripverse/app/controllers/VersionController.php?action=rollback">
                        <input type="hidden" name="itinerary_id" value="<?= $itinerary_id ?>">
                        <input type="hidden" name="version_id"   value="<?= $v['version_id'] ?>">
                        <button type="submit" class="btn-rollback-sm"
                          onclick="return confirm('Roll back to v<?= $v['version_number'] ?>?')">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                               stroke="currentColor" stroke-width="2" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                          </svg>
                          Rollback
                        </button>
                      </form>
                    <?php else: ?>
                      <span class="vtable-current">Current</span>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <script>
  function openVersionModal() {
    document.getElementById('versionModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeVersionModal(e) {
    if (!e || e.target === document.getElementById('versionModal')) {
      document.getElementById('versionModal').classList.remove('open');
      document.body.style.overflow = '';
    }
  }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeVersionModal();
  });
  </script>

  <?php
// Pre-calculate visible activity counts per date for the current user
$responseModel = new RSVP();
$visibleCountByDate = [];

foreach ($activitiesByDay as $date => $acts) {
    $count = 0;
    foreach ($acts as $act) {
        if ($isLeader) {
            $count++;
        } else {
            $resp = $responseModel->getUserResponse($act['activity_id'], $_SESSION['user_id']);
            if ($resp !== 'not_going') $count++;
        }
    }
    $visibleCountByDate[$date] = $count;
}
?>

  <!-- ══ DAYS NAV ══ -->
  <div class="days-nav-wrap fade-up fade-up-2">
    <div class="days-nav" id="daysNav">
      <?php foreach ($tripDays as $i => $day):
        $date    = $day['date'];
       $hasActs = ($visibleCountByDate[$date] ?? 0) > 0;
      ?>
        <div
          class="day-tab <?= $i === 0 ? 'active' : '' ?>"
          data-day="day-<?= $i ?>"
          onclick="selectDay(this, 'day-<?= $i ?>')"
        >
          <span class="day-num">Day <?= $day['day_number'] ?></span>
          <span class="day-name"><?= date('D', strtotime($date)) ?></span>
          <span class="day-date"><?= date('d M', strtotime($date)) ?></span>
          <?php if ($hasActs): ?>
            <span class="act-dot"></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ══ DAY PANELS ══ -->
  <div id="dayPanels" class="fade-up fade-up-3">
    <?php foreach ($tripDays as $i => $day):
      $date       = $day['date'];
      $activities = $activitiesByDay[$date] ?? [];
    ?>
      <div class="day-panel <?= $i === 0 ? 'active' : '' ?>" id="day-<?= $i ?>">

        <div class="panel-header">
          <div>
            <div class="panel-day-label">Day <?= $day['day_number'] ?></div>
            <div class="panel-day-date"><?= date('l, d M Y', strtotime($date)) ?></div>
          </div>
          <a
            href="/Tripverse/app/views/activity/addActivity.php?version_id=<?= $version['version_id'] ?>&itinerary_id=<?= $itinerary_id ?>&date=<?= $date ?>"
            class="btn-add-activity"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Activity
          </a>
        </div>

        <?php if (($visibleCountByDate[$date] ?? 0) === 0): ?>
          <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p>No activities planned for this day yet.</p>
          </div>
        <?php endif; ?>

        <?php
$responseModel = new RSVP();

$previousVisible = null;

foreach ($activities as $activity):

    // Leader is always going
    if ($isLeader) {

        $userResponse = 'going';

    } else {

        $userResponse = $responseModel->getUserResponse(
            $activity['activity_id'],
            $_SESSION['user_id']
        );

        // Hide activity completely
        if ($userResponse === 'not_going') {
            continue;
        }
    }

    // Dynamic travel line between visible activities only
    if (
    $previousVisible &&
    strtolower(trim($previousVisible['location'])) !==
    strtolower(trim($activity['location']))
):

    $travelMinutes = 30;

    if ($activity['transport_mode'] === 'walking') {
        $travelMinutes = 20;
    }

    if ($activity['transport_mode'] === 'train') {
        $travelMinutes = 45;
    }

    if ($activity['transport_mode'] === 'car') {
        $travelMinutes = 30;
    }
?>

    <div class="travel-line">

    🚗 Travel:
    <?= htmlspecialchars($previousVisible['location']) ?>
    →
    <?= htmlspecialchars($activity['location']) ?>

    •

    <?= $travelMinutes ?> mins
    by
    <?= htmlspecialchars($activity['transport_mode']) ?>

</div>

<?php
    endif;

    // Card color class
    $cardClass = '';

    if ($activity['status'] === 'confirmed') {
        $cardClass =
            ($userResponse === 'going')
            ? 'card-going'
            : 'card-pending';
    }
?>

<div class="activity-card <?= $cardClass ?>">

    <img
        class="activity-img"
        src="https://images.unsplash.com/photo-1569949381669-ecf31ae8e613?q=80&w=600"
        alt="<?= htmlspecialchars($activity['title']) ?>">

    <div class="activity-body">

        <!-- RSVP status -->
        <?php if ($activity['status'] === 'confirmed'): ?>

            <?php if ($userResponse === 'going'): ?>

                <div class="rsvp-indicator rsvp-going">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5"
                         width="11"
                         height="11">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                    <?= $isLeader
                        ? "You're going (Leader)"
                        : "You're going" ?>
                </div>

            <?php else: ?>

                <div class="rsvp-indicator rsvp-pending">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5"
                         width="11"
                         height="11">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01"/>
                    </svg>

                    No response yet
                </div>

            <?php endif; ?>

        <?php endif; ?>

        <h3 class="activity-title">
            <?= htmlspecialchars($activity['title']) ?>
        </h3>

        <div class="activity-meta">
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor"
                 viewBox="0 0 24 24">

                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
            </svg>

            <?= htmlspecialchars($activity['location']) ?>
        </div>

        <div class="activity-time">
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
            </svg>

            <?= date('h:i A', strtotime($activity['start_time'])) ?>
            &nbsp;→&nbsp;
            <?= date('h:i A', strtotime($activity['end_time'])) ?>
        </div>

        <div class="activity-creator">
            Added by <?= htmlspecialchars($activity['creator_name']) ?>
        </div>

        <div class="mt-2">

            <?php if ($activity['status'] === 'draft'): ?>

                <span class="badge-status badge-draft">
                    Draft
                </span>

            <?php else: ?>

                <span class="badge-status badge-confirmed">
                    Confirmed
                </span>

            <?php endif; ?>

        </div>

    </div>

    <!-- Actions -->
    <div class="activity-actions">

        <?php if ($activity['status'] === 'draft' && $isLeader): ?>

            <form method="POST"
                  action="/Tripverse/app/controllers/ActivityController.php?action=confirm">

                <input type="hidden"
                       name="activity_id"
                       value="<?= $activity['activity_id'] ?>">

                <button type="submit"
                        class="btn-action btn-confirm">
                    Confirm
                </button>
            </form>

        <?php elseif ($activity['status'] === 'confirmed' && $isLeader): ?>

            <span class="leader-going-badge">
                Always Going
            </span>

        <?php elseif ($activity['status'] === 'confirmed' && !$isLeader): ?>

            <form method="POST"
                  action="/Tripverse/app/controllers/RSVPController.php?action=respond">

                <input type="hidden"
                       name="activity_id"
                       value="<?= $activity['activity_id'] ?>">

                <?php if ($userResponse === 'going'): ?>

                    <button type="button"
                            class="btn-action btn-going btn-going-active">

                        ✓ Going
                    </button>

                    <button
                        name="status"
                        value="not_going"
                        type="submit"
                        class="btn-action btn-notgoing"
                        style="margin-top:8px">

                        Not Going
                    </button>

                <?php else: ?>

                    <button
                        name="status"
                        value="going"
                        type="submit"
                        class="btn-action btn-going">

                        ✓ Going
                    </button>

                    <button
                        name="status"
                        value="not_going"
                        type="submit"
                        class="btn-action btn-notgoing"
                        style="margin-top:8px">

                        Not Going
                    </button>

                <?php endif; ?>

            </form>

        <?php endif; ?>

    </div>

</div>

<?php
    $previousVisible = $activity;
endforeach;
?>

      </div><!-- /day-panel -->

<?php endforeach; ?>

  </div>

</div><!-- /container --><

<script>
function selectDay(tab, panelId) {
  document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.day-panel').forEach(p => p.classList.remove('active'));
  tab.classList.add('active');
  const panel = document.getElementById(panelId);
  if (panel) {
    panel.classList.add('active');
    tab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }
}
</script>

</body>
</html>