import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['slide', 'counter'];

    initialize() {
        this.currentIndex = 0;
        this.showSlide(this.currentIndex);
    }

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.slideTargets.length;
        this.showSlide(this.currentIndex);
    }

    previous() {
        this.currentIndex = (this.currentIndex - 1 + this.slideTargets.length) % this.slideTargets.length;
        this.showSlide(this.currentIndex);
    }

    showSlide(index) {
        this.slideTargets.forEach((slide, i) => {
            slide.style.display = i === index ? 'block' : 'none';
        });

        if (this.hasCounterTarget) {
            this.counterTarget.textContent = `${index + 1} / ${this.slideTargets.length}`;
        }
    }
}
