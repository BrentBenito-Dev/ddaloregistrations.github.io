
<div class ="container">
    <div class="row">
        <div class="col-md-6">
            <video id="preview" width="100%"></video>
            <?php
                if(isset($_SESSION['error'])){
                    echo "<div class = 'alert alert-danger'>
                            <h4>ERROR!</h4>"
                            .$_SESSION['error']."
                            </div>";
                            
                }
                if(isset($_SESSION['success'])){
                    echo "<div class = 'alert alert-success'>
                            <h4>Success</h4>"
                            .$_SESSION['success']."
                            </div>";
                            
                }
            ?>
        </div>
        <div class="col-md-6">
            <form action="admin_qr_scanner_action.php" method="post" class="form-horizontal">
                <label>SCAN QR CODE</label>
                <input type="text" name="Id" id="Id" readonly="" placeholder="scan qrcode" class="form-control">
            </form>
            <?php 
                include'admin_qr_scanner_table.php';
            ?>
        </div>
    </div>
</div>


<script>
    let scanner = new Instascan.Scanner({video: document.getElementById('preview')});
    Instascan.Camera.getCameras().then(function(cameras){
        if(cameras.length > 0){
            scanner.start(cameras[0]);
        }else{
            alert('No cameras found');
        }
    }).catch(function(e) {
        console.error(e);
    });

    scanner.addListener('scan',function(c){
        document.getElementById('Id').value=c;
        document.forms[0].submit();
    });
</script> 
    