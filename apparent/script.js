class HouseOps {
    constructor(house, target) {
        this.display(house, target);
    }

    display = (house, target) => {
        let home = document.createElement('div');
        home.className = 'house';

        let imgHolder = document.createElement('div');
        imgHolder.className = 'image';
        imgHolder.innerHTML =
            '<span class="like"><i class="fas fa-heart"></i></span>' +
            house.img1;
        home.appendChild(imgHolder);

        // let location = document.createElement('p');
        // location.className = 'location';
        // location.innerHTML =
        //     '<i class="fa fa-map-marker" aria-hidden="true"></i> ' + house.loc;
        //home.appendChild(location);

        let houseName = document.createElement('p');
        houseName.className = 'houseName';
        houseName.innerHTML = house.hName;
        home.appendChild(houseName);

        // let houseType = document.createElement('p');
        // houseType.className = 'type';
        // houseType.innerHTML = house.hType;
        //home.appendChild(houseType);

        let rent = document.createElement('span');
        rent.className = 'rent';
        rent.innerHTML =
            '<span style="font-size:14px;opacity:.8;margin-right:3px">Ksh</span>' +
            house.rent.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        home.appendChild(rent);

        let stats = document.createElement('div');
        stats.className = 'houseStats';
        stats.innerHTML =
            '<span class="statH"><i class="fa fa-bed" aria-hidden="true"></i></span><span class="statN">' +
            house.bedR +
            '</span><span class="statH"><i class="fa fa-bath" aria-hidden="true"></i></span><span class="statN">' +
            house.bathR +
            '</span>';
        home.appendChild(stats);

        // let details = document.createElement('span');
        // details.className = 'details';
        // details.innerHTML = 'View Details';
        //home.appendChild(details);

        home.addEventListener('click', () => {
            this.moreDetails(house);

            this.call(house.hName, house.loc, house.hType)

        });
        target.appendChild(home);
    };

    call = (house, loc, type) => {
        let response = [];
        $.ajax({
            url: 'functions/getRelated.php?' +
                $.param({
                    hName: house,
                    location: loc,
                    type: type,
                }),
            type: 'POST',
            dataType: 'JSON',
            contentType: 'false',
            processData: 'false',
            success: function(res) {
                // res.forEach((house) => {
                //     new HouseOps(
                //         house,
                //         document.querySelector(
                //             'div.related div.relatedHouses'
                //         )
                //     );
                // });
                response = res;

            },
        });
        console.log(response)
    }
    smallDisplay = (res) => {
        console.log(res)
    }

    moreDetails = (home) => {
        body.style.overflow = 'hidden';
        let container = document.createElement('div');
        container.className = 'clickedHouse';

        //
        let topbar = document.createElement('div');
        topbar.className = 'topbar';

        let backButton = document.createElement('span');
        backButton.className = 'backButton';
        backButton.innerHTML =
            '<button><i class="fa fa-arrow-left" aria-hidden="true"></i></button>';
        backButton.addEventListener('click', () => {
            this.back(container);
        });

        let call = document.createElement('span');
        call.className = 'call';
        call.innerHTML =
            '<a href="tel:' +
            home.phone +
            '"><button><i class="fa fa-phone" aria-hidden="true"></i>Book Now</button></a>';

        topbar.appendChild(backButton);
        topbar.appendChild(call);
        container.appendChild(topbar);

        let activeImg;
        //
        let imageHolder = document.createElement('div');
        imageHolder.className = 'imageHolder';

        let sideImage = document.createElement('div');
        sideImage.className = 'sideImage';

        let img1 = document.createElement('div');
        img1.className = 'miniImage';
        img1.innerHTML = home.img1;
        img1.addEventListener('click', () => {
            this.changeImg(img1.innerHTML);
            activeImg.style.border = '2px solid rgb(238, 238, 238)';
            activeImg = img1;
            activeImg.style.border = '2px solid #27ae60';
        });
        sideImage.appendChild(img1);

        let img2 = document.createElement('div');
        img2.className = 'miniImage';
        img2.innerHTML = home.img2;
        img2.addEventListener('click', () => {
            this.changeImg(img2.innerHTML);
            activeImg.style.border = '2px solid rgb(238, 238, 238)';
            activeImg = img2;
            activeImg.style.border = '2px solid #27ae60';
        });
        sideImage.appendChild(img2);

        let img3 = document.createElement('div');
        img3.className = 'miniImage';
        img3.innerHTML = home.img3;
        img3.addEventListener('click', () => {
            this.changeImg(img3.innerHTML);

            activeImg.style.border = '2px solid rgb(238, 238, 238)';
            activeImg = img3;
            activeImg.style.border = '2px solid #27ae60';
        });
        sideImage.appendChild(img3);

        activeImg = img1;
        activeImg.style.border = '2px solid #27ae60';
        // let img4 = document.createElement('div');
        // img4.className = 'miniImage';
        // img1.innerHTML = home.img1;
        // sideImage.appendChild(img4);

        imageHolder.appendChild(sideImage);

        let img = document.createElement('div');
        img.className = 'mainImage';
        img.innerHTML = home.img1;
        imageHolder.appendChild(img);
        //
        container.appendChild(imageHolder);

        let houseDetails = document.createElement('div');
        houseDetails.className = 'houseDetails';

        let rentSpan = document.createElement('span');
        rentSpan.className = 'rentSpan';
        rentSpan.innerHTML =
            '<span style="font-size:16px;opacity:.8;margin-right:3px">Ksh</span>' +
            home.rent.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        let houseName = document.createElement('div');
        houseName.className = 'houseName';
        houseName.innerHTML =
            '<span class="name">' +
            home.hName +
            '</span><span class="loc">' +
            home.loc +
            '</span>';

        houseDetails.appendChild(rentSpan);
        houseDetails.appendChild(houseName);
        container.appendChild(houseDetails);

        let details = document.createElement('div');
        details.className = 'details';
        details.innerHTML =
            '<div class="det"><span class="lab">Bedrooms</span><span class="no">' +
            home.bedR +
            '<i class="fa fa-bed" aria-hidden="true"></i></span></div>' +
            '<div class="det"><span class="lab">Bathrooms</span><span class="no">' +
            home.bathR +
            '<i class="fa fa-bath" aria-hidden="true"></i></span></div>';

        details.innerHTML +=
            '<div class="desc"><div class="descL">Description</div><div class="descC">' +
            home.hDesc +
            '</div></div>';

        container.appendChild(details);

        let related = document.createElement('div');
        related.className = 'related';
        related.innerHTML = '<p class="relatedTopic">Related Searches</p>';

        let relatedHouses = document.createElement('div');
        relatedHouses.className = 'relatedHouses';

        related.appendChild(relatedHouses);

        container.appendChild(related);

        // let contact = document.createElement('div');
        // contact.className = 'contact';
        // contact.innerHTML =
        //     '<div><i class="fa fa-user" aria-hidden="true"></i></div><div class="owner">' +
        //     home.fullname +
        //     '</div>';

        // container.appendChild(contact);

        body.appendChild(container);
    };

    back = (item) => {
        body.style.overflow = 'auto';
        body.removeChild(item);
    };

    changeImg = (pic) => {
        document.querySelector('div.mainImage').innerHTML = pic;
    };
}


let display = (house, target) => {
    let home = document.createElement('div');
    home.className = 'house';

    let imgHolder = document.createElement('div');
    imgHolder.className = 'image';
    imgHolder.innerHTML =
        '<span class="like"><i class="fas fa-heart"></i></span>' +
        house.img1;
    home.appendChild(imgHolder);

    // let location = document.createElement('p');
    // location.className = 'location';
    // location.innerHTML =
    //     '<i class="fa fa-map-marker" aria-hidden="true"></i> ' + house.loc;
    //home.appendChild(location);

    let houseName = document.createElement('p');
    houseName.className = 'houseName';
    houseName.innerHTML = house.hName;
    home.appendChild(houseName);

    // let houseType = document.createElement('p');
    // houseType.className = 'type';
    // houseType.innerHTML = house.hType;
    //home.appendChild(houseType);

    let rent = document.createElement('span');
    rent.className = 'rent';
    rent.innerHTML =
        '<span style="font-size:14px;opacity:.8;margin-right:3px">Ksh</span>' +
        house.rent.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    home.appendChild(rent);

    let stats = document.createElement('div');
    stats.className = 'houseStats';
    stats.innerHTML =
        '<span class="statH"><i class="fa fa-bed" aria-hidden="true"></i></span><span class="statN">' +
        house.bedR +
        '</span><span class="statH"><i class="fa fa-bath" aria-hidden="true"></i></span><span class="statN">' +
        house.bathR +
        '</span>';
    home.appendChild(stats);

    // let details = document.createElement('span');
    // details.className = 'details';
    // details.innerHTML = 'View Details';
    //home.appendChild(details);

    home.addEventListener('click', () => {
        moreDetails(house);

        $.ajax({
            url: 'functions/getRelated.php?' +
                $.param({
                    hName: house.hName,
                    location: house.loc,
                    type: house.hType,
                }),
            type: 'POST',
            dataType: 'JSON',
            contentType: 'false',
            processData: 'false',
            success: function(res) {
                res.forEach((house) => {
                    smallDisplay(
                        house,
                        document.querySelector(
                            'div.related div.relatedHouses'
                        )
                    );
                });

            },
        });

    });
    target.appendChild(home);
};

let smallDisplay = (house, target) => {
    let home = document.createElement('div')
    home.className = 'relHouse'

    let imgHolder = document.createElement('div')
    imgHolder.className = 'image'
    imgHolder.innerHTML = house.img1
    home.appendChild(imgHolder)

    let hName = document.createElement('div')
    hName.className = 'houseName'
    hName.innerHTML = '<p>' + house.hName + '</p><i class="fa fa-heart-o" aria-hidden="true"></i>'
    home.appendChild(hName)

    let specs = document.createElement('div')
    specs.className = 'specs'
    specs.innerHTML = '<span class="statH"><i class="fa fa-bed" aria-hidden="true"></i></span><span class="statN">' +
        house.bedR +
        '</span><span class="statH"><i class="fa fa-bath" aria-hidden="true"></i></span><span class="statN">' +
        house.bathR +
        '</span>'
    home.appendChild(specs)

    let rent = document.createElement('span');
    rent.className = 'relRent';
    rent.innerHTML =
        '<span style="font-size:14px;opacity:.8;margin-right:3px">Ksh</span>' +
        house.rent.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    home.appendChild(rent)

    home.addEventListener('click', () => {
        if (document.contains(document.querySelector('div.clickedHouse'))) {
            document.querySelector('div.clickedHouse').remove()
        }

        moreDetails(house)
        let houseTypes = ['Bedsitter', 'Single Room', '2 Bedroom', '3 Bedroom', '4 Bedroom']
        $.ajax({
            url: 'functions/getRelated.php?' +
                $.param({
                    hName: house.hName,
                    location: house.loc,
                    type: houseTypes[house.hType],
                }),
            type: 'POST',
            dataType: 'JSON',
            contentType: 'false',
            processData: 'false',
            success: function(res) {
                res.forEach((house) => {
                    smallDisplay(
                        house,
                        document.querySelector(
                            'div.related div.relatedHouses'
                        )
                    );
                });

            },
        });
    })
    target.appendChild(home)
}

let moreDetails = (home) => {
    body.style.overflow = 'hidden';
    let container = document.createElement('div');
    container.className = 'clickedHouse';

    //
    let topbar = document.createElement('div');
    topbar.className = 'topbar';

    let backButton = document.createElement('span');
    backButton.className = 'backButton';
    backButton.innerHTML =
        '<button><i class="fa fa-arrow-left" aria-hidden="true"></i></button>';
    backButton.addEventListener('click', () => {
        back(container);
    });

    let call = document.createElement('span');
    call.className = 'call';
    call.innerHTML =
        '<a href="tel:' +
        home.phone +
        '"><button><i class="fa fa-phone" aria-hidden="true"></i>Book Now</button></a>';

    topbar.appendChild(backButton);
    topbar.appendChild(call);
    container.appendChild(topbar);

    let activeImg;
    //
    let imageHolder = document.createElement('div');
    imageHolder.className = 'imageHolder';

    let sideImage = document.createElement('div');
    sideImage.className = 'sideImage';

    let img1 = document.createElement('div');
    img1.className = 'miniImage';
    img1.innerHTML = home.img1;
    img1.addEventListener('click', () => {
        changeImg(img1.innerHTML);
        activeImg.style.border = '2px solid rgb(238, 238, 238)';
        activeImg = img1;
        activeImg.style.border = '2px solid #27ae60';
    });
    sideImage.appendChild(img1);

    let img2 = document.createElement('div');
    img2.className = 'miniImage';
    img2.innerHTML = home.img2;
    img2.addEventListener('click', () => {
        changeImg(img2.innerHTML);
        activeImg.style.border = '2px solid rgb(238, 238, 238)';
        activeImg = img2;
        activeImg.style.border = '2px solid #27ae60';
    });
    sideImage.appendChild(img2);

    let img3 = document.createElement('div');
    img3.className = 'miniImage';
    img3.innerHTML = home.img3;
    img3.addEventListener('click', () => {
        changeImg(img3.innerHTML);

        activeImg.style.border = '2px solid rgb(238, 238, 238)';
        activeImg = img3;
        activeImg.style.border = '2px solid #27ae60';
    });
    sideImage.appendChild(img3);

    activeImg = img1;
    activeImg.style.border = '2px solid #27ae60';

    imageHolder.appendChild(sideImage);

    let img = document.createElement('div');
    img.className = 'mainImage';
    img.innerHTML = home.img1;
    imageHolder.appendChild(img);
    //
    container.appendChild(imageHolder);

    let houseDetails = document.createElement('div');
    houseDetails.className = 'houseDetails';

    let rentSpan = document.createElement('span');
    rentSpan.className = 'rentSpan';
    rentSpan.innerHTML =
        '<span style="font-size:16px;opacity:.8;margin-right:3px">Ksh</span>' +
        home.rent.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    let houseName = document.createElement('div');
    houseName.className = 'houseName';
    houseName.innerHTML =
        '<span class="name">' +
        home.hName +
        '</span><span class="loc">' +
        home.loc +
        '</span>';

    houseDetails.appendChild(rentSpan);
    houseDetails.appendChild(houseName);
    container.appendChild(houseDetails);

    let details = document.createElement('div');
    details.className = 'details';
    details.innerHTML =
        '<div class="det"><span class="lab">Bedrooms</span><span class="no">' +
        home.bedR +
        '<i class="fa fa-bed" aria-hidden="true"></i></span></div>' +
        '<div class="det"><span class="lab">Bathrooms</span><span class="no">' +
        home.bathR +
        '<i class="fa fa-bath" aria-hidden="true"></i></span></div>';

    details.innerHTML +=
        '<div class="desc"><div class="descL">Description</div><div class="descC">' +
        home.hDesc +
        '</div></div>';

    container.appendChild(details);

    let related = document.createElement('div');
    related.className = 'related';
    related.innerHTML = '<p class="relatedTopic">Related Searches</p>';

    let relatedHouses = document.createElement('div');
    relatedHouses.className = 'relatedHouses';

    related.appendChild(relatedHouses);

    container.appendChild(related);

    body.appendChild(container);
};

let back = (item) => {
    body.style.overflow = 'auto';
    body.removeChild(item);
};

let changeImg = (pic) => {
    document.querySelector('div.mainImage').innerHTML = pic;
};


$(document).ready(function() {
    setTimeout(() => {
        $('div.loader').fadeOut('fast');
    }, 1000);
});

const body = document.querySelector('body');

//nav bar
const navbar = document.querySelector('nav');
const mobileNav = document.querySelector('div.mobileNav');
const burger = document.querySelector('div.burger');
const burgerLines = document.querySelectorAll('div.burger div');

let navFlag = false;
burger.addEventListener('click', () => {
    if (!navFlag) {
        navFlag = true;
        mobileNav.style.transform = 'translateX(0)';
        burgerLines[1].style.opacity = 0;
        burgerLines[0].style.transform = 'rotate(43deg)';
        burgerLines[2].style.transform = 'rotate(-43deg)';
    } else {
        navFlag = false;
        mobileNav.style.transform = 'translateX(100%)';
        burgerLines[1].style.opacity = 1;
        burgerLines[0].style.transform = 'rotate(0)';
        burgerLines[2].style.transform = 'rotate(-0)';
    }
});
window.addEventListener('scroll', () => {
    if (navFlag) {
        navFlag = false;
        mobileNav.style.transform = 'translateX(100%)';
    }
});

window.onscroll = () => {
    if (document.documentElement.scrollTop > 300) {
        navbar.style.background = '#fff';
    } else {
        navbar.style.background = 'rgba(255, 255, 255, 0.637)';
    }
};

//searchbar
let searchBar = document.querySelector('div.searchBar');
let searchBarInput = document.querySelector('input#txt');
let searchBarInput2 = document.querySelector('input#txt2');

const search = document.querySelector('div.searchResults');
const searched = document.querySelector('div.searchResults div.res div.houses');
const exit = document.querySelector('div.searchResults p.no i');

let searchFlag = false;
searchBarInput.addEventListener('keyup', () => {
    searchFlag = true;

    searchBarInput2.focus();
    searchBarInput2.value = searchBarInput.value;

    window.scrollTo(0, 470);

    body.style.overflow = 'hidden';
    body.style.position = 'fixed';
    body.style.top = 470;
    search.style.display = 'block';
});
searchBarInput2.addEventListener('keyup', () => {
    searchBarInput.value = searchBarInput2.value;
});

exit.addEventListener('click', () => {
    searchFlag = false;
    body.style.overflow = 'auto';
    body.style.position = 'relative';
    search.style.display = 'none';
    //searchBar.style.zIndex = '1';
});

/**
 * modal functionality
 */
let modalBody = document.querySelector('.modal-body');
let modalClose = document.querySelectorAll('.modal-close');

function modals(type) {
    if (type == 1) {
        document.querySelector('div.login-modal').style.display = 'block';
    } else if (type == 2) {
        document.querySelector('div.signup-modal').style.display = 'block';
    } else if (type == 3) {
        document.querySelector('div.property-modal').style.display = 'block';
    }

    modalBody.style.visibility = 'visible';
    modalBody.style.opacity = 1;
    body.style.overflow = 'hidden';

    modalClose.forEach((btn) => {
        btn.addEventListener('click', () => {
            btn.parentNode.parentNode.parentNode.style.display = 'none';
            modalBody.style.visibility = 'hidden';
            modalBody.style.opacity = 0;
            body.style.overflow = 'auto';
        });
    });
}

let addProperty = (logged) => {
    if (logged) {
        modals(3);
    } else {
        modals(1);
    }
};

let userModal = document.querySelector('div.profile');
let userPop = () => {
    userModal.style.transform = 'translateX(0)';
    userModal.addEventListener('mouseover', () => {
        userModal.style.transform = 'translateX(0)';
    });
    userModal.addEventListener('mouseout', () => {
        userModal.style.transform = 'translateX(110%)';
    });
};

//alert popups
let alerts = [{
        icon: '<i class="fa fa-check-circle" aria-hidden="true"></img>',
        title: 'Success',
        message: 'Welcome to Apparent. We pride in making housing easy.',
    },
    {
        icon: '<i class="fa fa-times-circle" aria-hidden="true"></i>',
        title: 'Error',
        message: 'There was an error. Try again',
    },
];

let displayAlert = (type) => {
    document.querySelector('div.alertContainer').style.display = 'block';

    if (type) {
        document.querySelector('div.alertContainer').style.background =
            '#44d787';

        document.querySelector('div.alertContainer div.icon').appendChild =
            alerts[0].icon;
        document.querySelector(
            'div.alertContainer div.more div.t'
        ).appendChild = alerts[0].title;
        document.querySelector(
            'div.alertContainer div.more div.tSub'
        ).appendChild = alerts[0].message;
    } else {
        document.querySelector('div.alertContainer').style.background =
            '#f9461d';

        document.querySelector('div.alertContainer div.icon').appendChild =
            alerts[1].icon;
        document.querySelector(
            'div.alertContainer div.more div.t'
        ).appendChild = alerts[1].title;
        document.querySelector(
            'div.alertContainer div.more div.tSub'
        ).appendChild = alerts[1].message;
    }
};

let closeAlert = (me) => {
    me.parentNode.parentNode.style.display = 'none';
};

//footer
let footer = document.querySelector('footer');
let date = new Date();
footer.innerHTML =
    '&copy ' + date.getFullYear() + '. APPARENT. ALL RIGHTS RESERVED.';

/*
    nav bar refresh
*/
let updatePage = (user, loggedIn, hasHouse) => {
    if (!loggedIn) {
        $('div.logCheck').html(
            '<span class="navlink login" onclick="formPop(0)"><a href="#">Login</a></span><span class="navlink signup" onclick="formPop(1)"><a href="#">Sign Up</a></span>'
        );
    } else {
        $('div.logCheck').html(
            '<span class="user" onmouseover="userPop()">Hi, ' +
            user +
            ' <i class="fa fa-user-circle" aria-hidden="true"></i></span>'
        );
    }

    if (!hasHouse) {
        $('span.property').html(
            '<a href="#" onclick="addProperty(' +
            loggedIn +
            ')">List Property</a>'
        );
    } else {
        $('span.property').html(
            '<span class="navlink s property"><a href="landlord/main.php">View My Property</a></span>'
        );
    }
};