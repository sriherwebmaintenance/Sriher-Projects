$(window).load(function () {
    $(".preloader").fadeOut(function () {
    //  $(".centerLogo").addClass('show-logo');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    AOS.init({
        mirror: true,
        offset: 200, // Adjust if needed
    });
});





$(document).ready(function () {

    // navButton=document.querySelector('.toggler')
    // navMenu=document.querySelector('#mega-menu-wrap-header_menu')
    // arrow=document.querySelector('.toggler')
    // navButton.addEventListener('click', ()=>{
    //     $(navMenu).toggleClass('open');
    //     $(arrow).toggleClass('rotate')
    //     $(navTopMenu).hide()
    //     $(topArrow).removeClass('top-rotate')
    // });
    navButton=document.querySelector('.toggler')
    navMenu=document.querySelector('#menu-mobileheadermenu')
    arrow=document.querySelector('.toggler')
    navButton.addEventListener('click', ()=>{
        $(navMenu).toggleClass('open');
        $(arrow).toggleClass('rotate')
        $(navTopMenu).hide()
        $(topArrow).removeClass('top-rotate')
    });
    // const menuItems = document.querySelectorAll('#menu-mobileheadermenu');
    //     menuItems.forEach(item => {
    //         const link = item.querySelector('.menu-item-has-children');
    //         const subMenu = item.querySelector('.sub-menu');
            
    //         if (link && subMenu) {
    //             link.addEventListener('click', (e) => {
    //                 e.preventDefault();
    //                 $(subMenu).slideToggle();
    //                 item.classList.toggle('active');
    //             });
    //         }
    // });
    // const menuItemsWithChildren = document.querySelectorAll('#menu-mobileheadermenu .menu-item-has-children');
    // menuItemsWithChildren.forEach(item => {
    //     const link = item.querySelector('a');
    //     const subMenu = item.querySelector('.sub-menu');
        
    //     // Create and append a toggle button
    //     const toggleBtn = document.createElement('span');
    //     toggleBtn.className = 'submenu-toggle';
    //     link.after(toggleBtn);
        
    //     toggleBtn.addEventListener('click', (e) => {
    //         e.preventDefault();
    //         e.stopPropagation();
    //         $(subMenu).slideToggle();
    //         item.classList.toggle('active');
    //     });
    // });
    const menuItemsWithChildren = document.querySelectorAll('#menu-mobileheadermenu .menu-item-has-children');
    menuItemsWithChildren.forEach(item => {
        const link = item.querySelector('a');
        const subMenu = item.querySelector('.sub-menu');
        
        // Initially hide all submenus
        if(subMenu) {
            $(subMenu).hide();
        }
        
        // Create and append a toggle button
        const toggleBtn = document.createElement('span');
        toggleBtn.className = 'submenu-toggle';
        link.after(toggleBtn);
        
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            $(subMenu).slideToggle();
            item.classList.toggle('active');
        });
    });
    navTopButton=document.querySelector('.top-toggler')
    navTopMenu=document.querySelector('.top-menu-items')
    topArrow=document.querySelector('.top-toggler')
    navTopButton.addEventListener('click', ()=>{
        $(navTopMenu).slideToggle()
        $(topArrow).toggleClass('top-rotate')
        $(navMenu).removeClass('open');
    });

    searchButton=document.querySelector('.searchbutton')
    searchForm=document.querySelector('.expandright')
    nav=document.querySelector('.menu-items')
    overlay=document.querySelector('.overlay')

    searchButton.addEventListener('click', ()=>{
        $(searchForm).toggleClass('expand')
        $(nav).toggleClass('open')
        $(overlay).toggle()
    });
    overlay.addEventListener('click', ()=>{
        $(searchForm).removeClass('expand')
        $(nav).removeClass('open')
        $(overlay).hide()
    });
    

    $('.gallery-content').magnificPopup({
        type:'inline',
        midClick: true,
        delegate: 'a',
        gallery: {
            enabled: true
        },
    });
});

document.addEventListener("mousemove", parallax);
    function parallax(event) {
    this.querySelectorAll(".parallax-wrap span").forEach((shift) =>
     {
        const position = shift.getAttribute("value");
        const x = (window.innerWidth - event.pageX * position) / 90;
        const y = (window.innerHeight - event.pageY * position) / 90;

        shift.style.transform = `translateX(${x}px) translateY(${y}px)`;
    });
}

// jQuery(document).ready(function ($) {
//     $(".toggler").click(function () {
//         $("#menu-mobileheadermenu").slideToggle();
//         $(this).toggleClass("open");
//     });
// });