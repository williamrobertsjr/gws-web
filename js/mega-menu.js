const megaMenu = document.querySelector('#mega-menu-max_mega_menu_1');
const megaMenuLinks = document.querySelectorAll('#mega-menu-max_mega_menu_1 > li');
const headerTransparent = document.getElementsByClassName('header-transparent')[0];

// Add black background when menu links are clicked
for (const link of megaMenuLinks) {
    link.addEventListener('click', function() {
        headerTransparent.classList.add('bg-black');
    });
}

// Remove black background when clicking outside the menu
document.addEventListener('click', function(event) {
    let isClickInsideMenu = megaMenu.contains(event.target);
    if (!isClickInsideMenu) {
        headerTransparent.classList.remove('bg-black');
    }
});

// PRODUCTS MENU VARIABLE DEFINITIONS
const productsButtons = document.querySelectorAll('.products-menu-btn');
const millingLinks = document.querySelector('#mega-menu-item-133115');
const specialtyLinks = document.querySelector('#mega-menu-item-133117');
const holemakingLinks = document.querySelector('#mega-menu-item-133116');
const threadingLinks = document.querySelector('#mega-menu-item-133118');
const insertsLinks = document.querySelector('#mega-menu-item-133119');
const customSpecialtyLinks = document.querySelector('#mega-menu-item-134115');
const allLinks = [millingLinks, specialtyLinks, holemakingLinks, threadingLinks, insertsLinks, customSpecialtyLinks];

const menuRightLinks = document.querySelectorAll('.menu-right-links');
const millingRight = document.querySelector('.menu-right-links.milling')
const specialtyRight = document.querySelector('.menu-right-links.specialty')
const holemakingRight = document.querySelector('.menu-right-links.holemaking')
const threadingRight = document.querySelector('.menu-right-links.threading')
const insertsRight = document.querySelector('.menu-right-links.inserts')

let menuRightImg = document.querySelector('.menu-right-img').style
let menuRightCustomImg = document.querySelector('.menu-right-custom-img').style
let menuRightImgDefault = "url('/wp-content/uploads/2024/01/all_tools_menu_menu.jpg')";
let menuRightCustomImgDefault = "url('/wp-content/uploads/2024/06/custom_specialty_menu.jpg')"
const millingImg = "url('/wp-content/uploads/2024/01/milling_menu.jpg')"
const specialtyImg = "url('/wp-content/uploads/2024/01/specialty_menu.jpg')"
const holemakingImg = "url('/wp-content/uploads/2024/01/holemaking_menu.jpg')"
const threadingImg = "url('/wp-content/uploads/2024/01/threading_menu.jpg')"
const insertsImg = "url('/wp-content/uploads/2024/01/inserts_menu.jpg')"
const customSpecialtyImg = "url('/wp-content/uploads/2024/06/custom_specialty_menu.jpg')"


menuRightImg.backgroundImage = menuRightImgDefault
menuRightCustomImg.backgroundImage = menuRightCustomImgDefault

// Hide all menu right subtype links to start
menuRightLinks.forEach(link => {
    link.classList.add('hide-menu');
});
// Hide all subtype links to start
allLinks.forEach(link => {
    link.classList.add('hide-menu');
});

for (const btn of productsButtons) {
    btn.addEventListener('click', function(event) {
        let heading = btn.firstChild.nextSibling;
        let icon = heading.firstChild.nextSibling;
        console.log(heading)
        // Determine which link should be shown based on the heading text
        let linkToShow;
        let menuRightToShow
        let rightImgToShow
        if (heading.classList.contains('standardMilling')) {
            linkToShow = millingLinks;
            menuRightToShow = millingRight
            menuRightImg.backgroundImage = millingImg
        } else if (heading.classList.contains('standardSpecialty')) {
            linkToShow = specialtyLinks;
            menuRightToShow = specialtyRight
            menuRightImg.backgroundImage  = specialtyImg
        } else if (heading.classList.contains('standardHolemaking')) {
            linkToShow = holemakingLinks;
            menuRightToShow = holemakingRight;
            menuRightImg.backgroundImage  = holemakingImg
        } else if (heading.classList.contains('standardThreading')) {
            linkToShow = threadingLinks;
            menuRightToShow = threadingRight;
            menuRightImg.backgroundImage  = threadingImg
        } else if (heading.classList.contains('standardInserts')) {
            linkToShow = insertsLinks;
            menuRightToShow = insertsRight;
            menuRightImg.backgroundImage  = insertsImg
        } else if (heading.classList.contains('customSpecialty')) {
            console.log('worked')
            linkToShow = customSpecialtyLinks;
            menuRightCustomImg.backgroundImage  = customSpecialtyImg
        } else {
            return
        }

       // Check if the corresponding link is already shown
       if (linkToShow && !linkToShow.classList.contains('hide-menu')) {
            // If shown, hide it and remove the classes
            linkToShow.classList.add('hide-menu');
            if(menuRightToShow) {
                menuRightToShow.classList.add('hide-menu');
            }
            icon.classList.remove('rotate-45', 'text-pale-blue');
            heading.classList.remove('text-pale-blue');
            menuRightImg.backgroundImage  = menuRightImgDefault
            menuRightCustomImg.backgroundImage = menuRightCustomImgDefault
            
        } else {
            // menuRightImg.backgroundImage  = menuRightImgDefault
            // If not shown, first hide all links and remove classes from all
            allLinks.forEach(link => {
                link.classList.add('hide-menu');
            });
            // Hide all menu right subtype links to start
            menuRightLinks.forEach(link => {
                link.classList.add('hide-menu');
            });

            productsButtons.forEach(button => {
                let otherHeading = button.firstChild.nextSibling;
                let otherIcon = otherHeading.firstChild.nextSibling;
                otherIcon.classList.remove('rotate-45', 'text-pale-blue');
                otherHeading.classList.remove('text-pale-blue');
            });

            // Then, show the clicked link and add the classes
            if (linkToShow) {
                linkToShow.classList.remove('hide-menu');
                if(menuRightToShow) {
                    menuRightToShow.classList.remove('hide-menu');
                }
                icon.classList.add('rotate-45', 'text-pale-blue');
                heading.classList.add('text-pale-blue');
            }
        }
    });
}


const toolCategories = {
    "Milling": {
        "Standard": {
            links: document.querySelector('#mega-menu-item-133115'),
            rightLinks: "" ,
            img: ""
        },
        "Custom": {}
    },
    "Holemaking": {
        "Standard": {
            links: "",
            rightLinks: "" ,
            img: ""
        },
        "Custom": {}
    },
    "Specialty": {
        "Standard": {
            links: "",
            rightLinks: "" ,
            img: ""
        },
        "Custom": {}
    },
    "Inserts": {
        "Standard": {
            links: "",
            rightLinks: "" ,
            img: ""
        },
        "Custom": {}
    },
    "Threading": {
        "Standard": {
            links: "",
            rightLinks: "" ,
            img: ""
        },
        "Custom": {}
    }
}