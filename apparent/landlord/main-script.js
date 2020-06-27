let scrollmark = document.querySelector('div.scrollmark');
let navlinks = document.querySelectorAll('div.navlinks');
let mainDivs = document.querySelectorAll('main div.innerMain>div');
let n = 0;

navlinks[0].click();
let temp = navlinks[0];
let tempDiv = mainDivs[0];
temp.style.color = 'rgb(17, 169, 118)';
tempDiv.style.zIndex = 1;

navlinks.forEach((link) => {
    link.id = n;
    link.addEventListener('click', () => {
        if (window.innerWidth > 500) {
            scrollmark.style.top = 81 + link.id * 45 + 'px';
        }
        temp.style.color = 'inherit';
        tempDiv.style.zIndex = 0;
        temp = link;
        tempDiv[n].style.zIndex = 1;
        link.style.color = 'rgb(17, 169, 118)';
    });
    n++;
});

//burger
let burger = document.querySelector('div.burger');
let burgerLines = document.querySelectorAll('div.burger div');
let burgerFlag = false;

burger.addEventListener('click', () => {
    if (!burgerFlag) {
        burgerFlag = true;
        burgerLines[1].style.opacity = '0';
        burgerLines[2].style.transform = 'rotate(45deg)';
        burgerLines[0].style.transform = 'rotate(-45deg)';
    } else {
        burgerFlag = false;
        burgerLines[1].style.opacity = '1';
        burgerLines[2].style.transform = 'rotate(0)';
        burgerLines[0].style.transform = 'rotate(0)';
    }
});

//profile
let userProfile = document.querySelector('div.userProfile');
let userIcon = document.querySelector('div.icons>i');

userIcon.addEventListener('mouseover', () => {
    userProfile.style.transform = 'translate(0)';
    userProfile.addEventListener('mouseover', () => {
        userProfile.style.transform = 'translate(0)';
    });
    userProfile.addEventListener('mouseout', () => {
        userProfile.style.transform = 'translate(110%)';
    });
});
userIcon.addEventListener('mouseout', () => {
    userProfile.style.transform = 'translate(110%)';
});

//close image alert
let closeAlert = (me) => {
    me.parentNode.parentNode.style.display = 'none';
};