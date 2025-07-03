<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Payment Method</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ddd;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: bold;
        }

        #card-element {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fafafa;
        }

        .btn {
            background: #635bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:disabled {
            background: #aaa;
        }

        .error {
            color: #d33;
            margin-top: 10px;
        }

        .success {
            color: #28a745;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add Payment Method</h2>
        <form id="payment-form">
            <div class="form-group">
                <label for="card-element">Credit or debit card</label>
                <div id="card-element"></div>
            </div>
            <button class="btn" id="submit-btn">Add Card</button>
            <div id="card-errors" class="error"></div>
            <div id="card-success" class="success"></div>
        </form>
    </div>
    <script>
        // ضع هنا مفتاح Stripe Publishable الخاص بك
        const stripe = Stripe('pk_test_51RVEgTQTuEnVAVOq1MppPgUyKQxdGWixjboLaPJTTxGgO5gj0Z67Vf6Mu7I2mZ3Ydj50juLPWPKCguJkHOIcaeWx00iWKBP1We');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const errorDiv = document.getElementById('card-errors');
        const successDiv = document.getElementById('card-success');
        const submitBtn = document.getElementById('submit-btn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorDiv.textContent = '';
            successDiv.textContent = '';
            submitBtn.disabled = true;
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });
            if (error) {
                errorDiv.textContent = error.message;
                submitBtn.disabled = false;
            } else {
                // أرسل paymentMethod.id إلى الباك اند
                fetch('/api/payment-methods', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_method_id: paymentMethod.id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            successDiv.textContent = 'Card added successfully!';
                        } else {
                            errorDiv.textContent = data.message || 'Error occurred';
                        }
                        submitBtn.disabled = false;
                    })
                    .catch(() => {
                        errorDiv.textContent = 'Network error.';
                        submitBtn.disabled = false;
                    });
            }
        });
    </script>
</body>

</html>