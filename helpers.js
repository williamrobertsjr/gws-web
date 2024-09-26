// // Swap Home Hero images when hovering over text
// const homeHero = document.getElementById('home-hero');
// const heroLink = document.querySelectorAll('.hero-link');
// if (heroLink) {
//     heroLink[0].addEventListener('mouseover', function() {
//         homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/tool_spread-scaled.jpg')";
//     })
//     heroLink[1].addEventListener('mouseover', function() {
//         homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/home_industries.jpg')";
//     })
//     heroLink[2].addEventListener('mouseover', function() {
//         homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/custom_home.jpg')";
//     })
//     heroLink[3].addEventListener('mouseover', function() {
//         homeHero.style.backgroundImage = "url('/wp-content/uploads/2024/02/illinois-shop-floor-edited.jpg')";
//     })  
// }

document.addEventListener('DOMContentLoaded', function() {
    const homeHero = document.getElementById('home-hero');
    const heroLinks = document.querySelectorAll('.hero-link');

    // Get image URLs from data attributes
    const image1 = homeHero.getAttribute('data-image1');
    const image2 = homeHero.getAttribute('data-image2');
    const image3 = homeHero.getAttribute('data-image3');
    const image4 = homeHero.getAttribute('data-image4');

    // Create an array of images for easier access
    const heroImages = [image1, image2, image3, image4];

    // Set initial background image if available
    if (heroImages[0]) {
        homeHero.style.backgroundImage = `url(${heroImages[0]})`;
    }

    // Add hover event listeners to each hero link
    heroLinks.forEach((link, index) => {
        link.addEventListener('mouseover', function() {
            console.log(link)
            console.log(heroImages[index])
            if (heroImages[index]) { // Check if image exists for this index
                homeHero.style.backgroundImage = `url(${heroImages[index]})`;
            }
        });
    });


});




// Swap Home Services card on hover
const servicesCards = document.querySelectorAll('.services-card')
// const servicesCardFront = document.querySelectorAll('.card-content')
// const servicesCardBack = document.querySelector('.card-overlay')

servicesCards.forEach(card => {
    const cardFront = card.querySelector('.card-content');
    const cardBack = card.querySelector('.card-overlay');

    // Handle mouseover event
    card.addEventListener('mouseover', function() {
        cardFront.classList.add('hidden');
        cardBack.classList.remove('hidden');
    });

    // Handle mouseout event
    card.addEventListener('mouseout', function() {
        cardFront.classList.remove('hidden');
        cardBack.classList.add('hidden');
    });
});

// const footerSubmitButton = document.getElementById('gform_submit_button_29')

// if (footerSubmitButton) {
//     footerSubmitButton.value = 'Sign Up' + '<span>&#x2714;</span>';
//     console.log(footerSubmitButton.value)
// }

