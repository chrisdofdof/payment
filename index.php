<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Gateway</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.5.0/js/md5.min.js"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="row my-5">
    <div class="col-sm-6 offset-sm-3 py-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-4" style="height: 90%;">
                <img src="https://actionchapel.net/wp-content/uploads/2018/06/logo.png"
                     alt="Logo">
            </div>
            <div class="card-body px-lg-5 pt-4">
                <form onsubmit="event.preventDefault(); startTransaction();">
                    <fieldset>
                        <div class="form-group row">
                            <label for="customerID" class="col-sm-4 col-form-label">FULL NAME</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control-plaintext"
                                       id="customerID"
                                       placeholder="Full Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-4 col-form-label">PHONE</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control-plaintext" id="phone"
                                       placeholder="Phone" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-4 col-form-label">AMOUNT</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control-plaintext"
                                       id="amount"
                                       placeholder="Amount" required>
                            </div>
                        </div>
                        <button type="submit" class="col-sm-6 offset-sm-3 btn btn-success">Proceed</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<div>

</div>

<script>
    function startTransaction() {
        let gpid = "GPZEN065";
        let amount = $("#amount").val();
        let desc = "";
        let productID = "";
        let customerID = $("#customerID").val();
        let transactionInitTime = new Date().toJSON("yyyy/MM/dd HH:mm:ss");
        let referenceID = md5(gpid + amount + desc + productID + customerID + transactionInitTime);

        let onSuccess = function (data, status, headers, config) {
            console.log(data);
            let transactionID = data;
            let params = {
                tid: transactionID,
                GPID: gpid,
                amount: amount,
                desc: desc,
                referenceID: referenceID,
                productID: productID,
                customerID: customerID
            };
            let urlParams = new URLSearchParams(params).toString();
            window.location.href = 'https://www.zenithbank.com.gh/api.globalpay/Service/PaySecure?' + urlParams;
        };

        let onError = function (data, status, headers, config) {
            alert('Transaction failed!!! Please retry');
        };
        $.post(
            'https://www.zenithbank.com.gh/api.globalpay/Service/SecurePaymentRequest',
            new URLSearchParams({
                GPID: gpid,
                amount: amount,
                desc: desc,
                referenceID: referenceID,
                productID: productID,
                customerID: customerID
            }).toString(),
            {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).done(onSuccess).fail(onerror);
    }
</script>

</body>
</html>
