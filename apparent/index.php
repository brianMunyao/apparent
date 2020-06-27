<?php
require('functions/conn.php');
session_start();

if (isset($_SESSION['sess_user'])) {
    $logged = true;
} else {
    $logged = false;
}

if (isset($_SESSION['hh'])) {
    $hasHouse = $_SESSION['hh'];
} else {
    $hasHouse = false;
}

$fullname = $username = $email = $gender = $password = $err = '';
if (isset($_POST['signup'])) {
    $fullname = ucwords(trim($_POST['s_fullname']));
    $username = trim($_POST['s_username']);
    $email = trim($_POST['s_email']);
    $gender = trim($_POST['s_gender']);
    $password = md5(trim($_POST['s_password']));

    $checkEmailResult = mysqli_query($conn, "SELECT username FROM users WHERE username='$username' OR email='$email'");

    if (mysqli_num_rows($checkEmailResult) == 0) {
        //store
        $query = "INSERT INTO users(fullname,username,email,gender,password) VALUES('$fullname','$username','$email','$gender','$password')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['sess_user'] = $username;
            $logged = true;

            //get id
            $IDresult = mysqli_query($conn, "SELECT id FROM users WHERE username='" . $username . "'");
            if (mysqli_num_rows($IDresult) == 1) {
                while ($row = mysqli_fetch_assoc($IDresult)) {
                    $_SESSION['id'] = $row['id'];
                }
            }
            $hasHouse = false;
            $_SESSION['hh'] = false;
        } else {
            $logged = false;
        }
    } else {
        //something matches
    }
}


if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $query = "SELECT id FROM users WHERE username='" . $user . "' and password='" . $pass . "'";
    $result = mysqli_query($conn, $query);

    $numrows = mysqli_num_rows($result);
    if ($numrows == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];

            $_SESSION['sess_user'] = $user;
            $_SESSION['id'] = $id;
            $logged = true;
        }

        //
        //hasHouse??
        $houseResult = mysqli_query($conn, "SELECT ownerID FROM property WHERE ownerID=" . $id);
        if (mysqli_num_rows($houseResult) == 1) {
            $hasHouse = true;
            $_SESSION['hh'] = true;
        } else {
            $hasHouse = false;
        }
    } else {
        $logged = false;
        $err = "Invalid username or password";
    }
}

//available houses
$available = array();
$result = mysqli_query($conn, "SELECT phone,houseName,houseDesc,location,houseType,bedrooms,bathrooms,rent,totalRooms,vacantRooms,datePosted,img1,img2,img3,img4,fullname,email FROM property JOIN users ON property.ownerID= users.id");
$numrows = mysqli_num_rows($result);

$houseTypes = array(0 => 'Bedsitter', 1 => 'Single Room', 2 => '2 Bedroom', 3 => '3 Bedroom', 4 => '4 Bedroom');
if ($numrows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fullname = $row['fullname'];
        $email = $row['email'];
        $hName = $row['houseName'];
        $hDesc = $row['houseDesc'];
        $phone = $row['phone'];
        $loc = $row['location'];
        $hType = $houseTypes[$row['houseType']];
        $bedrooms = $row['bedrooms'];
        $bathrooms = $row['bathrooms'];
        $rent = $row['rent'];
        $tRooms = $row['totalRooms'];
        $vRooms = $row['vacantRooms'];
        $date = $row['datePosted'];
        $img1 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img1']) . '" alt=""/>';
        $img2 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img2']) . '" alt=""/>';
        $img3 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img3']) . '" alt=""/>';
        $img4 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img4']) . '" alt=""/>';


        array_push($available, ['fullname' => $fullname, 'email' => $email, 'hName' => $hName, 'hDesc' => $hDesc, 'phone' => $phone, 'loc' => $loc, 'hType' => $hType, 'bedR' => $bedrooms, 'bathR' => $bathrooms, 'rent' => $rent, 'tR' => $tRooms, 'vR' => $vRooms, 'img1' => $img1, 'img2' => $img2, 'img3' => $img3, 'img4' => $img4]);
    }
}


//get images
$images = array();
$imgQuery = "SELECT img1 FROM property WHERE img1 IS NOT NULL";
$imgResult = mysqli_query($conn, $imgQuery);
if (mysqli_num_rows($imgResult) > 0) {
    while ($row = mysqli_fetch_assoc($imgResult)) {
        array_push($images, '<img src="data:image/jpeg;base64,' . base64_encode($row["img1"]) . '" alt="feature"/>');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Apparent</title>

    <link rel="stylesheet" href="style.css" />
    <script src="https://kit.fontawesome.com/c51e0caa33.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>

<body>

    <!-- loader -->
    <div class="loader">
        <div>
            <i class="fa fa-home" aria-hidden="true"></i>
            <p class="bank">apparent</p>
        </div>
    </div>

    <nav>
        <span class="logo">
            <a href="index.php">
                <i class="fa fa-home" aria-hidden="true"></i> apparent
            </a>
        </span>

        <span class="navlink s property">
            <?php
            if ($hasHouse) {
                echo ('<a href="landlord/main.php">View Property</a>');
            } else {
                echo ('<span onclick="addProperty(' . $logged . ')">List Property</span>');
            }
            ?>

        </span>


        <span class="navlink s">
            <a href="#">
                Gallery
            </a>
        </span>
        <span class="navlink s">
            <a href="#">
                Help
            </a>
        </span>



        <div class="logCheck">
            <?php
            if ($logged) {
                echo ('
                    <span class="user" onmouseover="userPop()">Hi, ' . $_SESSION['sess_user'] . '<i class="fa fa-user-circle" aria-hidden="true"></i></span>
                    ');
            } else {
                echo ('
                    <span class="navlink login" onclick="modals(1)">
                        Login
                    </span>
                    <span class="navlink signup" onclick="modals(2)">
                        Sign Up
                    </span>
                    ');
            }
            ?>

        </div>

        <div class="burger">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <div class="profile">
        <p class="profile_name"><?php echo $_SESSION['sess_user']; ?></p>
        <p class="profile_email"><?php echo 'myemailaddress@gmail.com'; ?></p>
        <p class="logout"><a href="functions/logout.php" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i> logout</a></p>
    </div>

    <div class="mobileNav">
        <span class="navlink s property" onclick="modals(3)">
            <?php
            if ($hasHouse) {
                echo ('<a href="landlord/main.php">View Property</a>');
            } else {
                echo ('<span onclick="addProperty(' . $logged . ')">List Property</span>');
            }
            ?>
        </span>
        <span class="navlink s">
            <a href="#">
                Gallery
            </a>
        </span>
        <span class="navlink s">
            <a href="#">
                Help
            </a>
        </span>
        <hr>
        <div>
            <?php
            if ($logged) {
                echo ('
                    <span class="user" onmouseover="userPop()">Hi, ' . $_SESSION['sess_user'] . '<i class="fa fa-user-circle" aria-hidden="true"></i></span>
                    ');
            } else {
                echo ('
                    <span class="navlink login" onclick="modals(1)">
                        Login
                    </span>
                    <span class="navlink signup" onclick="modals(2)">
                        Sign Up
                    </span>
                    ');
            }
            ?>
        </div>
    </div>


    <!-- modals go here -->
    <div class="modal-body">
        <div class="login-modal">
            <form method="POST" class="login" id="login">
                <div class="h">
                    <span class="formTitle">LOGIN</span>
                    <i class="fa fa-times modal-close" aria-hidden="true"></i>
                </div>

                <p class="err"><?php echo $err; ?></p>

                <div class="formItem">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" class="username" name="username" placeholder="Username">
                </div>

                <div class="formItem">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" class="password" name="password" placeholder="Password">
                    <span class="see">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
                <input type="hidden" name="action" value="login">

                <div class="rem">
                    <span class="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </span>
                    <span class="forgot">
                        <a href="#">Forgot Password?</a>
                    </span>
                </div>

                <div class="formSubmit">
                    <input type="submit" value="LOGIN" name="login">
                </div>
            </form>
        </div>

        <div class="signup-modal">
            <form method="POST" class="signup" name="signup" onsubmit="checkSignup()">
                <div class="h">
                    <span class="formTitle">SIGN UP</span>
                    <i class="fa fa-times modal-close" aria-hidden="true"></i>
                </div>

                <div class="formItem">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="s_fullname" placeholder="Full Name" onkeyup="checkAvailability(0,this)">
                    <span class="s_err"></span>
                </div>

                <div class="formItem">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="s_username" placeholder="Username" onkeyup="checkAvailability(1,this)">
                    <span class="s_err"></span>
                </div>

                <div class="formItem">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <input type="email" name="s_email" placeholder="Email Address" onkeyup="checkAvailability(2,this)">
                    <span class="s_err"></span>
                </div>

                <div class="check">
                    <label for="male">Male<input type="radio" name="s_gender" id="male" value="1"></label>
                    <label for="female">Female<input type="radio" name="s_gender" id="female" value="0"></label>
                </div>

                <div class="formItem">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" name="s_password" placeholder="Password" onkeyup="checkAvailability(3,this)">
                    <span class="s_err"></span>
                    <span class="see">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div>

                <!-- <input type="hidden" name="action" value="signup"> -->

                <div class="formSubmit">
                    <input type="submit" value="SIGN UP" name="signup">
                </div>
            </form>
        </div>

        <div class="property-modal">
            <form method="POST" class="property" name="property" onsubmit="event.preventDefault(); postHouse(this)">
                <div class="h">
                    <span class="formTitle">POST MY PROPERTY</span>
                    <i class="fa fa-times modal-close" aria-hidden="true"></i>
                </div>
                <div class="formItem">
                    <i class="fa fa-text-height" aria-hidden="true"></i>
                    <input type="text" name="houseName" placeholder="House Name">
                </div>
                <div class="formItem">
                    <i class="fa fa-mobile" aria-hidden="true"></i>
                    <input type="tel" name="phone" placeholder="Phone Number">
                </div>
                <div class="formItem">
                    <i class="fa fa-text-width" aria-hidden="true"></i>
                    <input type="tel" name="bedrooms" placeholder="No. of Bedrooms">
                </div>
                <div class="formItem">
                    <i class="fa fa-mobile" aria-hidden="true"></i>
                    <input type="tel" name="bathrooms" placeholder="No. of Bathrooms">
                </div>
                <div class="formItem">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input type="text" name="location" placeholder="Location">
                </div>
                <div class="formItem">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input type="tel" name="totalRooms" placeholder="Total no. of rooms">
                </div>
                <div class="formItem">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input type="tel" name="vacantRooms" placeholder="Total no. of vacant rooms">
                </div>

                <div class="check">
                    <label for="h0">Bedsitter</label>
                    <input type="radio" name="houseType" value="0" id="h0">
                    <label for="h1">Single Room</label>
                    <input type="radio" name="houseType" value="1" id="h1">
                    <label for="h2">2 Bedroom</label>
                    <input type="radio" name="houseType" value="2" id="h2">
                    <label for="h3">3 Bedroom</label>
                    <input type="radio" name="houseType" value="3" id="h3">
                    <label for="h4">4 Bedroom</label>
                    <input type="radio" name="houseType" value="4" id="h4">
                </div>
                <div class="formItem">
                    <i class="fas fa-coins"></i>
                    <input type="text" name="rent" placeholder="Expected Rent">
                </div>
                <div class="tArea">
                    <textarea name="desc" class="desc" placeholder="Briefly describe your house"></textarea>
                </div>

                <div class="formSubmit">
                    <input type="submit" value="POST" name="property">
                </div>
            </form>
        </div>
    </div>


    <!-- website alerts eg. success and failure -->
    <div class="alertContainer">
        <div class="alertInner">
            <i class="fa fa-times" aria-hidden="true"></i>
            <div class="icon"></div>
            <div class="more">
                <p class="t"></p>
                <p class="tSub"></p>
            </div>
        </div>
    </div>

    <div class="start">
        <div class="mainDesc">
            <!-- <p class="mainTitle">Find your next home ..</p>
            <input type="text" name="search"> -->
        </div>

        <div class="slider">
            <!-- Images slideshow -->
        </div>
    </div>

    <!-- Featured houses -->
    <div class="featured">
        <p class="title">Available Houses</p>
        <div class="featuredDisplay">
            <!-- featured houses go here -->
        </div>
    </div>

    <div class="searchBar">
        <div class="inner">
            <span class="s">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input type="text" id="txt" placeholder="E.g. house name, location ..">
            </span>
            <input type="button" value="SEARCH" id="btn">
        </div>
    </div>

    <div class="searchResults">
        <div class="searchBar">
            <div class="inner">
                <span class="s">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" name="search" id="txt2" placeholder="E.g. house name, location .." onkeyup="searchHouse(this.value)">
                </span>
                <input type="button" name="search" value="SEARCH" id="btn2">
            </div>
        </div>

        <div class="filter">
            <div class="fItem">
                <label for="sort">Sort By:</label>
                <select name="sort" id="sort" onchange="searchHouse($('#txt2').val())">
                    <option value="0">Any</option>
                    <option value="1">Type</option>
                    <option value="2">Rent Ascending</option>
                    <option value="3">Rent Descending</option>
                </select>
            </div>
            <div class="fItem">
                <label for="type">House Type:</label>
                <select name="type" id="type" onchange="searchHouse($('#txt2').val())">
                    <option value="0">Any</option>
                    <option value="1">Bedsitter</option>
                    <option value="2">Single Room</option>
                    <option value="3">2 Bedrooms</option>
                    <option value="4">3 Bedrooms</option>
                </select>
            </div>
        </div>

        <div class="res">
            <p class="no">
                <span class="found">0 result(s)</span>
                <span>
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                </span>
            </p>
            <div class="houses">
                <!-- search results go here -->
            </div>
        </div>
    </div>



    <!-- why choose us section -->
    <div class="whyUs">
        <p class="title">Why Choose Us?</p>
        <div class="items">
            <div class="adv">
                <p class="subTitle">
                    Search Property from Anywhere
                </p>
                <p class="subContent">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam repudiandae quisquam officiis provident culpa cum, quasi perspiciatis possimus ducimus ut modi nemo saepe vero, ipsa molestiae mollitia autem magnam nisi?
                </p>
            </div>
            <div class="adv">
                <p class="subTitle">
                    Wider Markets for Home Owners
                </p>
                <p class="subContent">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam repudiandae quisquam officiis provident culpa cum, quasi perspiciatis possimus ducimus ut modi nemo saepe vero, ipsa molestiae mollitia autem magnam nisi?
                </p>
            </div>
            <div class="adv">
                <p class="subTitle">
                    Easy Payment Intermediary
                </p>
                <p class="subContent">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam repudiandae quisquam officiis provident culpa cum, quasi perspiciatis possimus ducimus ut modi nemo saepe vero, ipsa molestiae mollitia autem magnam nisi?
                </p>
            </div>
        </div>
    </div>

    <footer></footer>

    <script src="script.js"></script>

    <script>
        let available = <?php echo json_encode($available); ?>;
        available.forEach((house) => {
            //new HouseOps(house, document.querySelector('div.featuredDisplay'));
            display(house, document.querySelector('div.featuredDisplay'));

        });

        let images = <?php echo json_encode($images); ?>;
        const slider = document.querySelector('div.start div.slider');

        images.forEach((img) => {
            slider.innerHTML += img;
        });

        slider.insertAdjacentHTML(
            'beforeend',
            images[0]
        );

        let counter = 1;
        let carousel = setInterval(() => {
            slider.style.transition = 'all 0.5s ease-in-out';
            slider.style.transform = 'translateX(' + (-100 * counter) + 'vw)';
            counter++;
        }, 6000);

        slider.addEventListener('transitionend', () => {
            if (counter === images.length + 1) {
                slider.style.transition = 'none';
                counter = 1;
                slider.style.transform = 'translateX(0vw)';
            }
        });

        let checkAvailability = (no, input) => {
            if (input.value == '') {
                if (no == 0) {
                    input.nextElementSibling.innerHTML = 'Fullname required'
                }
                if (no == 1) {
                    input.nextElementSibling.innerHTML = 'Username required'
                }
                if (no == 2) {
                    input.nextElementSibling.innerHTML = 'Email required'
                }
                if (no == 3) {
                    input.nextElementSibling.innerHTML = 'Password required'
                }

            } else {
                $.ajax({
                    url: 'functions/availability.php?' + $.param({
                        item: no,
                        value: input.value
                    }),
                    type: 'POST',
                    contentType: false,
                    processData: false,

                    success: function(res) {
                        input.nextElementSibling.innerHTML = res
                    }
                })
            }
        };

        let checkSignup = () => {
            if (!$('form.signup input[name="s_username"]').val()) {
                console.log('ugd')
            }
        };

        let postHouse = (form) => {
            let xhr = new XMLHttpRequest()
            xhr.onreadystatechange = function() {
                if (this.status == 200 && this.readyState == 4) {
                    console.log(this.responseText)
                    if (this.responseText == 'success') {
                        $('form.login div.h .fa-times').click()
                    } else {
                        //if house details are wrong
                    }
                }
            }
            xhr.open('POST', 'functions/postHouse.php?' + $(form).serialize())
            xhr.send()
        };

        let loginCheck = (form) => {
            $.ajax({
                url: 'functions/form_handle.php',
                type: 'POST',
                data: new FormData(form),
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(res) {
                    let loggedIn = res[0].login
                    let user = res[0].username
                    let hasHouse = res[0].hasHouse
                    if (!loggedIn) {
                        $('form.login .err').html('Invalid username or password');
                        $('form.login input.password').val('')
                    } else {
                        updatePage(user, loggedIn, hasHouse);
                        $('form.login div.h .fa-times').click()
                    }
                },
            });
        };

        let signupCheck = (form) => {
            $.ajax({
                url: 'functions/form_handle.php',
                type: 'POST',
                data: new FormData(form),
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(res) {
                    let loggedIn = res[0].login
                    let user = res[0].username
                    let hasHouse = res[0].hasHouse
                    if (!loggedIn) {
                        $('form.login .err').html('Invalid username or password');
                    } else {
                        updatePage(user, loggedIn, hasHouse);
                        $('form.signup div.h .fa-times').click()
                    }
                },
            });
        };

        let searchHouse = (term) => {
            let sort = $('select#sort').val()
            let type = $('select#type').val()

            $.ajax({
                url: 'functions/search.php?' + $.param({
                    searchTerm: term,
                    sort: sort,
                    type: type
                }),
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(res) {
                    $('div.res div.houses').html('')
                    $('p.no span.found').html(res.length + ' result(s)')
                    res.forEach((house) => {
                        //new HouseOps(house, document.querySelector('div.res div.houses'));
                        display(house, document.querySelector('div.res div.houses'))
                    });
                }

            })
        };
    </script>
</body>

</html>