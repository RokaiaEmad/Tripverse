<link rel="stylesheet"
      href="/Tripverse/app/assets/css/documents.css">

<div class="documents-wrapper">

    <div class="documents-container">

        <!-- HEADER -->

        <div class="documents-header">

            <h1>Document Vault</h1>

            <p>
                Upload and manage trip documents securely
            </p>

        </div>

        <!-- FORM -->

        <form method="POST"
              action="/Tripverse/app/controllers/DocumentController.php?action=upload"
              enctype="multipart/form-data">

            <input type="hidden"
                   name="trip_id"
                   value="<?= $trip['id'] ?>">

            <!-- TYPE -->

            <div class="form-group">

                <label>
                    Document Type
                </label>

                <select name="type"
                        class="form-input">

                    <option value="passport">
                        Passport
                    </option>

                    <option value="ticket">
                        Ticket
                    </option>

                    <option value="other">
                        Other
                    </option>

                </select>

            </div>

            <!-- VISIBILITY -->

            <div class="form-group">

                <label>
                    Visibility
                </label>

                <select name="visibility"
                        class="form-input">

                    <option value="private">
                        Private
                    </option>

                    <option value="leader">
                        Leader Only
                    </option>

                    <option value="group">
                        Group
                    </option>

                </select>

            </div>

            <!-- FILE -->

            <div class="form-group">

                <label>
                    Upload File
                </label>

                <input type="file"
                       name="document"
                       class="form-input file-input"
                       required>

            </div>

            <!-- BUTTON -->

            <button type="submit"
                    class="upload-btn">

                Upload Document

            </button>

        </form>

        <!-- DOCUMENTS LIST -->

        <div class="documents-list">

            <h2 class="documents-title">
                Uploaded Documents
            </h2>

            <?php foreach ($documents as $doc): ?>

                <div class="document-card">

                    <div>

                        <h3>
                            <?= htmlspecialchars($doc['type']) ?>
                        </h3>

                        <p>
    Uploaded by:
    <?= htmlspecialchars($doc['owner_name']) ?>
</p>

                        <p>
                            Visibility:
                            <?= htmlspecialchars($doc['visibility']) ?>
                        </p>

                    </div>

                   <div class="document-actions">

    <a href="/Tripverse/app/uploads/documents/<?= $doc['file_path'] ?>"
       target="_blank"
       class="view-btn">

        View

    </a>

    <?php if ($doc['owner_member_id'] == $_SESSION['user_id']): ?>

        <a href="/Tripverse/app/controllers/DocumentController.php?action=delete&document_id=<?= $doc['document_id'] ?>&itinerary_id=<?= $itinerary_id ?>"
           class="delete-btn"
           onclick="return confirm('Delete this document?')">

            Delete

        </a>

    <?php endif; ?>

</div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>