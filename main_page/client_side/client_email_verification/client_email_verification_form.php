<?php
session_start();
?>
<div class="container bg-white mt-5 p-5">
    <main class="pb-3 ">
        <div class="py5-auto text-align-center">
            <div class="card-header p-5">
                <h1>Verify your email<h1>
            </div>

            <form class="p-5" method="POST" action="client_email_verification_action.php">
                <div class="content">

                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control  " placeholder="Verification Code" id="otp" name="otpCode" />
                        </div>
                        <div class="col-auto">
                            <input class="btn btn-primary" type="submit" name="check" value="Submit">
                        </div>
                    </div>
                </div>
            </form>

        </div>