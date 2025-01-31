const swiper = new Swiper('.swiper', {
  direction: 'horizontal',
  slidesPerView: '3',
  grabCursor: false,
  scrollbar: {
      el: ".swiper-scrollbar",
      hide: true,
    },
  keyboard: {
    enabled: true
  },
  mousewheel: {
    enabled: true,
    forceToAxis: true
  }
}
);

// Swiper used on Series Pages
const seriesSwiper = new Swiper('.swiper', {
  direction: 'horizontal',
  slidesPerView: '1',
  scrollbar: {
      el: ".swiper-scrollbar",
      hide: true,
    },
  keyboard: {
    enabled: true
  },
  mousewheel: {
    enabled: true,
    forceToAxis: true
  },
  navigation: {
    nextEl: '.nextEl',
    prevEl: '.prevEl',
  },
  breakpoints: {
    // when window width is >= 767px
    769: {
      slidesPerView: 2,
    },
    // when window width is >= 1200px
    1200: {
      slidesPerView: 3,
    }
  }
}
);

const subtypeSwiper = new Swiper('.subtypesSwiper', {
  // Optional parameters
  loop: true,
  slidesPerView: 1,
  grabCursor: true,

  // Navigation arrows
  navigation: {
    nextEl: '.nextEl',
    prevEl: '.prevEl',
  },
  breakpoints: {
    // when window width is >= 767px
    769: {
      slidesPerView: 2,
    },
    // when window width is >= 1200px
    1200: {
      slidesPerView: 3,
    }
  }

});