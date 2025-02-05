import $ from 'jquery';
global.$ = global.jQuery = $;

import './styles/app.scss';

// CSS de AdminLTE
import 'admin-lte/dist/css/adminlte.min.css';
// JS de AdminLTE
import 'admin-lte/dist/js/adminlte.min.js';

import 'bootstrap-icons/font/bootstrap-icons.css';

// Importar Bootstrap y Popper.js
import 'bootstrap';
import '@popperjs/core';

document.addEventListener("DOMContentLoaded", function () {
  const currentUrl = window.location.href;

  document
    .querySelectorAll(".sidebar-menu a.nav-link")
    .forEach(function (link) {
      if (link.href === currentUrl) {
        link.classList.add("active");
        const navItem = link.closest(".nav-item");
        if (navItem) {
          navItem.classList.add("menu-open");
        }
        const treeView = link.closest(".nav-treeview");
        if (treeView) {
          const parentLink = treeView.previousElementSibling;
          if (parentLink) {
            parentLink.classList.add("active");
          }
        }
      }
    });
});