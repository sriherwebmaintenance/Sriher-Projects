(function () {
  "use strict";
  window.KBTabs = {
    setupTabs() {
      const tabs = document.querySelectorAll(".kt-tabs-wrap");

      tabs.forEach(tab => {
        tab.querySelectorAll(":scope > .kt-tabs-title-list").forEach(titleList => {
          titleList.setAttribute("role", "tablist");
        });
        tab.querySelectorAll(":scope > .kt-tabs-content-wrap > .kt-tab-inner-content").forEach(content => {
          content.setAttribute("role", "tabpanel");
          content.setAttribute("aria-hidden", "true");
        });

        tab.querySelectorAll(":scope > .kt-tabs-title-list li a").forEach(link => {
          const listItem = link.parentElement;
          const id = listItem.getAttribute("id");
          const isActive = listItem.classList.contains("kt-tab-title-active");

          listItem.setAttribute("role", "presentation");
          link.setAttribute("role", "tab");
          link.setAttribute("aria-selected", isActive ? "true" : "false");
          link.setAttribute("tabindex", isActive ? "0" : "-1");

          const dataTab = link.getAttribute("data-tab");
          const content = tab.querySelector(`:scope > .kt-tabs-content-wrap > .kt-inner-tab-${dataTab}`);

          content.setAttribute("aria-labelledby", id);
          content.setAttribute("aria-hidden", isActive ? "false" : "true");

          if (isActive) {
            content.style.display = "block";
          }
        });

        tab.querySelectorAll(":scope > .kt-tabs-title-list li").forEach(listItem => {
          listItem.addEventListener("keydown", function (event) {
            switch (event.which) {
              case 37:
                listItem.previousElementSibling ? listItem.previousElementSibling.querySelector("a").click() : listItem.parentElement.querySelector("li:last-of-type > a").click();
                break;
              case 39:
                listItem.nextElementSibling ? listItem.nextElementSibling.querySelector("a").click() : listItem.parentElement.querySelector("li:first-of-type > a").click();
                break;
            }
          });
        });

        const resizeEvent = new Event("resize");
        window.dispatchEvent(resizeEvent);
      });

      const tabLinks = document.querySelectorAll(".kt-tabs-title-list li a");

      tabLinks.forEach(link => {
        link.addEventListener("click", function (event) {
          event.preventDefault();
          const dataTab = link.getAttribute("data-tab");
          const parentTab = link.closest(".kt-tabs-wrap");
          window.KBTabs.setActiveTab(parentTab, dataTab);
        });
      });

      const createAccordionTabs = document.querySelectorAll(".kt-create-accordion");
-
      createAccordionTabs.forEach(accordion => {
        accordion.querySelectorAll(":scope > .kt-tabs-title-list .kt-title-item").forEach(titleItem => {
          const dataTab = titleItem.querySelector("a").getAttribute("data-tab");
          const classes = titleItem.classList;
          const parentTab = titleItem.closest(".kt-tabs-wrap");
          const contentWrap = parentTab.querySelector(":scope > .kt-tabs-content-wrap");
          const accordionTitle = window.document.createElement("div");

          accordionTitle.className = [...classes].concat(["kt-tabs-accordion-title", "kt-tabs-accordion-title-" + dataTab]).join(" ");
          accordionTitle.innerHTML = titleItem.innerHTML;

          contentWrap.insertBefore(accordionTitle, contentWrap.querySelector(":scope > .kt-inner-tab-" + dataTab));
          contentWrap.querySelector(":scope > .kt-tabs-accordion-title-" + dataTab + "  a").removeAttribute("role");
          contentWrap.querySelector(":scope > .kt-tabs-accordion-title-" + dataTab + "  a").removeAttribute("tabindex");
        });
      });

      const accordionLinks = document.querySelectorAll(".kt-tabs-accordion-title a");

      accordionLinks.forEach(link => {
        const dataTab = link.getAttribute("data-tab");
        const listItem = link.parentElement;
        const parentTab = listItem.closest(".kt-tabs-wrap");
        const otherTabs = parentTab.querySelectorAll(".kt-tabs-content-wrap .kt-title-item");
        const content = parentTab.querySelector(":scope > .kt-tabs-content-wrap > .kt-inner-tab-" + dataTab);
        const otherContents = parentTab.querySelectorAll(".kt-tabs-content-wrap > .wp-block-kadence-tab");
        link.addEventListener("click", function (event) {
          event.preventDefault();

          if (listItem.classList.contains("kt-tab-title-active")) {
            parentTab.classList.remove("kt-active-tab-" + dataTab);
            listItem.classList.replace("kt-tab-title-active", "kt-tab-title-inactive");
            content.style.display = "none";
          } else {
            otherTabs.forEach(otherTab => {
                otherTab.classList.replace("kt-tab-title-active", "kt-tab-title-inactive");
            });
            otherContents.forEach(otherContent => {
                otherContent.style.display = "none";
            });
            parentTab.classList.add("kt-active-tab-" + dataTab);
            listItem.classList.replace("kt-tab-title-inactive", "kt-tab-title-active");
            content.style.display = "block";
          }

          const resizeEvent = new Event("resize");
          window.dispatchEvent(resizeEvent);

          const openEvent = new Event("kadence-tabs-open");
            window.dispatchEvent(openEvent);
          });
      });

      window.KBTabs.setActiveWithHash();
    },

    setActiveWithHash() {
      if (window.location.hash == "") return;

      const titleItem = window.document.querySelector(window.location.hash + ".kt-title-item");

      if (!titleItem) return;

      const anchor = window.document.querySelector("#" + window.location.hash.substring(1));
      const dataTab = anchor.querySelector("a").getAttribute("data-tab");
      const parentTab = anchor.closest(".kt-tabs-wrap");

      window.KBTabs.setActiveTab(parentTab, dataTab);
    },

    isMobileSize() {
      return 767 >= window.innerWidth;
    },

    isTabletSize() {
      return 767 < window.innerWidth && 1024 >= window.innerWidth;
    },

    setActiveTab(tab, tabName, focus = true) {
      const activeLink = tab.querySelector(":scope > .kt-tabs-title-list > li.kt-tab-title-active a");
      const activeListItem = tab.querySelector(":scope > .kt-tabs-title-list > li.kt-tab-title-active");
      activeListItem.classList.replace("kt-tab-title-active", "kt-tab-title-inactive");
      activeLink.setAttribute("tabindex", "-1");
      activeLink.setAttribute("aria-selected", "false");
      tab.className = tab.className.replace(/\bkt-active-tab-\S+/g, "kt-active-tab-" + tabName);

      const newLink = tab.querySelector(":scope > .kt-tabs-title-list > li.kt-title-item-" + tabName + " a");
      const newListItem = tab.querySelector(":scope > .kt-tabs-title-list > li.kt-title-item-" + tabName);

      newListItem.classList.replace("kt-tab-title-inactive", "kt-tab-title-active");
      newLink.setAttribute("tabindex", "0");
      newLink.setAttribute("aria-selected", "true");

      tab.querySelectorAll(":scope > .kt-tabs-content-wrap > .kt-tab-inner-content").forEach(content => {
        content.style.display = "none";
      });

      const newContent = tab.querySelector(":scope > .kt-tabs-content-wrap > .kt-inner-tab-" + tabName);
      newContent.style.display = "block";

      if (focus) {
        newLink.focus();
      }

      window.KBTabs.setAriaAttributesForTabs(tab, tabName);

      const resizeEvent = new Event("resize");
      window.dispatchEvent(resizeEvent);

      const openEvent = new Event("kadence-tabs-open");
      window.dispatchEvent(openEvent);
    },

    setAriaAttributesForTabs(tab, tabName) {
      tab.querySelectorAll(":scope > .kt-tabs-content-wrap > .kt-tab-inner-content:not(.kt-inner-tab-" + tabName + ")").forEach(content => {
        content.setAttribute("aria-hidden", "true");
      });

      tab.querySelector(":scope > .kt-tabs-content-wrap > .kt-inner-tab-" + tabName).setAttribute("aria-hidden", "false");

      tab.querySelectorAll(":scope > .kt-tabs-content-wrap > .kt-tabs-accordion-title:not(.kt-tabs-accordion-title-" + tabName + ")").forEach(accordion => {
        accordion.classList.replace("kt-tab-title-active", "kt-tab-title-inactive");
        accordion.setAttribute("tabindex", "-1");
        accordion.setAttribute("aria-selected", "false");
      });

      const activeAccordion = tab.querySelector(":scope >.kt-tabs-content-wrap > .kt-tabs-accordion-title.kt-tabs-accordion-title-" + tabName);

      if (activeAccordion) {
        activeAccordion.classList.replace("kt-tab-title-inactive", "kt-tab-title-active");
        activeAccordion.setAttribute("tabindex", "0");
        activeAccordion.setAttribute("aria-selected", "true");
      }
    },

    init() {
      window.KBTabs.setupTabs();

      window.addEventListener("hashchange", window.KBTabs.setActiveWithHash, false);
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", window.KBTabs.init);
  } else {
    window.KBTabs.init();
  }

  document.addEventListener("kb-query-loaded", window.KBTabs.init);
})();
