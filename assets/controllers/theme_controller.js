import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['body'];
    darkMode = false;

    connect() {
        const stored = localStorage.getItem('darkMode');
        this.darkMode = stored ? JSON.parse(stored) : false;
        this.updateTheme();
    }

    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.updateTheme();
    }

    updateTheme() {
        this.bodyTarget.setAttribute(
            'data-bs-theme',
            this.darkMode ? 'dark' : ''
        );
    }
}
