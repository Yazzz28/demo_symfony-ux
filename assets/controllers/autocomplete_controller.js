import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'results', 'selected', 'selectedText'];

    connect() {
        this.currentFocus = -1;
        this.isLanguageSearch = this.element.dataset.autocompleteType === 'languages';

        // Data sources
        this.countries = [
            'France', 'Allemagne', 'Espagne', 'Italie', 'Portugal', 'Belgique',
            'Pays-Bas', 'Suisse', 'Autriche', 'Royaume-Uni', 'Irlande', 'Suède',
            'Norvège', 'Danemark', 'Finlande', 'Pologne', 'République tchèque',
            'Hongrie', 'Grèce', 'Roumanie', 'Bulgarie', 'Croatie'
        ];

        this.languages = {
            'Frontend': ['JavaScript', 'TypeScript', 'HTML', 'CSS', 'React', 'Vue.js', 'Angular'],
            'Backend': ['PHP', 'Python', 'Java', 'C#', 'Ruby', 'Go', 'Node.js'],
            'Mobile': ['Swift', 'Kotlin', 'React Native', 'Flutter', 'Objective-C'],
            'Système': ['C', 'C++', 'Rust', 'Assembly']
        };

        // Click outside to close
        this.clickOutsideHandler = this.handleClickOutside.bind(this);
        document.addEventListener('click', this.clickOutsideHandler);
    }

    disconnect() {
        document.removeEventListener('click', this.clickOutsideHandler);
    }

    search(event) {
        const value = event.target.value.toLowerCase();
        this.resultsTarget.innerHTML = '';
        this.currentFocus = -1;

        if (!value) {
            this.resultsTarget.style.display = 'none';
            return;
        }

        let matches = [];

        if (this.isLanguageSearch) {
            Object.keys(this.languages).forEach(category => {
                this.languages[category].forEach(lang => {
                    if (lang.toLowerCase().includes(value)) {
                        matches.push({ text: lang, category: category });
                    }
                });
            });
        } else {
            matches = this.countries
                .filter(c => c.toLowerCase().includes(value))
                .map(c => ({ text: c }));
        }

        if (matches.length > 0) {
            matches.forEach((match, idx) => {
                const div = document.createElement('div');
                div.className = 'autocomplete-item';

                if (match.category) {
                    div.innerHTML = `<strong>${match.text}</strong> <span style="color: #999;">(${match.category})</span>`;
                } else {
                    div.innerHTML = `<strong>${match.text}</strong>`;
                }

                div.addEventListener('click', () => this.selectItem(match.text));
                this.resultsTarget.appendChild(div);
            });
            this.resultsTarget.style.display = 'block';
        } else {
            this.resultsTarget.style.display = 'none';
        }
    }

    searchLanguages(event) {
        this.isLanguageSearch = true;
        this.search(event);
    }

    navigate(event) {
        const items = this.resultsTarget.querySelectorAll('.autocomplete-item');

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            this.currentFocus++;
            this.addActive(items);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.currentFocus--;
            this.addActive(items);
        } else if (event.key === 'Enter') {
            event.preventDefault();
            if (this.currentFocus > -1 && items[this.currentFocus]) {
                items[this.currentFocus].click();
            }
        } else if (event.key === 'Escape') {
            this.resultsTarget.style.display = 'none';
        }
    }

    selectItem(text) {
        this.inputTarget.value = text;
        this.selectedTextTarget.textContent = text;
        this.selectedTarget.style.display = 'block';
        this.resultsTarget.style.display = 'none';
    }

    addActive(items) {
        if (!items || items.length === 0) return;
        this.removeActive(items);
        if (this.currentFocus >= items.length) this.currentFocus = 0;
        if (this.currentFocus < 0) this.currentFocus = items.length - 1;
        items[this.currentFocus].classList.add('active');
    }

    removeActive(items) {
        items.forEach(item => item.classList.remove('active'));
    }

    handleClickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.resultsTarget.style.display = 'none';
        }
    }
}
