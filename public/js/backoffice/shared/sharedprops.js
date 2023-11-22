/*
-------------------------------------------
::: COMMON FUNCTIONALITIES ACROSS PAGES :::
-------------------------------------------
*/

import { MessageBox } from "../../modals/messagebox.js";

export class SharedProps
{
    constructor() 
    {
        this.ACTION_NONE       = 0;
        this.ACTION_CREATE     = 1;
        this.ACTION_EDIT       = 2;

        this.ERR_COMMON        = "This action can't be completed. Please reload the page and try again.";

        this.routeCreate = '';
        this.routeEdit   = '';
        this.messageBox  = null; 
        
        $(() => this.onAwake());
    }

    onAwake() 
    {
        this.messageBox         = new MessageBox( $('#messagebox') );
        this.$crudFormModalDom  = $('#crudFormModal');
        this.crudFormModal      = new FormModal( this.$crudFormModalDom );
        this.formAction         = this.$crudFormModalDom.find('#form-action').val();

        this.crudFormModal.closeOnPositive(false);

        this.setCreateRoute( $('#form-action-store').val() )
        this.setEditRoute( $('#form-action-update').val() );
        
        this.setFormAction( this.getFormAction() );
        window.crud = this.crudFormModal;
        window.crudForm = this.crudFormModal.getForm();

        if (this.crudFormModal.hasErrors())
        {
            this.crudFormModal.setDirty();
            this.showCrudForm();
        }

        // Message from server after a successful opration
        this.loadFlashMessage();

        // The first record will be marked with a blinking arrow.
        // It should stop after 5 seconds
        setTimeout(() => {
            $('.td-latest').removeClass('td-latest');
        }, 3500);

        $(document).trigger('domReady');
    }

    //
    // Setters
    //
    setCreateRoute(route) {
        this.routeCreate = route;
    }

    setEditRoute(route) {
        this.routeEdit = route;
    }

    setActionCreate() {
        this.setFormAction( this.ACTION_CREATE );
    }

    setActionEdit() {
        this.setFormAction( this.ACTION_EDIT );
    }

    setActionNone() {
        this.setFormAction( this.ACTION_NONE );
    }

    setFormAction(action) 
    {
        // Memory
        this.formAction = parseInt(action) || 0;

        // DOM
        this.$crudFormModalDom.find('#form-action').val(this.formAction);

        switch (this.formAction)
        {
            case this.ACTION_CREATE:
                this.crudFormModal.setSubmitAction( this.getCreateRoute() );
                break;

            case this.ACTION_EDIT:
                this.crudFormModal.setSubmitAction( this.getEditRoute() );
                break;

            case this.ACTION_NONE:
            default:
                this.crudFormModal.clearSubmitAction();
                break;
        }
    }

    showCrudForm() 
    {
        var titleMap =
        {
            [this.ACTION_CREATE]:   this.$crudFormModalDom.attr('title-create'),
            [this.ACTION_EDIT]:     this.$crudFormModalDom.attr('title-edit'),
            [this.ACTION_NONE]:     this.$crudFormModalDom.attr('title')
        };

        var action = this.getFormAction();
        var title  = (action in titleMap) ? titleMap[action] : titleMap[this.ACTION_NONE];

        this.crudFormModal.present({ title: title });
    }

    //
    // Getters
    //
    getCreateRoute() {
        return this.routeCreate;
    }

    getEditRoute() {
        return this.routeEdit;
    }

    getFormAction() {
        return this.formAction;
    }

    //
    // Utilities
    //
    cleanupForm()
    {
        this.crudFormModal.resetForm();
        $(document).trigger('onCleanupForm');

        this.setActionNone();
    }

    validateEntries()
    {
        const requiredFields = this.crudFormModal.getForm().find('input[required]');
        var errorCount = 0;
        
        $.each(requiredFields, (i, f) => 
        {
            const $root = $(f).closest('.flat-controls');

            var $label  = $root.find('.error-label');
            var $text   = $root.find('.input-text');
            var $alias  = $root.data('alias');
            
            if ($(f).val())
            {
                $text.removeClass('has-error');
                $label.text('');
                return true;        // continue next iteration
            }
            
            switch ($alias)
            {
                case 'text':
                    var $placeholder = $text.find('.main-control').attr('placeholder');
                    $text.addClass('has-error');
                    $label.text((!$placeholder) ? 'Please fill out this field' : `${$placeholder} must be filled out`);
                    break;

                case 'select':
                    $label.text('Please choose an option');
                    break;

                case 'perm-select':
                    console.log('caught here');
                    $label.text('Please select an appropriate permission');
                    break;

                default:
                    $label.text('');
                    break;
            }

            errorCount++;
        });

        return (errorCount < 1);
    }

    isFormFilled() 
    {
        // The filter function will be used to count the number of non-empty input elements. 
        // This eliminates the need for the 'var counter = 0' variable and the $.each loop.
        // This version could be slightly faster because it stops searching as soon as it 
        // finds a non-empty input element or a visible error label.
        const inputs = $(this.crudFormModal.getForm())
            .find('.flat-controls input[type="text"]')
            .not('.ignore-dirty-check')
            .filter(function () 
            {
                return $(this).val();
            });

        // If there are error labels visible, count them as dirty
        const hasErrors = $('.has-error').length > 0;

        // Directly checks whether there are non-empty input elements or visible error labels.
        return inputs.length > 0 || hasErrors;
    }

    loadFlashMessage()
    {
        var flashedMessage = $("#flash-message").val().trim();

        if (flashedMessage == '' || flashedMessage == undefined || flashedMessage == null)
            return;

        try 
        {
            var content = JSON.parse(flashedMessage);

            switch (content.showIn)
            {
                case 'modal-s':
                    this.messageBox.showInfo(content.response, { title: content.title });
                    break;
                case 'modal-w':
                    this.messageBox.showWarning(content.response, { title: content.title });
                    break;
                case 'modal-x':
                    this.messageBox.showDanger(content.response, { title: content.title });
                    break;

                case 'toast':
                    showToast(content.response, '', { type: content.type });
                    break;
            }
        }
        catch (error) 
        {
            console.warn('Unable to read flashed message.' + error);
        }
    }

}