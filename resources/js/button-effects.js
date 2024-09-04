// document.addEventListener('DOMContentLoaded', (event) => {
//     const loginButton = document.querySelector('.login-button');
//     const registerButton = document.querySelector('.register-button');

//     function createParticle(x, y, color) {
//         const particle = document.createElement('div');
//         particle.style.position = 'fixed';
//         particle.style.left = `${x}px`;
//         particle.style.top = `${y}px`;
//         particle.style.width = '5px';
//         particle.style.height = '5px';
//         particle.style.backgroundColor = color;
//         particle.style.borderRadius = '50%';
//         particle.style.pointerEvents = 'none';
//         document.body.appendChild(particle);

//         const angle = Math.random() * Math.PI * 2;
//         const velocity = Math.random() * 3 + 1;
//         const lifetime = Math.random() * 1000 + 500;

//         let opacity = 1;

//         function animate() {
//             particle.style.left = `${parseFloat(particle.style.left) + Math.cos(angle) * velocity}px`;
//             particle.style.top = `${parseFloat(particle.style.top) + Math.sin(angle) * velocity}px`;
//             opacity -= 1 / (lifetime / 16);
//             particle.style.opacity = opacity;

//             if (opacity > 0) {
//                 requestAnimationFrame(animate);
//             } else {
//                 particle.remove();
//             }
//         }

//         requestAnimationFrame(animate);
//     }

//     function createParticles(event, color) {
//         const rect = event.target.getBoundingClientRect();
//         for (let i = 0; i < 20; i++) {
//             createParticle(event.clientX - rect.left, event.clientY - rect.top, color);
//         }
//     }

//     if (loginButton) {
//         loginButton.addEventListener('mousemove', (event) => createParticles(event, '#1DB954'));
//     }

//     if (registerButton) {
//         registerButton.addEventListener('mousemove', (event) => createParticles(event, '#8E2DE2'));
//     }
// });
