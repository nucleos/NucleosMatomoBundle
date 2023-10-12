/*!
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import MatomoGraph from './MatomoGraph';

export default class extends Controller {
    static values = {
        responsive: { type: Boolean, default: true },
    };

    declare responsiveValue: boolean;
    declare readonly hasResponsiveValue: boolean;

    connect() {
        const matomoGraph = new MatomoGraph(this.element, this.responsiveValue);

        this._dispatchEvent('matomoGraph:connect', { matomoGraph: matomoGraph });
    }

    _dispatchEvent(name: string, payload: any = {}) {
        this.element.dispatchEvent(new CustomEvent(name, { detail: payload }));
    }
}
