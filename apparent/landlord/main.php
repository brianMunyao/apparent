<?php
//connection
require('../functions/conn.php');
session_start();


//check if picture of house is present
$picResult = mysqli_query($conn, "SELECT img1 FROM property WHERE ownerID='" . $_SESSION['id'] . "'");
while ($row = mysqli_fetch_assoc($picResult)) {
    $picInfo = $row['img1'];
    if ($picInfo == NULL) {
        $hasPic = false;
        echo ('
            <div class="imgUpload">
                <div class="innerImgUpload">
                    <p class="err"></p>
                    <i class="fa fa-times" aria-hidden="true" onclick="closeAlert(this)"></i>

                    <div class="file">
                        <i class="fas fa-image"></i>
                        <p class="text">Add a photo</p>
                        <form name="addImg" id="addImage" method="POST" enctype="multipart/form-data">
                            <input type="file" name="img" id="img" onchange="imageUpload(this.parentNode)"/>
                        </form>
                    </div>
                    <p class="imgSub">Let the world see your house..</p>
                    <hr/>
                </div>
            </div>
        ');
    } else {
        $hasPic = true;
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord</title>
    <link rel="stylesheet" href="main-style.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">

        <aside>
            <div class="logo"><a href="../index.html"><i class="fa fa-home" aria-hidden="true"></i>Apparent</a></div>
            <span><i class="fas fa-align-left"></i></span>
            <div class="icons">
                <div class="bell">
                    <i class="fa fa-bell" aria-hidden="true"></i>
                    <span class="notify"></span>
                </div>
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </aside>

        <div class="userProfile">
            <p class="name">Name: <?php echo $_SESSION['sess_user']; ?></p>
            <p class="info">Data: jndojnonoweno</p>
            <p class="log"><a href="../index.php"><i class="fa fa-power-off" aria-hidden="true"></i>Logout</a></p>
        </div>

        <main>
            <div class="innerMain">
                <div id="dashboard">
                    <div class="innerDash">
                        <p class="title">DashBoard</p>
                    </div>
                </div>

                <div id="transactions">
                    <div class="innerDash">
                        <p class="title">Transactions</p>
                    </div>
                </div>

                <div id="rooms">
                    <div class="innerDash">
                        <p class="title">Rooms</p>
                    </div>
                </div>

                <div id="property">
                    <div class="innerDash">
                        <div class="imageHolder">

                        </div>
                        <div class="specs">

                        </div>
                    </div>

                </div>
            </div>
        </main>

        <nav>
            <div class="scrollmark"></div>

            <div class="logo"><a href="main.php"><i class="fa fa-home" aria-hidden="true"></i>Apparent</a></div>

            <div class="navlinks"><a href=""><i class="fa fa-cubes" aria-hidden="true"></i><span>Dashboard</span></a></div>
            <div class="navlinks"><a href=""><i class="fas fa-coins"></i><span>Transactions</span></a></div>
            <div class="navlinks"><a href=""><i class="fa fa-th-large"></i><span>Rooms</span></a></div>
            <div class="navlinks"><a href=""><i class="fa fa-house-user"></i><span>My Property</span></a></div>

            <div class="logout"><a href="../index.php"><i class="fa fa-power-off" aria-hidden="true"></i>Back To Home</a></div>
        </nav>

    </div>


    <script src="main-script.js"></script>

    <script>
        let imageUpload = (form) => {
            let img_name = $('#img').val();
            let extension = $('#img').val().split('.').pop().toLowerCase();

            if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert('Invalid image file');
                $('#img').val('');
                return false;
            } else {
                $.ajax({
                    url: '../functions/addPic.php',
                    method: 'POST',
                    data: new FormData(form),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 0) {
                            alert('image not added');
                        } else if (data == 1) {
                            closeAlert($('.imgUpload .fa-times'))
                        }
                    }
                })
            }
        };
    </script>
</body>

</html>