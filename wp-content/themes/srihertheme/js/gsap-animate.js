

document.addEventListener("DOMContentLoaded", function () {
    gsap.registerPlugin(ScrollTrigger);

    function animateText() {
        // Ensure SplitType applies to elements that exist
        let elements = document.querySelectorAll("[animate]");
        if (elements.length === 0) return; // Exit if no elements found

        elements.forEach((el) => {
            let typeSplit = new SplitType(el, {
                types: "lines, words, chars",
                tagName: "span"
            });

            gsap.from(el.querySelectorAll(".line"), {
                y: "100%",
                opacity: 0,
                duration: 0.7,
                ease: "power2.out",
                stagger: 0.1,
                scrollTrigger: {
                    trigger: el,
                    start: "top 80%",
                    end: "bottom 40%",
                    markers: false, // Set to true if you want to debug
                    toggleActions: "restart none none restart" // Ensures animation happens every scroll
                }
            });
        });

        ScrollTrigger.refresh(); // Ensures ScrollTrigger updates
    }

    // Run animation on page load
    animateText();

    // Refresh ScrollTrigger on window resize
    window.addEventListener("resize", () => {
        ScrollTrigger.refresh();
    });
});



document.addEventListener("DOMContentLoaded", function () {
gsap.registerPlugin(ScrollTrigger);

// Wait for images to fully load before initializing GSAP
function initZoomEffect() {
    gsap.utils.toArray(".zoom-image").forEach((img) => {
        gsap.to(img, {
            scale: 1.3, // Adjust the zoom level
            ease: "none",
            scrollTrigger: {
                trigger: img,
                start: "top bottom", // Animation starts when image enters viewport
                end: "bottom top",   // Animation ends when image leaves viewport
                scrub: 2,           // Smooth scrolling effect
            }
        });
    });

    ScrollTrigger.refresh(); // Refresh ScrollTrigger after DOM updates
}

// Run after images load to prevent layout shifts
window.addEventListener("load", initZoomEffect);
});

