/*!
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Application, Controller } from '@hotwired/stimulus';
import { getByTestId, waitFor } from '@testing-library/dom';
import { clearDOM, mountDOM } from '@symfony/stimulus-testing';
import GraphController from '../src/controller'; // Controller used to check the actual controller was properly booted

// Controller used to check the actual controller was properly booted
class CheckController extends Controller {
    connect() {
        this.element.addEventListener('matomoGraph:connect', () => {
            this.element.classList.add('connected');
        });
    }
}

const startStimulus = () => {
    const application = Application.start();
    application.register('check', CheckController);
    application.register('matomoGraph', GraphController);
    return application;
};

describe('GraphController', () => {
    let container;

    beforeEach(() => {
        container = mountDOM(`
            <div
                    data-testid="container"
                    data-controller="check matomoGraph"
                    data-matomoGraph-responsive-value="false"
            >
                <table class="table table-striped hidden">
                    <thead>
                    <tr>
                        <th data-format="%b&nbsp;%#d">Datum</th>
                        <th>Besucher</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>13.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>14.09.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>15.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>16.09.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>17.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>18.09.2023</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>19.09.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>20.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>21.09.2023</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>22.09.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>23.09.2023</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>24.09.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>25.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>26.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>27.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>28.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>29.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>30.09.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>01.10.2023</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>02.10.2023</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>03.10.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>04.10.2023</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>05.10.2023</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>06.10.2023</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>07.10.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>08.10.2023</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>09.10.2023</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>10.10.2023</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>11.10.2023</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>12.10.2023</td>
                        <td>0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        `);
    });

    afterEach(() => {
        clearDOM();
    });

    it('connect', async () => {
        expect(getByTestId(container, 'container')).not.toHaveClass('connected');

        startStimulus();
        await waitFor(() => expect(getByTestId(container, 'container')).toHaveClass('connected'));
    });
});
