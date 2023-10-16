import { ModalsBase } from "./modals-base.js";

export class FormsLightBox extends ModalsBase
{
    appendContent(content)
    {
        $(content).detach().appendTo($(this.modalBody));
    }
}

