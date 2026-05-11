<?php
if (!isset($activeTab)) {
    $activeTab = 'itinerary'; // بدل 'home'
}
?>
<?php
if (!isset($itinerary_id)) {
    $itinerary_id = 0;
}
?>
<?php
if (!isset($trip)) {
    $trip = ['id' => 0];
}
?>
<style>
    :root {

        --bg-page: #f5f7fb;
        --bg-tabs: #ffffff;
        --border-color: #e5e7eb;
        --text-main: #4b5563;
        --accent-color: #2563eb;
        --hover-effect: #f9fafb;
        --text-main: rgba(232, 240, 255, .45);
        --accent-color: #00e5a0;
        --hover-effect: rgba(0, 229, 160, .05);
        --border-color: rgba(0, 229, 160, .15);
    }


    /* <?php if ($activeTab === 'itinerary'): ?>
    :root {
        --bg-page: #0f1115;
        --bg-tabs: #181a20;
        --border-color: rgba(255,255,255,0.07);
        --text-main: #94a3b8;
        --accent-color: #3b82f6;
        --hover-effect: rgba(255,255,255,0.03);
    }
    <?php endif; ?> */


    /* body {
        background: var(--bg-page) !important;
    } */

    .trip-tabs {
        background: #060d1f;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        margin: 20px;
    }

    .switch-bar {
        display: flex;
        align-items: center;
        justify-content: space-around;
        height: 90px;
    }

    .switch-bar a {
        position: relative;
        height: 100%;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-decoration: none;
        color: var(--text-main);
        font-size: 18px;
        font-weight: 600;
        transition: 0.3s;
    }

    .switch-bar a:hover {
        background: var(--hover-effect);
    }

    .switch-bar a.active {
        color: var(--accent-color);
    }

    .switch-bar a.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 15%;
        width: 70%;
        height: 4px;
        background: var(--accent-color);
        border-radius: 10px;
    }

    .switch-bar a.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 15%;
        width: 70%;
        height: 3px;
        background: #00e5a0;
        /* نفس accent */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 229, 160, .4);
        /* glow خفيف */
    }
</style>

<div class="trip-tabs">
    <div class="switch-bar">

        <a
            href="/Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=<?php echo $itinerary_id; ?>"
            class="<?= $activeTab == 'itinerary' ? 'active' : '' ?>">
            <i class="fa-regular fa-map"></i>
            <span>Itinerary</span>
        </a>

        <a
            href="/Tripverse/app/controllers/ExpenseController.php?action=show&trip_id=<?= $trip['id'] ?>"
            class="<?= $activeTab == 'expenses' ? 'active' : '' ?>">
            <i class="fa-solid fa-wallet"></i>
            <span>Expenses</span>
        </a>

       <a
    href="/Tripverse/app/controllers/DocumentController.php?action=index&itinerary_id=<?= $itinerary_id ?>"
    class="<?= $activeTab == 'documents' ? 'active' : '' ?>"
>
    <i class="fa-regular fa-file-lines"></i>
    <span>Documents</span>
</a>

        <a href="voteController.php" class="<?= $activeTab == 'votes' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-simple"></i>
            <span>Votes</span>
        </a>

    </div>
</div>