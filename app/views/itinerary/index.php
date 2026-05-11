<?php require __DIR__ . '/../layout/header.php'; ?>

<?php

/** @var array $trip */
/** @var array $members */
/** @var array $activitiesByDay */
/** @var array $version */
/** @var array $versions */
/** @var int $itinerary_id */
/** @var bool $isLeader */
/** @var array $tripDays */

require_once __DIR__ . '/../../models/RSVP.php';

$activity_error   = $_SESSION['activity_error']   ?? '';
$activity_success = $_SESSION['activity_success'] ?? '';

unset($_SESSION['activity_error']);
unset($_SESSION['activity_success']);

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
  href="/Tripverse/app/assets/css/itinerary/index.css">

<?php if ($activeTab === 'itinerary'): ?>

  <script>
    let lastHtml = '';

    async function pollItinerary() {

      try {

        const response = await fetch(
          '/Tripverse/app/controllers/PollController.php?itinerary_id=<?= $itinerary_id ?>'
        );

        const data = await response.json();

        if (data.success) {

          if (data.html !== lastHtml) {

            document.getElementById('activitiesContainer').innerHTML =
              data.html;

            lastHtml = data.html;

            console.log('Updated');

          } else {

            console.log('No changes');

          }
        }

      } catch (error) {

        console.error(error);

      }
    }

    pollItinerary();

    setInterval(pollItinerary, 5000);
  </script>

<?php endif; ?>

<div class="container py-5">

  <?php if ($activity_error): ?>

    <div class="alert alert-danger mt-4">

      <?= $activity_error ?>

    </div>

  <?php endif; ?>

  <?php if ($activity_success): ?>

    <div class="alert alert-success mt-4">

      <?= $activity_success ?>

    </div>

  <?php endif; ?>

  <!-- HERO -->

  <div class="hero fade-up">

    <img
      src="<?= $trip['image'] ?: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=1400' ?>"
      alt="<?= htmlspecialchars($trip['trip_name']) ?>">

    <div class="hero-overlay"></div>

    <div class="hero-content">

      <h1>

        <?= htmlspecialchars($trip['trip_name']) ?>

      </h1>

      <div class="hero-dest">

        <svg xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          viewBox="0 0 24 24">

          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />

        </svg>

        <?= htmlspecialchars($trip['destination']) ?>

      </div>

      <div class="hero-dates">

        <?= date('d M Y', strtotime($trip['start_date'])) ?>

        —

        <?= date('d M Y', strtotime($trip['end_date'])) ?>

      </div>

    </div>

  </div>

  <!-- TRIP BAR -->

  <div class="trip-bar fade-up fade-up-1">

    <div>

      <div class="trip-bar-label">

        Travellers

      </div>

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

                  <input type="hidden"
                    name="trip_id"
                    value="<?= $trip['id'] ?>">

                  <input type="hidden"
                    name="member_id"
                    value="<?= $m['id'] ?>">

                  <button type="submit"
                    class="member-remove-btn">

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

          <form method="POST"
            action="/Tripverse/app/controllers/TripMemberController.php?action=invite">

            <input type="hidden"
              name="trip_id"
              value="<?= $trip['id'] ?>">

            <button type="submit"
              class="btn-invite">

              Invite

            </button>

          </form>

        <?php endif; ?>

      </div>

    </div>

    <div class="budget-chip">

      <span class="currency">

        Budget

      </span>

      <span class="amount">

        $<?= number_format($trip['budget']) ?>

      </span>

    </div>

  </div>

  <!-- TABS -->

  <?php include __DIR__ . '/../layout/tabbar.php'; ?>

  <!-- DYNAMIC CONTENT -->

  <div id="activitiesContainer">

    <?php if ($activeTab === 'itinerary'): ?>

      <?php include __DIR__ . '/partials/dayPanels.php'; ?>

    <?php elseif ($activeTab === 'documents'): ?>

      <?php include __DIR__ . '/../documents/content.php'; ?>

    <?php elseif ($activeTab === 'polls'): ?>

      <?php include __DIR__ . '/../poll/content.php'; ?>

    <?php elseif ($activeTab === 'expenses'): ?>

      <?php include __DIR__ . '/../expense/addExpense.php'; ?>

    <?php endif; ?>

  </div>

</div>

<script>
  function selectDay(tab, panelId) {

    document
      .querySelectorAll('.day-tab')
      .forEach(t => t.classList.remove('active'));

    document
      .querySelectorAll('.day-panel')
      .forEach(p => p.classList.remove('active'));

    tab.classList.add('active');

    const panel =
      document.getElementById(panelId);

    if (panel) {

      panel.classList.add('active');

      tab.scrollIntoView({

        behavior: 'smooth',

        block: 'nearest',

        inline: 'center'
      });
    }
  }
</script>