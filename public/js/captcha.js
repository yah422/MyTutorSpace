// Je limite une valeur entre un minimum et un maximum
function clamp(n, min, max) {
    return Math.min(Math.max(n, min), max);
}

// Je génère un nombre aléatoire entre un minimum et un maximum
function randomNumberBetween(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}

// Je définis la classe PuzzleCaptcha en tant qu'élément personnalisé HTML
class PuzzleCaptcha extends HTMLElement {
    connectedCallback() {
        // Je lis les attributs width, height, piece-width et piece-height de l'élément HTML
        const width = parseInt(this.getAttribute("width"), 10);
        const height = parseInt(this.getAttribute("height"), 10);
        const pieceWidth = parseInt(this.getAttribute("piece-width"), 10);
        const pieceHeight = parseInt(this.getAttribute("piece-height"), 10);
        const maxX = width - pieceWidth;
        const maxY = height - pieceHeight;

        // J'ajoute des classes CSS à l'élément
        this.classList.add("captcha");
        this.classList.add("captcha-waiting-interaction");

        // Je définis des propriétés CSS personnalisées
        this.style.setProperty("--width", `${width}px`);
        this.style.setProperty("--image", `url(${this.getAttribute("src")})`);
        this.style.setProperty("--height", `${height}px`);
        this.style.setProperty("--pieceWidth", `${pieceWidth}px`);
        this.style.setProperty("--pieceHeight", `${pieceHeight}px`);

        // Je sélectionne l'élément d'entrée pour stocker la réponse
        const input = this.querySelector(".captcha-anwser");

        // Je crée l'élément pièce du puzzle
        const piece = document.createElement("div");
        piece.classList.add("captcha-piece");
        this.appendChild(piece);

        // Je définis des variables pour suivre l'état du glissement et la position de la pièce
        let isDragging = false;
        let position = {
            x: randomNumberBetween(0, maxX),
            y: randomNumberBetween(0, maxY),
        };

        // Je positionne initialement la pièce
        piece.style.setProperty(
            "transform",
            `translate(${position.x}px, ${position.y}px)`
        );

        // Je gère le début du glissement
        piece.addEventListener("pointerdown", (e) => {
            isDragging = true;
            document.body.style.setProperty("user-select", "none");
            this.classList.remove("captcha-waiting-interaction");
            piece.classList.add("is-moving");

            // Je gère la fin du glissement
            window.addEventListener(
                "pointerup",
                () => {
                    document.body.style.removeProperty("user-select");
                    piece.classList.remove("is-moving");
                    isDragging = false;
                },
                { once: true }
            );
        });

        // Je gère le mouvement de la souris pendant le glissement
        this.addEventListener("pointermove", (e) => {
            if (!isDragging) {
                return;
            }
            // Je mets à jour la position de la pièce tout en respectant les limites
            position.x = clamp(position.x + e.movementX, 0, maxX);
            position.y = clamp(position.y + e.movementY, 0, maxY);
            piece.style.setProperty(
                "transform",
                `translate(${position.x}px, ${position.y}px)`
            );
            // Je mets à jour la valeur de l'entrée avec la nouvelle position
            input.value = `${position.x}-${position.y}`;
        });
    }
}

// Je définis l'élément personnalisé puzzle-captcha
customElements.define("puzzle-captcha", PuzzleCaptcha);
