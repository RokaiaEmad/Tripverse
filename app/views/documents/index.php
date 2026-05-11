<form method="POST"
      action="/Tripverse/app/controllers/DocumentController.php?action=upload"
      enctype="multipart/form-data">

    <input type="hidden"
           name="trip_id"
           value="<?= $trip['id'] ?>">

    <select name="type">

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

    <select name="visibility">

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

    <input type="file"
           name="document"
           required>

    <button type="submit">
        Upload
    </button>

</form>