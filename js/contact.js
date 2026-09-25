/* Contact page behaviour: mobile menu, form validation, char counter, faq toggle */

document.addEventListener('DOMContentLoaded', () => {

  // mobile menu
  const menuBtn = document.getElementById('menuBtn');
  const nav = document.getElementById('siteNav');
  menuBtn.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    menuBtn.setAttribute('aria-expanded', open);
  });

  // faq accordion — one at a time
  document.querySelectorAll('.c-faq-item').forEach(item => {
    const btn = item.querySelector('.c-faq-q');
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('is-open');
      document.querySelectorAll('.c-faq-item').forEach(i => i.classList.remove('is-open'));
      if (!isOpen) item.classList.add('is-open');
    });
  });

  // live char counter for the message box
  const message = document.getElementById('cMessage');
  const counter = document.getElementById('charCount');
  const MAX = 400;
  message.addEventListener('input', () => {
    counter.textContent = `${MAX - message.value.length} left`;
  });

 // form validation
  const form = document.getElementById('contactForm');
  const name = document.getElementById('cName');
  const email = document.getElementById('cEmail');

  if (form) {
    form.addEventListener('submit', (e) => {
      let valid = true;

      valid = checkField(name, name.value.trim().length > 1) && valid;
      valid = checkField(email, isValidEmail(email.value)) && valid;
      valid = checkField(message, message.value.trim().length > 0) && valid;

      // Input වැරදි නම් පමණක් Submit වීම නවත්වන්න
      if (!valid) {
        e.preventDefault();
      }
      // Inputs නිවැරදි නම් PHP backend එක වෙත submit වීමට ඉඩ දෙයි
    });
  }

  function checkField(field, condition) {
    const wrapper = field.closest('.c-field');
    wrapper.classList.toggle('has-error', !condition);
    return condition;
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }
});