"use strict";
var stripe1;
$(document).ready(function () {
    $("input[name='payment_method']").on('change', function (e) {
        e.preventDefault();
        var amount = $("#order_amount").val();
        var package_id = $("#pid").val();
        var tenure_id = $("#tid").val();
        if ($(this).val() == "Stripe") {
            $('#stripe_div').slideDown();
        } else {
            $('#stripe_div').slideUp();
        }
        if ($(this).val() == "Stripe") {
            $.post(base_url + "admin/billing/pre-payment-setup", { [csrfName]: csrfHash, 'payment_method': 'Stripe', 'amount': amount, 'package_id': package_id, 'tenure_id': tenure_id }, function (data) {
                $('#stripe_client_secret').val(data.client_secret);
                $('#stripe_payment_id').val(data.id);
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
            }, "json");
            stripe1 = stripe_setup($('#stripe_key_id').val());

        } else if ($(this).val() == "Razorpay") {
            $.post(base_url + "admin/billing/pre-payment-setup", { [csrfName]: csrfHash, 'payment_method': 'Razorpay', 'tenure_id': tenure_id }, function (data) {
                $('#razorpay_order_id').val(data.order_id);
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
            }, "json");
        }
    });
    function stripe_setup(key) {
        // A reference to Stripe.js initialized with a fake API key.
        // Sign in to see examples pre-filled with your key.
        var stripe = Stripe(key);
        // Disable the button until we have Stripe set up on the page
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#32325d"
                }
            },
            invalid: {
                fontFamily: 'Arial, sans-serif',
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", {
            style: style
        });
        card.mount("#stripe-card-element");

        card.on("change", function (event) {
            // Disable the Pay button if there are no card details in the Element
            document.querySelector("button").disabled = event.empty;
            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
        });
        return {
            'stripe': stripe,
            'card': card
        };
    }
    function stripe_payment(stripe, card, clientSecret) {
        // Calls stripe.confirmCardPayment
        // If the card requires authentication Stripe shows a pop-up modal to
        // prompt the user to enter authentication details without leaving your page.
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card
            }

        })
            .then(function (result) {
                if (result.error) {
                    // Show error to your customer
                    var errorMsg = document.querySelector("#card-error");
                    errorMsg.textContent = result.error.message;
                    if(result.error.message.substring(0, 13)=='As per Indian'){
                        result.error.message = 'First name, Address and Country should be filled in your profile';
                    }
                    
                    iziToast.error({
                        title: result.error.message,
                        message: '',
                        position: 'topRight'
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 6000);
                } else {
                    // The payment succeeded!
                    add_user_package('Stripe').done(function (result) {
                        if (result.error == false) {
                            setTimeout(function () {
                                location.href = base_url + 'admin/payment/success';
                            }, 3000);
                        }
                    });
                }
            });
    };

    $(document).on('click', '#submit_btn', function (e) {
        $('#submit_btn').html('Please Wait..'); $('#submit_btn').attr('disabled', true);
        e.preventDefault();
        var payment_methods = $("input[name='payment_method']:checked").val();
        if (payment_methods == "Stripe") {
            var stripe_client_secret = $('#stripe_client_secret').val();
            stripe_payment(stripe1.stripe, stripe1.card, stripe_client_secret);
        } else if (payment_methods == "Razorpay") {
            var key = $('#razorpay_key_id').val();
            var order_amount = $('#order_amount').val();
            var app_name = $('#app_name').val();
            var logo = $('#logo').val();
            var razorpay_order_id = $('#razorpay_order_id').val();
            var username = $('#username').val();
            var user_email = $('#user_email').val();
            var user_contact = $('#user_contact').val();
            var rzp1 = razorpay_setup(key, order_amount * 100, app_name, logo, razorpay_order_id, username, user_email, user_contact);
            rzp1.open();
            rzp1.on('payment.failed', function (response) {
                location.href = base_url + 'admin/payment/cancel';
            });
        } else if (payment_methods == "Paypal") {

            add_user_package('Paypal').done(function (result) {
                if (result.error == false) {
                    iziToast.success({
                        title: result.message,
                        message: '',
                        position: 'topRight'
                    });
                    setTimeout(function () {
                        $('#csrf_token').val(csrfHash);
                        $('#paypal_form').submit();
                    }, 5000);

                } else {
                    iziToast.error({
                        title: result.message,
                        message: '',
                        position: 'topRight'
                    });
                }

            });
        } else {
            var package_id = $('#pid').val();
            var tenure_id = $('#tid').val();
            $.post(base_url + "admin/billing/add-subscription", { [csrfName]: csrfHash, 'package_id': package_id, 'tenure_id': tenure_id, 'payment_method': '' }, function (data) {
                if (data.error == true) {
                    $('#submit_btn').html('Proceed'); $('#submit_btn').attr('disabled', false);
                    $('#checkout_result').html('<div class="alert alert-danger">' + data.message + '</div>')
                    $('#checkout_result').removeClass("d-none");
                    setTimeout(function () { $('#checkout_result').delay(6000).addClass("d-none") }, 6000);
                } else {
                    location.reload();
                }
            }, "json");
        }



    });
    function add_user_package(payment_method) {
        let myForm = document.getElementById('checkout_form');
        var formdata = new FormData(myForm);
        formdata.append(csrfName, csrfHash);
        var package_id = $("#pid").val();
        var tenure_id = $("#tid").val();
        formdata.append('package_id', package_id);
        formdata.append('tenure_id', tenure_id);
        formdata.append('payment_method', payment_method);
        return $.ajax({
            type: 'POST',
            data: formdata,
            url: base_url + 'admin/billing/add_subscription',
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#submit_btn').attr('disabled', true).html('Please Wait...');
            },
            success: function (data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                $('#submit_btn').attr('disabled', false).html('Proceed');
                if (payment_method == "Stripe") {
                    iziToast.success({
                        title: data.message,
                        message: '',
                        position: 'topRight'
                    });


                }
            }
        })
    }

    function razorpay_setup(key, amount, app_name, logo, razorpay_order_id, username, user_email, user_contact) {
        var options = {
            "key": key, // Enter the Key ID generated from the Dashboard
            "amount": (amount), // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": "INR",
            "name": app_name,
            "description": "Product Purchase",
            "image": logo,
            "order_id": razorpay_order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function (response) {
                $('#razorpay_payment_id').val(response.razorpay_payment_id);
                $('#razorpay_signature').val(response.razorpay_signature);
                add_user_package('Razorpay').done(function (result) {
                    if (result.error == false) {
                        $("#p_title").val(result.package_title);
                        $("#f_date").val(result.from_date);
                        $("#t_date").val(result.to_date);
                        $("#razorpay_form").submit();
                    } else {
                        iziToast.error({
                            title: result.message,
                            message: '',
                            position: 'topRight'
                        });
                    }
                });
            },
            "prefill": {
                "name": username,
                "email": user_email,
                "contact": user_contact
            },
            "notes": {
                "address": app_name + " Purchase"
            },
            "theme": {
                "color": "#3399cc"
            },
            "modal": {
                "ondismiss": function () {
                    $('#submit_btn').attr('disabled', false).html('Proceed');
                }
            }
        };
        var rzp = new Razorpay(options);
        return rzp;
    }
});
