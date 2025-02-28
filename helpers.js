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
    const heroTagLine = document.querySelector('.hero-tagline');
    const heroTagLineBig = document.querySelector('.hero-tagline-big');

    // Get image URLs from data attributes
    const image1 = homeHero.getAttribute('data-image1');
    const image2 = homeHero.getAttribute('data-image2');
    const image3 = homeHero.getAttribute('data-image3');
    const image4 = homeHero.getAttribute('data-image4');
    const image5 = homeHero.getAttribute('data-image5');

    // Get taglines from data attributes
    const tagline1 = homeHero.getAttribute('data-tagline1');
    const tagline2 = homeHero.getAttribute('data-tagline2');
    const tagline3 = homeHero.getAttribute('data-tagline3');
    const tagline4 = homeHero.getAttribute('data-tagline4');
    const tagline5 = homeHero.getAttribute('data-tagline5');

    const taglineBig1 = homeHero.getAttribute('data-tagline-big1');
    const taglineBig2 = homeHero.getAttribute('data-tagline-big2');
    const taglineBig3 = homeHero.getAttribute('data-tagline-big3');
    const taglineBig4 = homeHero.getAttribute('data-tagline-big4');
    const taglineBig5 = homeHero.getAttribute('data-tagline-big5');

    // Create arrays for easier access
    const heroImages = [image1, image2, image3, image4, image5];
    const heroTaglines = [tagline1, tagline2, tagline3, tagline4, tagline5];
    const heroTaglinesBig = [taglineBig1, taglineBig2, taglineBig3, taglineBig4, taglineBig5];

    // Set initial background image if available
    if (heroImages[0]) {
        homeHero.style.backgroundImage = `url(${heroImages[2]})`;
    }
    // Add hover event listeners to each hero link
    heroLinks.forEach((link, index) => {
        link.addEventListener('mouseover', function() {
            console.log(link)
            console.log(heroImages[index])
            console.log(heroTaglines[index])
            console.log(heroTaglinesBig[index])
            if (heroImages[index]) { // Check if image exists for this index
                homeHero.style.backgroundImage = `url(${heroImages[index]})`;
                console.log('hello');
                heroTagLine.textContent = heroTaglines[index];
                heroTagLineBig.textContent = heroTaglinesBig[index];
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

  
  
  
  

