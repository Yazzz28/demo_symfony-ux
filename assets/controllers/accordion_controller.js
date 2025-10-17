import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['header', 'content'];

    toggle(event) {
        const header = event.currentTarget;
        const index = this.headerTargets.indexOf(header);
        const content = this.contentTargets[index];

        // Toggle current content
        content.classList.toggle('active');

        // Optional: Close other accordions (uncomment to enable)
        // this.contentTargets.forEach((c, i) => {
        //     if (i !== index) {
        //         c.classList.remove('active');
        //     }
        // });
    }
}
