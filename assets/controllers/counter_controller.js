import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['display'];

    initialize() {
        this.count = 0;
    }

    increment() {
        this.count++;
        this.updateDisplay();
    }

    decrement() {
        this.count--;
        this.updateDisplay();
    }

    reset() {
        this.count = 0;
        this.updateDisplay();
    }

    updateDisplay() {
        this.displayTarget.textContent = this.count;
    }
}
