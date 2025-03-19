import './styles/app.css';
import './styles/gorest.css';

import { InitialHtmlMounter } from './js/services/InitialHtmlMounter.js';
import { HtmlHandler } from './js/services/HtmlHandler.js';

InitialHtmlMounter.mount();
HtmlHandler.reload();
