// Swap Home Hero images when hovering over text
const homeHero = document.getElementById('home-hero');
const heroLink = document.querySelectorAll('.hero-link');
if (heroLink) {
    heroLink[0].addEventListener('mouseover', function() {
        // homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/tool_spread-scaled.jpg')";
        homeHero.style.backgroundImage = "url('https://www.gwstoolgroup.com/wp-content/uploads/2024/08/imts_booth_home.jpg')";
    })
    heroLink[1].addEventListener('mouseover', function() {
        homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/home_industries.jpg')";
    })
    heroLink[2].addEventListener('mouseover', function() {
        homeHero.style.backgroundImage = "url('/wp-content/uploads/2023/12/custom_home.jpg')";
    })
    heroLink[3].addEventListener('mouseover', function() {
        homeHero.style.backgroundImage = "url('/wp-content/uploads/2024/02/illinois-shop-floor-edited.jpg')";
    })  
}

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