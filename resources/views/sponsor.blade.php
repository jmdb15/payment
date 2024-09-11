<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .modal-dialog {
            pointer-events: none; /* Prevent clicking inside the modal from closing it */
        }
        .modal-content {
            pointer-events: auto; /* Allow interaction within modal content */
        }
    </style>
</head>
<body>

    <h1>Simple Form</h1>

    <form id="myForm">
        <!-- Dropdown -->
        <div class="form-group">
            <label for="dropdown">Select an item:</label>
            <select id="dropdown" name="amount" >
                <option value="" disabled selected>Select an option</option>
                <option value="10000">100</option>
                <option value="20000">200</option>
                <option value="30000">300</option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="button" onclick="enableFields()">Continue</button>
        </div>

        <!-- Input fields -->
        <div class="form-group">
            <label for="input1">Name:</label>
            <input type="text" id="input1" name="name" disabled>
        </div>

        <div class="form-group">
            <label for="input2">Email:</label>
            <input type="text" id="input2" name="email" disabled>
        </div>

        <div class="form-group">
            <label for="input3">Phone:</label>
            <input type="text" id="input3" name="phone" disabled>
        </div>

        <div class="form-group">
            <button type="button" onclick="handleSubmit()">Continue</button>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Confirm</button>
        </div>
    </form>

    <!-- Modal Structure -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-body">
              <div class="modal-content">
                <iframe id="modalIframe" src="" width="100%" height="400px" frameborder="0"></iframe>
              </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
      let clientKey = '';
      let paymentMethodId = '';
      let paymentIntentId = ''

      
        function enableFields() {
            const dropdownValue = document.getElementById('dropdown').value;

            const input1 = document.getElementById('input1');
            const input2 = document.getElementById('input2');
            const input3 = document.getElementById('input3');

            // Enable the dropdown and input fields
            input1.disabled = false;
            input2.disabled = false;
            input3.disabled = false;
            
            fetch('/createPaymentIntent', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Make sure you have a meta tag with CSRF token in your HTML
              },
              body: JSON.stringify({
                amount: dropdownValue
              })
            })
            .then(response => response.json())
            .then(data => {
              clientKey = data.data.attributes.client_key;
              paymentIntentId = clientKey.split('_client')[0];
            })
            .catch(error => {
              console.error('Error:', error);
            });

        }

        function handleSubmit() {
            const name = document.getElementById('input1').value;
            const email = document.getElementById('input2').value;
            const phone = document.getElementById('input3').value;

            if (!dropdown || !input1 || !input2 || !input3) {
                alert('Please fill out all fields.');
                return;
            }

             fetch('/createPaymentMethod', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Make sure you have a meta tag with CSRF token in your HTML
              },
              body: JSON.stringify({
                name,
                email,
                phone
              })
            })
            .then(response => response.json())
            .then(data => {
              paymentMethodId = data.data.id;
            })
            .catch(error => {
              console.error('Error:', error);
            });
        }

        function handleConfirm(){
          var iframe = document.getElementById('modalIframe');

          fetch('/attach', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Make sure you have a meta tag with CSRF token in your HTML
              },
              body: JSON.stringify({
                clientKey,
                paymentMethodId,
                paymentIntentId
              })
            })
            .then(response => response.json())
            .then(data => {
              iframe.src = data.data.attributes.next_action.redirect.url; 
            })
            .catch(error => {
              console.error('Error:', error);
            });
        }

        // Add an event listener to set iframe src when modal is shown
        $('#exampleModal').on('show.bs.modal', function (e) {
            handleConfirm();
        });
        
    </script>

</body>
</html>
