const darkBtn = document.getElementById("darkModeBtn");
const body = document.body;

const scrollItems = document.querySelectorAll('.animate-on-scroll');
const optionButtons = document.querySelectorAll('.pick-option');
const recommendationText = document.getElementById('recommendationText');
const recommendationCard = document.getElementById('recommendationCard');

const recommendationMap = {
    foto: {
        title: 'Adobe Lightroom + Photoshop',
        message: 'Perfecto para retoques, corrección de color y creación de piezas visuales atractivas.'
    },
    vector: {
        title: 'Adobe Illustrator',
        message: 'Ideal para logos, branding, ilustraciones vectoriales y diseños escalables.'
    },
    video: {
        title: 'Adobe Premiere Pro',
        message: 'Excelente para editar clips, agregar transiciones y producir contenido audiovisual profesional.'
    },
    app: {
        title: 'Figma',
        message: 'Una gran opción para prototipos, interfaces de usuario y trabajo colaborativo en diseño de apps.'
    },
    ilustracion: {
        title: 'Krita',
        message: 'Una herramienta libre muy poderosa para ilustración digital y pintura con pinceles realistas.'
    }
};

function onIntersect(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}

const observer = new IntersectionObserver(onIntersect, {
    threshold: 0.16
});

scrollItems.forEach(item => observer.observe(item));

optionButtons.forEach(button => {
    button.addEventListener('click', () => {
        optionButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        const choice = button.dataset.choice;
        const recommendation = recommendationMap[choice];

        if (recommendation) {
            recommendationText.innerHTML = `<strong>${recommendation.title}</strong><br>${recommendation.message}`;
            recommendationCard.classList.add('active');
        }
    });
});

darkBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    darkBtn.textContent = body.classList.contains("dark-mode") ? "☀️" : "🌙";
});
