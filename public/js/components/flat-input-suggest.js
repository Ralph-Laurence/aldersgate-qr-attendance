export class FlatInputSuggest
{
    constructor(elementId, datasource) 
    {
        this.datasource         = null;
        this.$ulScrollview      = null;
        this.$textInput         = null;
        this.$rootElement       = null;

        this.$dropdownElement   = null;
        this.dropdownInstance   = null;
        this.dropdownVisible    = false;

        this.ev_itemNotFound    = null;
        this.ev_itemFound       = null;
        
        $((e) =>
        {
            this.$textInput = $(elementId);

            if (this.$textInput.length == 0)
                throw new Error(`There is no input element '${elementId}' found.`);
 
            this.$rootElement  = this.$textInput.closest('.flat-controls');
            this.$ulScrollview = this.$rootElement.find('.dropdown-menu .menu-scrollview');
            
            this.$warningWrapper = this.$rootElement.find('.warning-label-wrapper');
            this.$warningLabel   = this.$warningWrapper.find('.warning-label');

            this.scrollviewMutationOption = 
            {
                'config': { childList: true },
                'node'  : this.$ulScrollview[0]
            };

            this.scrollviewMutationObserver = this.initScrollviewMutationObserver();

            this.datasource = datasource;

            if (this.datasource)
                //this.populateDatalistOptions(this.datasource);
                this.filterDatalist(this.datasource, '');

            this.$dropdownElement = this.$rootElement.find('.dropdown-toggle');
            this.dropdownInstance = new mdb.Dropdown(this.$dropdownElement);

            this.bindEvents();
        });
    }

    bindEvents()
    {
        // Track text changed on the dropdown's input
        var debounce;

        this.$textInput.on('input', (e) =>
        {
            clearTimeout(debounce);

            debounce = setTimeout((obj) =>
            {
                // Force the dropdown menu to show when typing
                if (this.dropdownVisible !== true)
                    this.dropdownInstance.show();

                this.filterDatalist(this.datasource, e.target.value);

            }, 200);
        })
        .on('focus', (e) => this.observeScrollviewMutation())
        .on('blur',  (e) => this.stopMutationObserver());

        this.$dropdownElement.on('shown.bs.dropdown' , (e) => this.dropdownVisible = true);
        this.$dropdownElement.on('hidden.bs.dropdown', (e) => this.dropdownVisible = false);
    }

    // Watch for changes in the scroll view's children
    // if they have been added, or removed
    observeScrollviewMutation()
    {
        this.scrollviewMutationObserver.observe(
            this.scrollviewMutationOption['node'], 
            this.scrollviewMutationOption['config']
        );
    }

    stopMutationObserver() 
    {
        this.scrollviewMutationObserver.disconnect();
    }

    initScrollviewMutationObserver()
    {
        var targetNode = this.scrollviewMutationOption['node'];

        var callback = (mutationsList, observer)  =>
        {
            for(let mutation of mutationsList) 
            {
                if (mutation.type === 'childList') 
                {
                    //console.warn('A child node has been added or removed.');

                    if (targetNode.hasChildNodes() && this.ev_itemFound) 
                    {
                        // console.warn('The parent node not is empty.');
                        this.ev_itemFound();

                        this.hideWarning();
                    } 
                    else
                    {
                        if (this.ev_itemNotFound)
                            this.ev_itemNotFound();

                        var warning = `"${this.$textInput.val()}" does not match any options.`;
                        this.showWarning(warning);
                    }
                }
            }
        };

        return new MutationObserver(callback);
    }

    setDatasource(datasource)
    {
        this.datasource = datasource;
    }

    populateDatalistOptions(datasource)
    {
        if (this.$ulScrollview == null)
            return;

        this.$ulScrollview.empty();

        var docFrag = $(document.createDocumentFragment());

        // The keys are the ones displayed in <li> text
        for (var item in datasource)
        {
            if (item in datasource)
            {
                var a = $('<a></a>')
                    .addClass('dropdown-item')
                    .attr('role', 'button')
                    .text(item);

                // Bind extra attributes
                if (Object.keys(datasource[item]).length > 0)
                    // prefix each attribute with "data-*"
                    $.each(datasource[item], (k, v) => a.attr(`data-${k}`, v));

                // Always bind the data value
                a.attr('data-value', item);

                var li = $('<li></li>').append(a);

                docFrag.append(li);
            }
        }

        this.$ulScrollview.append(docFrag);
    }

    // Function to filter the datalist options based on the input
    // filterDatalist(datasource, input) 
    // {
    //     var objects = Object.keys(datasource)
    //         .filter(id => id.startsWith(input))
    //         .slice(0, 10)    // limit the <li> options to maximum 10 items, for performance
    //         .reduce((obj, key) =>
    //         {
    //             obj[key] = datasource[key];
    //             return obj;
    //         }, {});

    //     this.populateDatalistOptions(objects);
    // }

    // Function to filter the datalist options based on the input
    filterDatalist(datasource, input) 
    {
        var objects = Object.keys(datasource)
            .filter(id => id.startsWith(input))
            .reduce((obj, key, index) =>
            {
                if (index < 10)
                { // Limit the total data to 10 items
                    obj[key] = datasource[key];
                }
                return obj;
            }, {});

        this.populateDatalistOptions(objects);
    }

    set ItemSelected(callback)
    {
        $((x) => 
        {
            $(document).on('click', `.flat-controls .dropdown-menu .menu-scrollview .dropdown-item`,
            (event) => {
                
                // dataset is an object that contains all “data-*” attributes of the element
                callback(event.target.dataset)
                // just call the event -> callback();
                // or pass a data callback(attr);
                // then use it later obj.ItemSelected = (data) => {}
            });
        });
    }

    set ItemNotFound(callback)
    {
        this.ev_itemNotFound = callback;
    }

    set ItemFound(callback)
    {
        this.ev_itemFound = callback;
    }

    showWarning(message)
    {
        this.$warningLabel.text(message);
        this.$warningWrapper.fadeIn();
    }

    hideWarning()
    {
        this.$warningLabel.text('');
        this.$warningWrapper.fadeOut();
    }

    reset()
    {
        this.$textInput.val('');
        this.hideWarning();
    }
}