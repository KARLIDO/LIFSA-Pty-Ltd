<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        button { padding: 5px 10px; cursor: pointer; }
        .modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                 width: 400px; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px gray; }
        .modal input, .modal select { width: 100%; padding: 5px; margin: 5px 0; }
        .close-btn { background: red; color: white; padding: 5px 10px; cursor: pointer; border: none; float: right; }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Venue</th>
            <th>Fees / Person</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $events = [
            ["19 - 23 May 2025", "Kampala, Uganda", "USD2,500"],
            ["26 - 30 May 2025", "Kigali, Rwanda", "USD2,500"],
            ["07 – 11 July 2025", "Dar es Salaam, Tanzania", "USD2,500"],
            ["11 - 15 Aug 2025", "Lusaka, Zambia", "USD2,500"]
        ];
        foreach ($events as $event) {
            echo "<tr>
                    <td>{$event[0]}</td>
                    <td>{$event[1]}</td>
                    <td>{$event[2]}</td>
                    <td>
                        <button class='register-btn' data-date='{$event[0]}' data-venue='{$event[1]}' data-fees='{$event[2]}'>Register</button>
                        <button class='register-group-btn'>Register Group</button>
                    </td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<!-- Individual Registration Form -->
<div id="registerModal" class="modal">
    <button class="close-btn">X</button>
    <h3>Register for Event</h3>
    <form id="registerForm">
        <input type="hidden" name="registration_type" value="individual">
        <input type="hidden" name="event_date">
        <input type="hidden" name="venue">
        <input type="hidden" name="fees">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="organization" placeholder="Organization" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="country" placeholder="Country" required>
        <button type="submit">Complete Registration</button>
    </form>
</div>

<!-- Group Registration Form -->
<div id="registerGroupModal" class="modal">
    <button class="close-btn">X</button>
    <h3>Register a Group</h3>
    <form id="registerGroupForm">
        <input type="hidden" name="registration_type" value="group">
        <p>Pricing based on the number of participants and locations involved</p>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="organization" placeholder="Organization" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="country" placeholder="Country" required>
        <button type="submit">Complete Registration</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $(".register-btn").click(function() {
        let date = $(this).data("date");
        let venue = $(this).data("venue");
        let fees = $(this).data("fees");
        
        $("#registerModal input[name='event_date']").val(date);
        $("#registerModal input[name='venue']").val(venue);
        $("#registerModal input[name='fees']").val(fees);
        
        $("#registerModal").show();
    });

    $(".register-group-btn").click(function() {
        $("#registerGroupModal").show();
    });

    $(".close-btn").click(function() {
        $(".modal").hide();
    });

    $("#registerForm, #registerGroupForm").submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.post("register.php", formData, function(response) {
            alert(response);
            $(".modal").hide();
        });
    });
});
</script>

</body>
</html>
