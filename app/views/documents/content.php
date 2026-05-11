<div class="documents-page">

    <h2 class="text-white mb-4">
        Documents
    </h2>

    <form method="POST"
          action="/Tripverse/app/controllers/DocumentController.php?action=upload"
          enctype="multipart/form-data">

        <input type="hidden"
               name="trip_id"
               value="<?= $trip['id'] ?>">

        <div class="mb-3">

            <select name="type"
                    class="form-select">

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

        <div class="mb-3">

            <select name="visibility"
                    class="form-select">

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

        <div class="mb-3">

            <input type="file"
                   name="document"
                   class="form-control"
                   required>

        </div>

        <button type="submit"
                class="btn btn-success">

            Upload

        </button>

    </form>

</div>