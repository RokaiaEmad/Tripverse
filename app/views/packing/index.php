<link rel="stylesheet"
      href="/Tripverse/app/assets/css/documents.css">

<link rel="stylesheet"
      href="/Tripverse/app/assets/css/packing.css">

      <?php
$profiles = $profiles ?? [];
$members = $members ?? [];
?>

<div class="documents-wrapper packing-full">

    <!-- PACKING ITEMS -->

    <div class="documents-list full-width-list">

        <div class="documents-header mb-4">

            <h2>
                Suggested Packing Items
            </h2>

            <p>
                Auto-generated from itinerary activities
            </p>

        </div>

        <?php if (empty($items)): ?>

            <div class="empty-state">

                No packing items yet

            </div>

        <?php endif; ?>

        <?php foreach ($items as $item): ?>

            <div class="document-card packing-card">

                <!-- LEFT -->

                <div class="packing-info">

                    <h3>

                        <?= htmlspecialchars($item['name']) ?>

                    </h3>

                    <?php if ($item['suggested']): ?>

                        <span class="suggested-badge">

                            Suggested From Activity

                        </span>

                    <?php endif; ?>

                    <p class="packing-status">

                        Status:

                        <span>

                            <?= $item['is_checked']
                                ? 'Packed'
                                : 'Not Packed'
                            ?>

                        </span>

                    </p>

                    <p class="packing-status">

                        Assigned To:

                        <span>

                            <?= $item['assigned_name']
                                ?? 'Nobody'
                            ?>

                        </span>

                    </p>

                </div>

                <!-- RIGHT -->

                <div class="packing-actions">

                    <!-- TOGGLE -->

                    <a
                    href="/Tripverse/app/controllers/PackingController.php?action=toggle&item_id=<?= $item['item_id'] ?>&trip_id=<?= $trip['id'] ?>"
                    class="view-btn">

                        <?= $item['is_checked']
                            ? 'Unpack'
                            : 'Pack'
                        ?>

                    </a>

                    <!-- DELETE -->

                

                    <!-- ASSIGN -->

                    <form method="POST"
                          action="/Tripverse/app/controllers/PackingController.php?action=assign"
                          class="assign-form">

                        <input type="hidden"
                               name="item_id"
                               value="<?= $item['item_id'] ?>">

                        <input type="hidden"
                               name="trip_id"
                               value="<?= $trip['id'] ?>">

                        <select name="user_id"
                                class="form-input assign-select">

                            <option value="">
                                Assign Member
                            </option>

                            <?php foreach ($members as $m): ?>

                                <option value="<?= $m['id'] ?>">

                                    <?= htmlspecialchars($m['name']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <button type="submit"
                                class="upload-btn assign-btn">

                            Assign

                        </button>

                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>