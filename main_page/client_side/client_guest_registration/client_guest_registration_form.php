<div class="container bg-white mt-5 p-5">
    <main class="pb-3 ">
        <div class="py5-auto">
            <div class="card-header">
                <h1>Guest Registration<h1>
            </div>
            <form class="p-5 mt-5" method="POST" action="client_guest_registration_action.php" autocomplete="on">


                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required />
                </div>
                <input class="btn btn-primary" type="submit" name="guestRegistration" value="Register">


            </form>

        </div>
</div>
</main>
</div>