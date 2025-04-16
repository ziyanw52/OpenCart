import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {
    event = {
        connected: async () => {
            // I think for simple elements we can get without using a template system
            // Add the select element to the shadow DOM
            this.shadow.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '">' + this.innerHTML + '</select>';

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fonts/fontawesome/css/fontawesome.css');

            this.shadow.addEventListener('change', this.event.onchange);

            let response = await fetch('./catalog/view/data/localisation/country.' + this.getAttribute('language') + '.json');

            response.json().then(this.event.onloaded);
        },
        onloaded: (countries) => {
            let html = this.innerHTML;

            for (let i in countries) {
                html += '<option value="' + countries[i].country_id + '"';

                if (countries[i].country_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + countries[i].name + '</option>';
            }

            this.shadow.querySelector('select').innerHTML = html;
        },
        onchange: async (e) => {
            this.setAttribute('value', e.target.value);
        }
    };
}

customElements.define('x-country', XCountry);