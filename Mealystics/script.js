// Scroll-to-Top Button
const scrollToTopButton = document.createElement('button');
scrollToTopButton.textContent = '⬆';
scrollToTopButton.style.position = 'fixed';
scrollToTopButton.style.bottom = '20px';
scrollToTopButton.style.right = '20px';
scrollToTopButton.style.padding = '10px 15px';
scrollToTopButton.style.backgroundColor = '#FF5722';
scrollToTopButton.style.color = 'white';
scrollToTopButton.style.border = 'none';
scrollToTopButton.style.borderRadius = '5px';
scrollToTopButton.style.cursor = 'pointer';
scrollToTopButton.style.display = 'none';
scrollToTopButton.style.zIndex = '1000';
document.body.appendChild(scrollToTopButton);

scrollToTopButton.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

window.addEventListener('scroll', () => {
  if (window.scrollY > 300) {
    scrollToTopButton.style.display = 'block';
  } else {
    scrollToTopButton.style.display = 'none';
  }
});

// Navbar Active Link Highlight
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach((link) => {
  if (link.href === window.location.href) {
    link.classList.add('active');
  }
});

// Dynamic Testimonials (for index.html)
const testimonials = [
  { text: 'Makanannya enak coy.', author: 'Irene' },
  { text: 'Bagus juga tempatnya wifi gratis.', author: 'Lisa' },
  { text: 'Pelayanan cepat dan ramah.', author: 'Andi' },
];

const testimonialContainer = document.querySelector('.testimonials .row');
if (testimonialContainer) {
  testimonialContainer.innerHTML = ''; // Clear existing testimonials

  testimonials.forEach((testimonial) => {
    const col = document.createElement('div');
    col.className = 'col-md-6';

    const testimonialCard = document.createElement('div');
    testimonialCard.className = 'p-4 border rounded';

    const testimonialText = document.createElement('p');
    testimonialText.textContent = `"${testimonial.text}"`;

    const testimonialAuthor = document.createElement('p');
    testimonialAuthor.className = 'text-end';
    testimonialAuthor.textContent = `- ${testimonial.author}`;

    testimonialCard.appendChild(testimonialText);
    testimonialCard.appendChild(testimonialAuthor);
    col.appendChild(testimonialCard);
    testimonialContainer.appendChild(col);
  });
}

// Menu Page Interactivity (for Menu.html)
const menuItems = document.querySelectorAll('.card');
menuItems.forEach((item) => {
  item.addEventListener('mouseenter', () => {
    item.style.transform = 'scale(1.05)';
    item.style.transition = 'transform 0.3s ease';
  });

  item.addEventListener('mouseleave', () => {
    item.style.transform = 'scale(1)';
  });
});

// Form Validation (for Contact Us form)
const form = document.querySelector('.contact-us form');
if (form) {
  form.addEventListener('submit', (event) => {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !message) {
      event.preventDefault();
      alert('Please fill out all fields before submitting!');
    } else {
      alert('Thank you for your message!');
    }
  });
}