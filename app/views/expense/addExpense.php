<div class="expense-container">

    <style>
        .expense-container {

            width: 100%;
            max-width: 700px;

            background: rgba(255, 255, 255, 0.04);

            border: 1px solid rgba(255, 255, 255, 0.08);

            border-radius: 24px;

            padding: 35px;

            backdrop-filter: blur(10px);

            margin: 40px auto;

            color: white;

            font-family: Arial, sans-serif;
        }

        .expense-title {

            font-size: 34px;

            margin-bottom: 30px;

            color: #00ffc3;

            text-align: center;
        }

        .expense-group {

            margin-bottom: 25px;
        }

        .expense-label {

            display: block;

            margin-bottom: 10px;

            font-size: 16px;

            font-weight: 600;
        }

        .expense-input,
        .expense-select {

            width: 100%;

            padding: 14px 16px;

            border-radius: 14px;

            border: none;

            outline: none;

            background: rgba(255, 255, 255, 0.08);

            color: white;

            font-size: 15px;
        }

        .expense-input::placeholder {

            color: #b8c2d3;
        }

        .expense-select option {

            color: black;
        }

        .expense-btn {

            width: 100%;

            padding: 16px;

            border: none;

            border-radius: 16px;

            background: #00ffc3;

            color: #02153a;

            font-size: 17px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .expense-btn:hover {

            background: #00d9a7;

            transform: translateY(-2px);
        }

        /* remove arrows */

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {

            -webkit-appearance: none;

            margin: 0;
        }

        input[type="number"] {

            appearance: textfield;

            -moz-appearance: textfield;
        }
    </style>

    <h1 class="expense-title">
        Add Expense
    </h1>

    <form method="POST"
        action="../controllers/ExpenseController.php?action=store">

        <!-- TRIP ID -->
        <input type="hidden"
            name="trip_id"
            value="<?= $trip['id'] ?>">

        <!-- EXPENSE TITLE -->
        <div class="expense-group">

            <label class="expense-label">
                Expense Title
            </label>

            <input type="text"
                name="title"
                class="expense-input"
                placeholder="Enter expense title"
                required>

        </div>

        <!-- AMOUNT -->
        <div class="expense-group">

            <label class="expense-label">
                Amount
            </label>

            <input type="number"
                step="0.01"
                name="amount"
                class="expense-input"
                placeholder="Enter amount"
                required>

        </div>

        <!-- CATEGORY -->
        <div class="expense-group">

            <label class="expense-label">
                Category
            </label>

            <select name="category"
                class="expense-select"
                required>

                <option value="">
                    Choose Category
                </option>

                <option value="Food">
                    Food
                </option>

                <option value="Transport">
                    Transport
                </option>

                <option value="Hotel">
                    Hotel
                </option>

                <option value="Shopping">
                    Shopping
                </option>

                <option value="Activities">
                    Activities
                </option>

            </select>

        </div>

        <!-- MEMBER -->
        <div class="expense-group">

            <label class="expense-label">
                Choose Member
            </label>

            <select name="member_id"
                class="expense-select"
                required>

                <option value="">
                    Choose Member
                </option>

                <?php foreach ($members as $member): ?>

                    <option value="<?= $member['id'] ?>">

                        <?= htmlspecialchars($member['name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- SHARE AMOUNT -->
        <div class="expense-group">

            <label class="expense-label">
                Share Amount
            </label>

            <input type="number"
                step="0.01"
                name="share_amount"
                class="expense-input"
                placeholder="Enter amount">

        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="expense-btn">

            Add Expense

        </button>

    </form>

</div>