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

  // form validation + fake submit
  const form = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const toast = document.getElementById('formToast');

  const name = document.getElementById('cName');
  const email = document.getElementById('cEmail');

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;

    valid = checkField(name, name.value.trim().length > 1) && valid;
    valid = checkField(email, isValidEmail(email.value)) && valid;
    valid = checkField(message, message.value.trim().length > 0) && valid;

    if (!valid) return;

    // no backend yet — Phase 3 wires this to PHP/MySQL
    submitBtn.classList.add('is-sent');
    toast.classList.add('is-visible');

    setTimeout(() => {
      form.reset();
      counter.textContent = `${MAX} left`;
      submitBtn.classList.remove('is-sent');
      toast.classList.remove('is-visible');
    }, 2500);
  });

  function checkField(field, condition) {
    const wrapper = field.closest('.c-field');
    wrapper.classList.toggle('has-error', !condition);
    return condition;
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }
});