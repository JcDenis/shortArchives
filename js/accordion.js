'use strict';

const shortArchivesInit = () => {
  for (const widget of document.querySelectorAll('.shortArchives')) {
    widget.classList.add('shortArchives-js');

    for (const year of widget.querySelectorAll('.arch-years > li > span')) {
      const item = year.parentNode;
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'archives-year';
      button.textContent = year.textContent;
      button.setAttribute('aria-expanded', item.classList.contains('open') ? 'true' : 'false');
      year.replaceWith(button);

      button.addEventListener('click', () => {
        const open = item.classList.contains('open');
        for (const other of widget.querySelectorAll('.arch-years > li.open')) {
          other.classList.remove('open');
          other.querySelector('.archives-year')?.setAttribute('aria-expanded', 'false');
        }
        if (!open) {
          item.classList.add('open');
          button.setAttribute('aria-expanded', 'true');
        }
      });
    }
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', shortArchivesInit);
} else {
  shortArchivesInit();
}
