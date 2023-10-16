import { ModalsBase } from "./modals-base.js";

export class FormModal extends ModalsBase
{
    appendContent(content)
    {
        $(content).detach().appendTo($(this.modalBody));
    }
}

