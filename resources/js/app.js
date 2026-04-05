import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card-hover').forEach((card, index) => {
        card.animate(
            [
                { opacity: 0, transform: 'translateY(10px)' },
                { opacity: 1, transform: 'translateY(0)' },
            ],
            {
                duration: 350,
                delay: 40 * index,
                easing: 'ease-out',
                fill: 'both',
            }
        );
    });
});
