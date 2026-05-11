<div class="polls-page">

    <h2 class="text-white mb-4">
        Polls
    </h2>

    <form method="POST"
          action="/Tripverse/app/controllers/PollingController.php?action=create">

        <input type="hidden"
               name="trip_id"
               value="<?= $trip['id'] ?>">

        <div class="mb-3">

            <input type="text"
                   name="title"
                   class="form-control"
                   placeholder="Poll title"
                   required>

        </div>

        <div class="mb-3">

            <select name="type"
                    class="form-select">

                <option value="general">
                    General
                </option>

                <option value="must_have">
                    Must Have
                </option>

                <option value="nice_to_have">
                    Nice To Have
                </option>

            </select>

        </div>

        <div class="mb-3">

            <input type="datetime-local"
                   name="deadline"
                   class="form-control">

        </div>

        <div class="form-check mb-3">

            <input type="checkbox"
                   name="is_anonymous"
                   value="1"
                   class="form-check-input">

            <label class="form-check-label text-white">

                Anonymous Poll

            </label>

        </div>

        <button type="submit"
                class="btn btn-primary">

            Create Poll

        </button>

    </form>

</div>